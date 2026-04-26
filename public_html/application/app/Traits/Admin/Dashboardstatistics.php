<?php

namespace App\Traits\Admin;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Models\AlbumCategory;
use App\Models\AlbumSession;
use App\Models\Slider;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\SessionPackage;
use App\Models\Testimonial;

trait Dashboardstatistics
{
	public function totalVisitors()
    {
        return '1';
    }
	
	
	public function totalAlbumsCategories()
    {
        return AlbumCategory::count();
    }
	
	public function totalAlbums()
    {
        return AlbumSession::count();
    }
	
	public function totalSliders()
    {
        return Slider::count();
    }
	
	public function totalJournalCategories()
    {
        return BlogCategory::count();
    }
	
	public function totalJurnalPosts()
    {
        return Post::count();
    }
	
	public function totalPackages()
    {
        return SessionPackage::count();
    }
	
	public function totalTestimonials()
    {
        return Testimonial::count();
    }
}