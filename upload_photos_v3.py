#!/usr/bin/env python3
"""
Upload photos to Aperture Leadership staging environment
Uses correct endpoint: POST /admin/albums/session-images/create
"""

import requests
from pathlib import Path
import time
import csv
import re

# Configuration
BASE_URL = "https://stg.apertureleadership.com"
LOGIN_URL = f"{BASE_URL}/admin/login"
DASHBOARD_URL = f"{BASE_URL}/admin/dashboard"
UPLOAD_URL = f"{BASE_URL}/admin/albums/session-images/create"

# Photo folder
PHOTO_FOLDER = Path("/mnt/i/personal/apertureleadership/wetransfer_new-aperture-photos_2026-04-08_1837/Small")

# Admin credentials
EMAIL = "admin@admin.com"
PASSWORD = "Staging@2024"

# Read category mapping from CSV
def load_category_mapping():
    mapping = {}
    csv_path = Path("/mnt/i/personal/apertureleadership/photo-category-mapping.csv")
    with open(csv_path, 'r') as f:
        reader = csv.DictReader(f)
        for row in reader:
            filename = row['Filename']
            category_id = int(row['Category ID'])
            mapping[filename] = category_id
    return mapping

CATEGORY_MAPPING = load_category_mapping()

# Category names for reference
CATEGORIES = {
    1: "People",
    2: "Architecture",
    3: "Landscapes",
    4: "Symbols",
    5: "Culture"
}

def extract_csrf_token(html):
    """Extract CSRF token from HTML"""
    # Try multiple patterns
    patterns = [
        r'name="_token" value="([^"]+)"',
        r'value="([^"]+)"[^>]*name="_token"',
        r'<meta[^>]*csrf-token[^>]*content="([^"]+)"',
    ]
    for pattern in patterns:
        match = re.search(pattern, html)
        if match:
            return match.group(1)
    return None

def login(session):
    """Login to admin panel"""
    # Get login page
    response = session.get(LOGIN_URL)
    csrf_token = extract_csrf_token(response.text)
    if not csrf_token:
        raise Exception("Could not find CSRF token on login page")
    
    print(f"Login CSRF token: {csrf_token[:30]}...")
    
    # Login
    login_data = {
        'email': EMAIL,
        'password': PASSWORD,
        '_token': csrf_token
    }
    
    response = session.post(LOGIN_URL, data=login_data, allow_redirects=True)
    
    if 'dashboard' not in response.url:
        raise Exception(f"Login failed, redirected to: {response.url}")
    
    print(f"Login successful, at: {response.url}")
    return True

def get_csrf_from_dashboard(session):
    """Get CSRF token from dashboard page"""
    response = session.get(DASHBOARD_URL)
    csrf_token = extract_csrf_token(response.text)
    if csrf_token:
        return csrf_token
    
    # If not in HTML, session should have it from cookies
    # Laravel stores CSRF in XSRF-TOKEN cookie
    return None

def upload_photo(session, photo_path, category_id, title):
    """Upload a single photo"""
    # Get CSRF token from dashboard
    csrf_token = get_csrf_from_dashboard(session)
    
    # If not in HTML, try to get from cookies
    if not csrf_token:
        # Laravel typically stores it as XSRF-TOKEN
        for cookie in session.cookies:
            if cookie.name in ['XSRF-TOKEN', 'csrf_token']:
                csrf_token = cookie.value
                break
    
    if not csrf_token:
        raise Exception("Could not get CSRF token")
    
    print(f"  Using CSRF: {csrf_token[:30]}...")
    
    # Prepare form data
    data = {
        'category': str(category_id),
        'title': title,
        'description': '',
        '_token': csrf_token
    }
    
    # Prepare file
    with open(photo_path, 'rb') as f:
        files = {
            'session_image': (photo_path.name, f, 'image/jpeg')
        }
        
        # Add headers to mimic browser
        headers = {
            'X-Requested-With': 'XMLHttpRequest',
        }
        
        response = session.post(UPLOAD_URL, data=data, files=files, headers=headers, allow_redirects=True)
    
    return response

def main():
    # Get all photos
    photos = sorted([f for f in PHOTO_FOLDER.glob("*.jpg") if f.is_file()])
    print(f"Found {len(photos)} photos to upload")
    print(f"Upload URL: {UPLOAD_URL}")
    
    # Create session
    session = requests.Session()
    session.headers.update({
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language': 'en-US,en;q=0.5',
    })
    
    # Login
    login(session)
    
    # Show cookies
    print("\nSession cookies:")
    for cookie in session.cookies:
        print(f"  {cookie.name}: {cookie.value[:30]}...")
    
    # Upload each photo
    success_count = 0
    failed_count = 0
    failed_photos = []
    
    for i, photo_path in enumerate(photos, 1):
        filename = photo_path.name
        
        if filename not in CATEGORY_MAPPING:
            print(f"[{i}/{len(photos)}] SKIPPED: {filename} - No category mapping")
            failed_count += 1
            failed_photos.append(filename)
            continue
        
        category_id = CATEGORY_MAPPING[filename]
        title = filename.replace('.jpg', '')
        category_name = CATEGORIES.get(category_id, "Unknown")
        
        print(f"\n[{i}/{len(photos)}] Uploading: {filename}")
        print(f"  Category: {category_name} (ID: {category_id})")
        print(f"  Title: {title}")
        
        try:
            response = upload_photo(session, photo_path, category_id, title)
            
            print(f"  Response status: {response.status_code}")
            print(f"  Response URL: {response.url}")
            
            # Check if upload was successful
            if response.status_code == 200:
                # Check for success message in response
                if 'success' in response.text.lower() or 'created successfully' in response.text.lower():
                    print(f"  ✓ SUCCESS: {filename}")
                    success_count += 1
                elif 'warning' in response.text.lower() or 'error' in response.text.lower():
                    # Try to extract error message
                    error_match = re.search(r'class="[^"]*(?:error|warning)[^"]*"[^>]*>([^<]+)', response.text, re.IGNORECASE)
                    if error_match:
                        print(f"  ✗ FAILED: {filename} - {error_match.group(1).strip()}")
                    else:
                        print(f"  ✗ FAILED: {filename} - Server returned warning/error")
                    failed_count += 1
                    failed_photos.append(filename)
                else:
                    # Check if redirected to albums page (success)
                    if '/admin/albums' in response.url and 'create' not in response.url:
                        print(f"  ✓ SUCCESS: {filename} (redirected to albums)")
                        success_count += 1
                    else:
                        print(f"  ? UNCLEAR: {filename} - Status 200 but checking...")
                        # Save response for debugging
                        failed_count += 1
                        failed_photos.append(filename)
            else:
                print(f"  ✗ FAILED: {filename} - HTTP {response.status_code}")
                failed_count += 1
                failed_photos.append(filename)
                
        except Exception as e:
            print(f"  ✗ ERROR: {filename} - {str(e)}")
            failed_count += 1
            failed_photos.append(filename)
        
        # Small delay to avoid overwhelming the server
        time.sleep(1)
    
    print("\n" + "="*60)
    print(f"UPLOAD COMPLETE")
    print(f"Successful: {success_count}")
    print(f"Failed: {failed_count}")
    print(f"Total: {len(photos)}")
    
    if failed_photos:
        print("\nFailed photos:")
        for photo in failed_photos:
            print(f"  - {photo}")

if __name__ == "__main__":
    main()
