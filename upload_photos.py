import requests
import os
import re
import time

# Photo directory
photo_dir = 'wetransfer_new-aperture-photos_2026-04-08_1837/Small/'

# Category mapping
category_map = {'People': 1, 'Architecture': 2, 'Landscapes': 3, 'Symbols': 4, 'Culture': 5}

# Photo to category mapping
photo_categories = {
    'Art Figures France 2020.jpg': 'People',
    'Balcony Havana Cuba 2010.jpg': 'People',
    'Batman & Spiderman Borneo 2013.jpg': 'People',
    'Books London South Bank 2013.jpg': 'People',
    'Bruges Square Belgium 2014.jpg': 'People',
    'Carpenters Turkey 2014.jpg': 'People',
    'Chess Players Venice 2010.jpg': 'People',
    'Dolls House Bruges Belgium 2014.jpg': 'People',
    'Flower Seller Kolkata India 2016.jpg': 'People',
    'Mussels Bruges 2014.jpg': 'People',
    'Cao Dai Temple Vietnam 2015.jpg': 'Culture',
    'Carnival Venice 2014.jpg': 'Culture',
    'Chinese New Year Sydney 2019.jpg': 'Culture',
    'Dragon Dance Sydney 2019.jpg': 'Culture',
    'Floating Market Vietnam 2015.jpg': 'Culture',
    'Ganesh Festival Mumbai India 2015.jpg': 'Culture',
    'Goddess Temple China 2017.jpg': 'Culture',
    'Harbour Festival Hong Kong 2016.jpg': 'Culture',
    'Kumbh Mela India 2016.jpg': 'Culture',
    'Lanterns Chiang Mai Thailand 2015.jpg': 'Culture',
    'Lanterns Hoi An Vietnam 2015.jpg': 'Culture',
    'Bicycles Cochin India 2015.jpg': 'Architecture',
    'Blue Door Essaouira Morocco 2014.jpg': 'Architecture',
    'Church Budapest Hungary 2013.jpg': 'Architecture',
    'Gaudi Barcelona Spain 2012.jpg': 'Architecture',
    'Havana Balconies Cuba 2010.jpg': 'Architecture',
    'Jodhpur Blue City India 2016.jpg': 'Architecture',
    'Petronas Towers Kuala Lumpur 2014.jpg': 'Architecture',
    'Port Antonio Jamaica 2018.jpg': 'Architecture',
    'Prayer Hall Temple China 2017.jpg': 'Architecture',
    'Railing Hoi An Vietnam 2015.jpg': 'Architecture',
    'Roof Tops Havana Cuba 2010.jpg': 'Architecture',
    'Rock Carving Atacama Chile 2013.jpg': 'Architecture',
    'Womens Monument Boston USA 2022.jpg': 'Architecture',
    'Atacama Abandoned Mine Chile 2016.jpg': 'Landscapes',
    'Backwaters Kerala India 2015.jpg': 'Landscapes',
    'Bridge Bupdapest Hungary 2013.jpg': 'Landscapes',
    'Cave Temple China 2017.jpg': 'Landscapes',
    'Harbour Hong Kong 2016.jpg': 'Landscapes',
    'Kashmir Mountains India 2016.jpg': 'Landscapes',
    'Baloons Pushkar India 2013.jpg': 'Symbols',
    'Berlin Wall Memorial Germany 2011.jpg': 'Symbols',
    'Bread Istanbul Turkey 2015.jpg': 'Symbols',
    'Buddha Temple China 2017.jpg': 'Symbols',
    'Colosseum Rome Italy 2011.jpg': 'Symbols',
    'Flowers Market Mumbai India 2016.jpg': 'Symbols',
    'Ganges Benares India 2016.jpg': 'Symbols',
    'Masks Venice 2014.jpg': 'Symbols',
    'Meteora Monastery Greece 2010.jpg': 'Symbols',
    'Prayer Flags Leh India 2016.jpg': 'Symbols',
    'Statue New Zealand 2017.jpg': 'Symbols',
    'Temple Bells China 2017.jpg': 'Symbols',
    'Tiananmen Square Beijing 2017.jpg': 'Symbols',
    'Tribal Market Ethiopia 2018.jpg': 'Symbols',
}

# Get all photos
photos = sorted([f for f in os.listdir(photo_dir) if f.lower().endswith(('.jpg', '.jpeg', '.png'))])
print(f"Found {len(photos)} photos")

# Check for unmatched photos
matched = [p for p in photos if p in photo_categories]
unmatched = [p for p in photos if p not in photo_categories]
print(f"Matched: {len(matched)}, Unmatched: {len(unmatched)}")
if unmatched:
    print(f"Unmatched photos: {unmatched}")

# Login
session = requests.Session()
resp = session.get("https://stg.apertureleadership.com/admin/login", verify=True)
csrf_match = re.search(r'name="_token" value="([^"]+)"', resp.text)
if not csrf_match:
    print("ERROR: Could not get CSRF token")
    exit(1)
csrf = csrf_match.group(1)

resp = session.post("https://stg.apertureleadership.com/admin/login", data={
    '_token': csrf, 'email': 'admin@admin.com', 'password': 'Staging@2024'
}, allow_redirects=True)
print(f"Login: {'OK' if 'Dashboard' in resp.text else 'FAILED'}")

# Get fresh CSRF for form
resp = session.get("https://stg.apertureleadership.com/admin/albums/create", verify=True)
csrf_match = re.search(r'name="_token" value="([^"]+)"', resp.text)
if not csrf_match:
    print("ERROR: Could not get form CSRF")
    exit(1)
csrf = csrf_match.group(1)

# Upload
upload_url = "https://stg.apertureleadership.com/admin/albums/session-images/create"
success, errors = 0, 0

for i, photo in enumerate(photos, 1):
    path = os.path.join(photo_dir, photo)
    cat = photo_categories.get(photo, 'People')
    title = os.path.splitext(photo)[0]
    
    try:
        with open(path, 'rb') as f:
            resp = session.post(upload_url, files={'session_image': (photo, f, 'image/jpeg')}, data={
                '_token': csrf, 'category': str(category_map[cat]), 'title': title, 'description': ''
            }, allow_redirects=True)
        
        if resp.status_code in [200, 302]:
            success += 1
            print(f"[{i}/{len(photos)}] OK: {photo} -> {cat}")
        else:
            errors += 1
            print(f"[{i}/{len(photos)}] ERR: {photo} - Status {resp.status_code}")
        
        # Refresh CSRF
        csrf_match = re.search(r'name="_token" value="([^"]+)"', resp.text)
        if csrf_match:
            csrf = csrf_match.group(1)
            
    except Exception as e:
        errors += 1
        print(f"[{i}/{len(photos)}] ERR: {photo} - {str(e)[:50]}")
    
    time.sleep(0.2)

print(f"\nDone: {success} success, {errors} errors")