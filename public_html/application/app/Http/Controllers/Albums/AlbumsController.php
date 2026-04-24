<?php

namespace App\Http\Controllers\Albums;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AlbumSession;
use App\Models\AlbumCategory;
use App\Models\SessionImage;
use DB;
class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug=NULL)
    {   
        if (Auth::check()) {

            $session_tbl = new SessionImage;

        }else{

           $session_tbl = SessionImage::take(10); 
        }

        if(!empty($slug)) {

            $category = AlbumCategory::where('slug', $slug)->first();

            if(!$category) {

                return abort(404);
            }

            $albums = $session_tbl->where('album_category_id', $category->id);

        }else {
            
            $albums = $session_tbl;

        }

        $albums = $albums->with('album_category')->orderBy('created_at','DESC')->get();
         
        $albumcategories = AlbumCategory::get();
       
		return view('albums.albums', compact('albums', 'albumcategories', 'slug'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sessionImgaes($slug=NULL)
    {
        if(!empty($slug)) {

            $category = AlbumCategory::where('slug', $slug)->first();

            if(!$category) {
                return abort(404);
            }

            $albums = SessionImage::where('album_category_id', $category->id)->latest()->get();

        }else {
            
            $albums = SessionImage::latest()->take(20)->get();

        }

        $albumcategories = AlbumCategory::get();

        return view('albums.session_images', compact('albums', 'albumcategories', 'slug'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {	
		$album = AlbumSession::where('slug', $slug)->first();
		
		if(!$album) {
			return redirect()->back();
		}
        return view('albums.session', compact('album'));
    }
    public function getAlbumImage(Request $request){
		
		$limit= 9;
		$start = $request->start;
		
		if (Auth::check()) {
 
            $session_tbl = new SessionImage;

        }else{
 
           $session_tbl = SessionImage::take(9); 
        }
 
		if(!empty($request->slug)) {

            $category = AlbumCategory::where('slug', $request->slug)->first();

            $albums = $session_tbl->where('album_category_id', $category->id);

        }else {
            
            $albums = $session_tbl;

        }
		if (Auth::check()) {

            $albums = $albums
					->limit($limit)
                    ->offset($start)
                    ->orderBy('created_at','DESC')
					->get();
		 
			$auth =true;
		}else{

           $albums = $albums->orderBy('created_at','DESC')->get();
		   $auth =false;
        }
		 
					
		return ['albums'=>$albums,'status'=>true,'auth'=>$auth];			
		
	}
	
	public function setImageShape(){
		
		$data= DB::table('session_images')->get();
		foreach($data as $path){
			 
			//$path_image = url("application/public/uploads/albums/".$path->id."/".$path->featured_image);
			$path_image = public_path("uploads/albums/".$path->id."/".$path->session_image);
			if (file_exists($path_image)){
				$image_info = getimagesize($path_image); 
				$w=$image_info[0]; 
				$h=$image_info[1]; 
				$max_width=  $h * 1.5;
				if($max_width <= $w ){
					DB::table('session_images')->where(['id'=>$path->id])->update(['shape'=>'rectangle']);
				} 
			} 
	 	}
	 	 
	}
	
	
	public function getImageShape(){
		
		$data= DB::table('session_images')
		//->where(['id'=>18])
		->get();
		foreach($data as $path){
			 
			//$path_image = url("application/public/uploads/albums/".$path->id."/".$path->featured_image);
			$path_image = public_path("uploads/albums/".$path->id."/".$path->session_image);
			if (file_exists($path_image)){
				$info = getimagesize($path_image); 
				 
                if ($info['mime'] == 'image/jpeg') 
                    $image = imagecreatefromjpeg($path_image);
            
                elseif ($info['mime'] == 'image/gif') 
                    $image = imagecreatefromgif($path_image);
            
                elseif ($info['mime'] == 'image/png') 
                    $image = imagecreatefrompng($path_image);
                $destination= public_path("uploads/albums/".$path->id."/")."small_".$path->session_image;
                
                $quality=30;
                imagejpeg($image, $destination, $quality);
            
             
				
				
			    DB::table('session_images')->where(['id'=>$path->id])->update(['small_image'=>"small_".$path->session_image]);
		 	} 
	 	}
	 	 
	}

}
