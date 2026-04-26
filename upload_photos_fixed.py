#!/usr/bin/env python3
"""
Upload photos to Aperture Leadership staging environment
Corrected version with proper endpoint: /admin/albums/session-images/create
"""

import requests
from pathlib import Path
import time
import csv

# Configuration
BASE_URL = "https://stg.apertureleadership.com"
LOGIN_URL = f"{BASE_URL}/admin/login"
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

def get_csrf_token(session):
    """Get CSRF token from login page"""
    response = session.get(LOGIN_URL)
    if response.status_code != 200:
        raise Exception(f"Failed to get login page: {response.status_code}")
    
    # Extract CSRF token
    import re
    match = re.search(r'name="_token" value="([^"]+)"', response.text)
    if not match:
        raise Exception("Could not find CSRF token")
    return match.group(1)

def login(session):
    """Login to admin panel"""
    csrf_token = get_csrf_token(session)
    
    login_data = {
        'email': EMAIL,
        'password': PASSWORD,
        '_token': csrf_token
    }
    
    response = session.post(LOGIN_URL, data=login_data)
    
    # Check if login successful (should redirect to dashboard)
    if 'dashboard' not in response.url and response.status_code != 200:
        raise Exception(f"Login failed: {response.status_code}")
    
    print(f"Login successful, redirected to: {response.url}")
    return True

def upload_photo(session, photo_path, category_id, title):
    """Upload a single photo"""
    # Get fresh CSRF token for upload
    response = session.get(UPLOAD_URL)
    import re
    match = re.search(r'name="_token" value="([^"]+)"', response.text)
    if not match:
        raise Exception("Could not find CSRF token for upload")
    csrf_token = match.group(1)
    
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
        response = session.post(UPLOAD_URL, data=data, files=files)
    
    return response

def main():
    # Get all photos
    photos = sorted([f for f in PHOTO_FOLDER.glob("*.jpg") if f.is_file()])
    print(f"Found {len(photos)} photos to upload")
    
    # Create session
    session = requests.Session()
    session.headers.update({
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    })
    
    # Login
    login(session)
    
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
        
        print(f"[{i}/{len(photos)}] Uploading: {filename} -> Category: {category_name} (ID: {category_id})")
        
        try:
            response = upload_photo(session, photo_path, category_id, title)
            
            # Check if upload was successful
            if response.status_code == 200:
                # Check for success message in response
                if 'success' in response.text.lower() or 'created successfully' in response.text.lower():
                    print(f"  ✓ SUCCESS: {filename}")
                    success_count += 1
                elif 'warning' in response.text.lower() or 'error' in response.text.lower():
                    print(f"  ✗ FAILED: {filename} - Server returned warning/error")
                    failed_count += 1
                    failed_photos.append(filename)
                else:
                    print(f"  ? UNKNOWN: {filename} - Status 200 but unclear result")
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
        time.sleep(0.5)
    
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
