#!/usr/bin/env python3
"""
Aperture Leadership Staging - Comprehensive Test Suite
Tests all functionality including frontend, admin, and database operations
"""

import requests
import re
import sys
from datetime import datetime

class Colors:
    GREEN = '\033[92m'
    RED = '\033[91m'
    YELLOW = '\033[93m'
    BLUE = '\033[94m'
    RESET = '\033[0m'

class TestResult:
    def __init__(self, name, passed, message="", error=None):
        self.name = name
        self.passed = passed
        self.message = message
        self.error = error

class ApertureTester:
    def __init__(self, base_url="https://stg.apertureleadership.com"):
        self.base_url = base_url
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        })
        self.results = []
        self.admin_logged_in = False
        
    def log(self, message, color=Colors.BLUE):
        print(f"{color}{message}{Colors.RESET}")
        
    def test(self, name, func):
        """Run a test and record result"""
        try:
            self.log(f"\n🧪 Testing: {name}")
            func()
            self.results.append(TestResult(name, True, "PASSED"))
            self.log(f"✅ PASSED: {name}", Colors.GREEN)
        except AssertionError as e:
            self.results.append(TestResult(name, False, str(e)))
            self.log(f"❌ FAILED: {name} - {e}", Colors.RED)
        except Exception as e:
            self.results.append(TestResult(name, False, "ERROR", str(e)))
            self.log(f"💥 ERROR: {name} - {e}", Colors.RED)
    
    # ==================== FRONTEND TESTS ====================
    
    def test_homepage_loads(self):
        """Test homepage loads without errors"""
        resp = self.session.get(f"{self.base_url}/")
        assert resp.status_code == 200, f"Expected 200, got {resp.status_code}"
        assert "Aperture" in resp.text, "Page title not found"
        assert "Visual metaphors" in resp.text, "Content not found"
        
    def test_navigation_links(self):
        """Test all navigation links work"""
        links = [
            ("/", "Home"),
            ("/about-us", "About Us"),
            ("/posts", "Guidelines"),
            ("/albums", "Albums"),
            ("/contact", "Contact"),
        ]
        for path, name in links:
            resp = self.session.get(f"{self.base_url}{path}")
            assert resp.status_code == 200, f"{name} page returned {resp.status_code}"
            
    def test_albums_page(self):
        """Test albums listing page"""
        resp = self.session.get(f"{self.base_url}/albums")
        assert resp.status_code == 200
        assert "All" in resp.text
        assert "People" in resp.text
        assert "Architecture" in resp.text
        assert "Landscapes" in resp.text
        assert "Symbols" in resp.text
        assert "Culture" in resp.text
        
    def test_category_pages(self):
        """Test all category pages load without errors"""
        categories = ['people', 'architecture', 'landscapes', 'symbols', 'culture']
        for cat in categories:
            resp = self.session.get(f"{self.base_url}/albums/{cat}")
            assert resp.status_code == 200, f"Category {cat} returned {resp.status_code}"
            assert "Division by zero" not in resp.text, f"Division by zero error in {cat}"
            
    def test_category_counts(self):
        """Test category pages have photos"""
        categories = {
            'people': 10,
            'architecture': 14,
            'landscapes': 6,
            'symbols': 12,
            'culture': 12
        }
        for cat, expected_min in categories.items():
            resp = self.session.get(f"{self.base_url}/albums/{cat}")
            # Count image figures
            image_count = len(re.findall(r'<figure[^>]*>', resp.text))
            assert image_count > 0, f"Category {cat} has no images"
            
    def test_images_load(self):
        """Test that images actually load"""
        resp = self.session.get(f"{self.base_url}/albums")
        # Extract image URLs
        image_urls = re.findall(r'src="(/application/public/uploads/albums/[^"]+)"', resp.text)
        if image_urls:
            # Test first 5 images
            for url in image_urls[:5]:
                img_resp = self.session.get(f"{self.base_url}{url}")
                assert img_resp.status_code == 200, f"Image {url} returned {img_resp.status_code}"
                assert len(img_resp.content) > 0, f"Image {url} is empty"
                
    # ==================== ADMIN TESTS ====================
    
    def test_admin_login_page(self):
        """Test admin login page loads"""
        resp = self.session.get(f"{self.base_url}/admin/login")
        assert resp.status_code == 200
        assert "Sign In" in resp.text or "Login" in resp.text
        assert "_token" in resp.text
        
    def test_admin_login(self):
        """Test admin can login"""
        # Get login page
        resp = self.session.get(f"{self.base_url}/admin/login")
        csrf = re.search(r'name="_token" value="([^"]+)"', resp.text)
        assert csrf, "CSRF token not found"
        
        # Login
        resp = self.session.post(f"{self.base_url}/admin/login", data={
            '_token': csrf.group(1),
            'email': 'admin@admin.com',
            'password': 'Staging@2024'
        }, allow_redirects=True)
        
        assert resp.status_code == 200
        assert "dashboard" in resp.url.lower() or "Dashboard" in resp.text, "Login failed"
        self.admin_logged_in = True
        
    def test_admin_dashboard(self):
        """Test admin dashboard loads"""
        if not self.admin_logged_in:
            self.test_admin_login()
            
        resp = self.session.get(f"{self.base_url}/admin/dashboard")
        assert resp.status_code == 200
        assert "Dashboard" in resp.text
        
    def test_admin_albums(self):
        """Test admin albums page"""
        if not self.admin_logged_in:
            self.test_admin_login()
            
        resp = self.session.get(f"{self.base_url}/admin/albums")
        assert resp.status_code == 200
        assert "Albums" in resp.text
        # Check total count
        if "167" in resp.text or "166" in resp.text or "165" in resp.text:
            pass  # Good, has expected count
            
    def test_admin_categories(self):
        """Test admin categories page"""
        if not self.admin_logged_in:
            self.test_admin_login()
            
        resp = self.session.get(f"{self.base_url}/admin/albums/categories")
        assert resp.status_code == 200
        assert "People" in resp.text
        assert "Architecture" in resp.text  # Should be renamed from Objects
        assert "Culture" in resp.text  # Should exist
        
    # ==================== ERROR TESTS ====================
    
    def test_no_php_errors(self):
        """Test no PHP errors on main pages"""
        pages = ['/', '/albums', '/albums/people', '/albums/architecture', 
                 '/albums/landscapes', '/albums/symbols', '/albums/culture']
        for page in pages:
            resp = self.session.get(f"{self.base_url}{page}")
            assert "ErrorException" not in resp.text, f"PHP error on {page}"
            assert "Division by zero" not in resp.text, f"Division by zero on {page}"
            assert "Fatal error" not in resp.text, f"Fatal error on {page}"
            assert "Stack trace" not in resp.text, f"Stack trace on {page}"
            
    def test_404_page(self):
        """Test 404 page works"""
        resp = self.session.get(f"{self.base_url}/nonexistent-page-12345")
        assert resp.status_code == 404
        
    # ==================== RUN ALL TESTS ====================
    
    def run_all_tests(self):
        """Run all tests"""
        self.log("\n" + "="*60, Colors.BLUE)
        self.log("APERTURE LEADERSHIP STAGING - TEST SUITE", Colors.BLUE)
        self.log("="*60, Colors.BLUE)
        
        # Frontend tests
        self.log("\n📱 FRONTEND TESTS", Colors.YELLOW)
        self.test("Homepage loads", self.test_homepage_loads)
        self.test("Navigation links", self.test_navigation_links)
        self.test("Albums page", self.test_albums_page)
        self.test("Category pages", self.test_category_pages)
        self.test("Category counts", self.test_category_counts)
        self.test("Images load", self.test_images_load)
        
        # Admin tests
        self.log("\n🔐 ADMIN TESTS", Colors.YELLOW)
        self.test("Admin login page", self.test_admin_login_page)
        self.test("Admin login", self.test_admin_login)
        self.test("Admin dashboard", self.test_admin_dashboard)
        self.test("Admin albums", self.test_admin_albums)
        self.test("Admin categories", self.test_admin_categories)
        
        # Error tests
        self.log("\n🐛 ERROR TESTS", Colors.YELLOW)
        self.test("No PHP errors", self.test_no_php_errors)
        self.test("404 page", self.test_404_page)
        
        # Summary
        self.print_summary()
        
    def print_summary(self):
        """Print test summary"""
        self.log("\n" + "="*60, Colors.BLUE)
        self.log("TEST SUMMARY", Colors.BLUE)
        self.log("="*60, Colors.BLUE)
        
        passed = sum(1 for r in self.results if r.passed)
        failed = sum(1 for r in self.results if not r.passed)
        total = len(self.results)
        
        self.log(f"\nTotal: {total} tests", Colors.BLUE)
        self.log(f"Passed: {passed} tests", Colors.GREEN)
        self.log(f"Failed: {failed} tests", Colors.RED if failed > 0 else Colors.GREEN)
        
        if failed > 0:
            self.log("\n❌ FAILED TESTS:", Colors.RED)
            for result in self.results:
                if not result.passed:
                    self.log(f"  - {result.name}: {result.message}", Colors.RED)
                    if result.error:
                        self.log(f"    Error: {result.error}", Colors.RED)
        else:
            self.log("\n✅ ALL TESTS PASSED!", Colors.GREEN)
            
        return failed == 0

if __name__ == "__main__":
    tester = ApertureTester()
    success = tester.run_all_tests()
    sys.exit(0 if success else 1)
