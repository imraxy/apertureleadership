import requests
import os
import re
import time

# Photo directory
photo_dir = '/mnt/i/personal/apertureleadership/wetransfer_new-aperture-photos_2026-04-08_1837/Small/'

# Category mapping
category_map = {'People': 1, 'Architecture': 2, 'Landscapes': 3, 'Symbols': 4, 'Culture': 5}

# Photo to category mapping (from CSV)
photo_categories = {
    'Art Figures France 2020.jpg': 'People',
    'Atacama Volcanic Springs Chile 2017.jpg': 'Landscapes',
    'Badiyah Dune Oman 2010.jpg': 'Landscapes',
    'Balcony Havana Cuba 2010.jpg': 'Culture',
    'Berlin Cemetary Germany 2003.jpg': 'Symbols',
    'Berlin Clock Germany 2003.jpg': 'Architecture',
    'Berlin Traffic Box Germany 2003.jpg': 'Architecture',
    'Berlin Wall Germany 2003.jpg': 'Culture',
    'Bicycles Cochin India 2015.jpg': 'Culture',
    'Bicycles Florence Italy 1997.jpg': 'Culture',
    'Big Ben London UK 2000.jpg': 'Architecture',
    'Brancusi Sleeping Muse Paris France 2024.jpg': 'Symbols',
    'Bubble London South Bank UK 2013.jpg': 'Symbols',
    'Budapest Castle Hungary 2013.jpg': 'Architecture',
    'Cave Dwellings Oman 2014.jpg': 'Landscapes',
    'Cemetary Atacama Chile 2016.jpg': 'Culture',
    'Che Havana Facade Cuba 2014.jpg': 'Architecture',
    'Church Amorgos Greece 2022.jpg': 'Symbols',
    'Cliff Church Amorogos Greece 2022.jpg': 'Architecture',
    'Coliseum Arles France 2021.jpg': 'Architecture',
    'Dancing in Northern Greece 2022.jpg': 'People',
    'Djoser Tomb Egypt 2014.jpg': 'Symbols',
    'Elevators Heathrow UK 2024.jpg': 'People',
    'Farmer Burkina Faso 2015.jpg': 'People',
    'Fernand Leger Museum Biot France 2025.jpg': 'Symbols',
    'Giacometti Venice Italy 2013.jpg': 'Symbols',
    'Giant Buddah Kamakura Japan 2007.jpg': 'Culture',
    'Guggenheim New York USA 2022.jpg': 'Architecture',
    'Half Dome Yosemite USA 2015.jpg': 'Landscapes',
    'Halong Family Vietnam 2013.jpg': 'People',
    'Hamer Woman Omo Ethiopia 2014.jpg': 'People',
    'Hand of Buddha Kyoto Japan 2013.jpg': 'Culture',
    'Koi Kyoto Japan 2013.jpg': 'Symbols',
    'Lions Chauvet Cave France 2021.jpg': 'Symbols',
    'Lost City Trail Colombia 2024.jpg': 'People',
    'Meteora Monastery Greece 2020.jpg': 'Landscapes',
    'Monkey Temple Rajasthan India 2010.jpg': 'Architecture',
    "Musee D'Orsay Paris France 1995.jpg": 'Symbols',
    'Pere Lachaise Cemetary Paris Farnce 2003.jpg': 'People',
    'Priest Amorgos Greece 2022.jpg': 'People',
    'Reichstag Dome Berlin Germany 2003.jpg': 'Architecture',
    'Reichstag Mirrors Berlin Germany 2003.jpg': 'Symbols',
    'Rice Paddy Ubud 2022.jpg': 'Architecture',
    'Rock Carving Atacama Chile 2013.jpg': 'Architecture',
    'Rodin Mural Bangkok Thailand 2024.jpg': 'Culture',
    'Rosetta Stone British Museum London UK 2015.jpg': 'Culture',
    'Teppanyaki Tokyo Japan 2023.jpg': 'Symbols',
    'Tokyo Station Japan 2023.jpg': 'Culture',
    "Van Gogh's Bedroom St Remy France 2020.jpg": 'Landscapes',
    'Venice Biennale Hand Italy 2018.jpg': 'Culture',
    'Venice Biennale Sculptures Italy 2018.jpg': 'Symbols',
    'Village Elder Tanzania 2015.jpg': 'Symbols',
    'Waterfall Yosemite USA 2016.jpg': 'People',
    'Womens Monument Boston USA 2022.jpg': 'Architecture',
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
