<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Http\Response;
use App\Models\Post;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $posts = Post::latest()->get();
        $posts = Post::get();
        $categories = BlogCategory::get();
		return view('posts.posts', compact('posts', 'categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  str  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {	
		$post = Post::where('slug', $slug)->first();
		
		if(!$post) {
			return redirect()->back();
		}
		
		//$cookie_name = "user_visitor";
		
		//$cookie_value = $request->getClientIp();
		
		//setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		$post->no_of_views = $post->no_of_views+1;
		$post->update();
		
        return view('posts.detail', compact('post'));
    }
	
	public function setCookie(Request $request) {
		  $minutes = 1;
		  $response = new Response('Hello World');
		  $response->withCookie(cookie('name', 'virat', $minutes));
		  return $response;
	}
	public function getCookie(Request $request) {
		$value = $request->cookie('name');
		echo $value;
	}
}
