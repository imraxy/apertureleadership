#!/usr/bin/env python3
"""
Fix missing width/height for Culture category photos
This script generates SQL to update the database
"""

import requests
import re

BASE_URL = "https://stg.apertureleadership.com"
ADMIN_EMAIL = "admin@admin.com"
ADMIN_PASSWORD = "Staging@2024"

# Login to admin
session = requests.Session()
session.headers.update({
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
})

# Get login page
resp = session.get(f"{BASE_URL}/admin/login")
csrf = re.search(r'name="_token" value="([^"]+)"', resp.text).group(1)

# Login
resp = session.post(f"{BASE_URL}/admin/login", data={
    '_token': csrf,
    'email': ADMIN_EMAIL,
    'password': ADMIN_PASSWORD
}, allow_redirects=True)

if "dashboard" not in resp.url:
    print("Failed to login")
    exit(1)

print("Logged in successfully")

# Get the albums admin page to see Culture category photos
resp = session.get(f"{BASE_URL}/admin/albums")
print(f"Admin albums page status: {resp.status_code}")

# Culture category photos (from our upload mapping)
# We need to find the IDs of Culture photos and update their dimensions

# Generate SQL to fix missing dimensions
# We'll set default dimensions for photos that have null/0 width/height

sql = """
-- Fix Culture category photos with missing dimensions
-- Set default dimensions to prevent division by zero

UPDATE session_images 
SET width = 800, height = 600, updated_at = NOW()
WHERE album_category_id = 5 
AND (width IS NULL OR width = 0 OR height IS NULL OR height = 0);

-- Verify the fix
SELECT id, title, width, height 
FROM session_images 
WHERE album_category_id = 5 
ORDER BY id DESC 
LIMIT 20;
"""

print("\nSQL to fix Culture category:")
print(sql)

# Save SQL to file
with open('/mnt/i/personal/apertureleadership/fix-culture-dimensions.sql', 'w') as f:
    f.write(sql)

print("\nSQL saved to fix-culture-dimensions.sql")
print("\nTo apply this fix, you need to run the SQL in PHPMyAdmin or via SSH:")
print("1. Login to Hostinger hPanel")
print("2. Go to Databases → PHPMyAdmin")
print("3. Select database u285921350_stg")
print("4. Run the SQL above")
