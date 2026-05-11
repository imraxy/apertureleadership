<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\AlbumSession;
use App\Models\Testimonial;

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

        $album_sessions = AlbumSession::where('status', 1)->orderBy('id', 'desc')->limit(6)->get(['id', 'album_category_id', 'title', 'description', 'permalink', 'featured_image', 'slug']);

        $testimonials = Testimonial::orderBy('id', 'desc')->limit(6)->get(['name', 'message', 'image']);
        
        $about_tbl = new AboutUs;

        $about_me = $about_tbl->aboutmeDetail();

        return view('front.index', compact('sliders', 'about_me', 'album_sessions', 'testimonials'));
    }
}
