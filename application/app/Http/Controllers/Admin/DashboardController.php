<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\AlbumSession;
use App\Models\Post;
use App\Traits\Admin\Dashboardstatistics;

class DashboardController extends Controller
{
	use Dashboardstatistics;
    
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $admin_id = Auth::guard('admin')->user()->id;

        $data['latests_blogs'] = Post::select('id', 'blog_category_id', 'title', 'featured_image', 'description')->limit(5)->get();
        $data['album_sessions'] = AlbumSession::select('id', 'album_category_id', 'title', 'featured_image', 'description')->limit(10)->get();
		$data['totalVisitors'] = $this->totalVisitors();
		$data['totalAlbumsCategories'] = $this->totalAlbumsCategories();
        $data['totalAlbums'] = $this->totalAlbums();
        $data['totalSliders'] = $this->totalSliders();
        $data['totalJournalCategories'] = $this->totalJournalCategories();
        $data['totalJurnalPosts'] = $this->totalJurnalPosts();
        $data['totalPackages'] = $this->totalPackages();
        $data['totalTestimonials'] = $this->totalTestimonials();
		
        return view('admin.dashboard', $data);
    }
}
