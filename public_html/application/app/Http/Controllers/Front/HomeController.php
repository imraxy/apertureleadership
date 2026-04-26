<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\AlbumSession;
use App\Models\Testimonial;
use App\Models\SessionImage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();

        // Get new session images for the marquee showcase (split into two sets for variety)
        $allSessionImages = SessionImage::orderBy('id', 'desc')->get(['id', 'title', 'session_image', 'small_image']);
        $sessionImagesRow1 = $allSessionImages->slice(0, 12); // First 12 images
        $sessionImagesRow2 = $allSessionImages->slice(12, 12); // Next 12 images (different set)
        
        // If not enough images for row 2, fill with more from beginning
        if ($sessionImagesRow2->count() < 12) {
            $sessionImagesRow2 = $allSessionImages->slice(0, 12);
        }

        $album_sessions = AlbumSession::where('status', 1)->orderBy('id', 'desc')->limit(6)->get(['id', 'album_category_id', 'title', 'description', 'permalink', 'featured_image', 'slug']);

        $testimonials = Testimonial::orderBy('id', 'desc')->limit(6)->get(['name', 'message', 'image']);
        
        $about_tbl = new AboutUs;

        $about_me = $about_tbl->aboutmeDetail();

        return view('front.index', compact('sliders', 'sessionImagesRow1', 'sessionImagesRow2', 'about_me', 'album_sessions', 'testimonials'));
    }
}
