<?php

namespace App\Http\Controllers\Admin\Albums;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Image;
use Carbon\Carbon;
use App\Http\Requests\Admin\AlbumaddRequest;
use App\Http\Requests\Admin\AlbumupdateRequest;
use App\User;
use App\Models\AlbumCategory;
use App\Models\AlbumSession;
use App\Models\SessionImage;
use App\Traits\UploadTrait;
use Helper;
use Exception;

class SessionimagesController extends Controller
{
    private $image_dir = "uploads/albums", $gallery_dir = "uploads/albums/gallery";

    use UploadTrait;

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
    public function index(Request $request, $id)
    {
        $album_detail = AlbumSession::findOrFail($id);

        if(request()->ajax()) {

            ## Read value
            $draw = request('draw');

            $row = request('start');

            $rowperpage = request('length'); // Rows display per page

            $columnIndex = request('order')[0]['column']; // Column index

            $columnName = request('columns')[$columnIndex]['data']; // Column name

            $columnSortOrder = request('order')[0]['dir']; // asc or desc

            $searchValue = request('search.value'); // Search value

            ## Total number of records without filtering
            $totalRecords = SessionImage::where('album_session_id', $id)->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = SessionImage::where('album_session_id', $id)->filter($searchValue)->count();

            ## Fetch records            
            $users = SessionImage::select('id', 'album_session_id', 'session_image', 'title', 'description')
                ->where('album_session_id', $id)
                ->filter($searchValue)
                ->offset($row)
                ->limit($rowperpage)
                ->orderebycoloumn($columnName, $columnSortOrder)
                ->get();

            $data = array();

            $i = 1;            

            $delete_confirmation_msg = "'Are you sure you want to delete?'";
            
            foreach($users as $key => $row) {

                //<a href="'.route('admin.delete_albums', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.')" class="btn btn-sm btn-outline-danger m-btn m-btn--icon"><span><i class="la la-trash"></i><span>Delete</span></span></a>;
                
                $actions = '<a href="'.route('admin.edit_albums_session_image', ['id' => $id, 'session_image_id' => $row->id]).'" class="btn btn-sm btn-outline-success m-btn m-btn--icon">
                    <span>
                        <i class="la la-edit"></i>
                        <span>Edit</span>
                    </span>
                </a> &nbsp;
                <a href="'.route('admin.delete_albums_session_image', ['id' => $id, 'session_image_id' => $row->id]).'" onclick="return confirm('.$delete_confirmation_msg.')" class="btn btn-sm btn-outline-danger m-btn m-btn--icon"><span><i class="la la-trash"></i><span>Delete</span></span></a>';

                $data[$key]['title'] = $row->title;
                $data[$key]['content'] = $row->description;
                $data[$key]['action'] = $actions;
                
            }

            ## Response
            $response = array(
              "draw" => intval($draw),
              "iTotalRecords" => $totalRecords,
              "iTotalDisplayRecords" => $totalRecordwithFilter,
              "aaData" => $data
            );

            echo json_encode($response);

            exit();
        }

        return view('admin.albums.session_images.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        //$album_detail = AlbumSession::findOrFail($id);
        return view('admin.albums.session_images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation request data
        $request->validate([
            'category' => 'required|exists:album_categories,id',
            'title' => 'required|string|max:191|unique:session_images,title',
          //  'description' => 'string',
            'session_image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        try {
            
            $data['album_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');
            $create = SessionImage::create($data);

            if($create) {

                if($request->hasFile('session_image')) {

                    if ($request->file('session_image')->isValid()) {

                        //Upload original quality thumbnail image 
                        $uplode_image_path = public_path('uploads/albums/'.$create->id);

                        if(!File::isDirectory($uplode_image_path)) {

                            File::makeDirectory($uplode_image_path, 0777, true, true);
                        }
                        
                        $thumb_session_image = $request->file('session_image');

                        $waterMarkUrl = public_path('copyright-img.png');

                        ##########
                        $salt_image  = bin2hex(openssl_random_pseudo_bytes(22));

                        $get_thumb_filename = $salt_image.'.'.$thumb_session_image->getClientOriginalExtension();
                        $image_info = getimagesize($thumb_session_image->getRealPath());
    					$w=$image_info[0]; 
    					$h=$image_info[1]; 
    					$max_width=  $h * 1.5;
    					if($max_width <= $w ){
    						$imageArray['shape']= 'rectangle';
    					}else{
    						$imageArray['shape']= '';
    					}
    					$imageArray['width']= $w;
    					
    					$imageArray['height']= $h;
                        // open an image file
                        $thumb_img = Image::make($thumb_session_image->getRealPath());

                        $thumb_img->insert($waterMarkUrl, 'bottom', 15, 15);

                        // now you are able to resize the instance
                        $thumb_img->save($uplode_image_path.'/'.$get_thumb_filename, 80);

                        $imageArray['thumbnail_image'] =  $get_thumb_filename;

                        $imageArray['session_image'] =  $get_thumb_filename;
                        //End
                        
                        $image= public_path("uploads/albums/".$create->id."/").$get_thumb_filename;
                        
                        $destination= public_path("uploads/albums/".$create->id."/")."small_".$get_thumb_filename;
                        $quality=30;
                        imagejpeg($image, $destination, $quality);
                        
                        $imageArray['small_image']="small_".$get_thumb_filename;
                            
                        SessionImage::where('id', $create->id)->update($imageArray);
                        
                        
                    }
                }

                return redirect(route('admin.albums'))->with('success', 'New album session created successfully.');
                
            }

            return redirect()->back()->with('warning', 'Something went wrong please try again.');

        } catch (Exception $e) {
            
            return redirect()->back()->with('warning', $e->getMessage());   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $session_image_id)
    {
        $album_detail = AlbumSession::findOrFail($id);
        $session = SessionImage::findOrFail($session_image_id);
        return view('admin.albums.session_images.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $session_image_id)
    {
        $session = SessionImage::findOrFail($session_image_id);

        $required_featured_image = $session->session_image ? 'nullable' : 'required';

        //Validation request data
        $request->validate([
            'category' => 'required|exists:album_categories,id',
            'title' => 'required|string|max:191|unique:session_images,title,'.$session_image_id,
           // 'description' => 'string',
            'session_image' => $required_featured_image.'|image|mimes:jpeg,jpg,png',
        ]);

        try {
            
            $data['album_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');

            if($request->hasFile('session_image')) {

                if ($request->file('session_image')->isValid()) {


                    //Upload original quality thumbnail image 
                    $uplode_image_path = public_path('uploads/albums/'.$session_image_id);

                    if(!File::isDirectory($uplode_image_path)) {

                        File::makeDirectory($uplode_image_path, 0777, true, true);
                    }
                    
                    $thumb_session_image = $request->file('session_image');


                    //Unlik old file
                    if(!empty($session->session_image) && File::exists($uplode_image_path.'/'.$session->session_image)) {

                        unlink($uplode_image_path.'/'.$session->session_image);
                    }

                    $waterMarkUrl = public_path('copyright-img.png');

                    ##########
                    $salt_image  = bin2hex(openssl_random_pseudo_bytes(22));

                    $get_thumb_filename = $salt_image.'.'.$thumb_session_image->getClientOriginalExtension();
                    
                    $image_info = getimagesize($thumb_session_image->getRealPath());
					$w=$image_info[0]; 
					$h=$image_info[1]; 
					$max_width=  $h * 1.5;
					if($max_width <= $w ){
						$data['shape']= 'rectangle';
					}else{
						$data['shape']= '';
					}
					
                    // open an image file
                    $thumb_img = Image::make($thumb_session_image->getRealPath());

                    $thumb_img->insert($waterMarkUrl, 'bottom', 15, 15);

                    // now you are able to resize the instance
                    $thumb_img->save($uplode_image_path.'/'.$get_thumb_filename, 80);

                    $data['thumbnail_image'] =  $get_thumb_filename;

                    $data['session_image'] =  $get_thumb_filename;
                    //End
                    
                    $image= public_path("uploads/albums/".$session_image_id."/").$get_thumb_filename;
                        
                    $destination= public_path("uploads/albums/".$session_image_id."/")."small_".$get_thumb_filename;
                        $quality=30;
                    imagejpeg($image, $destination, $quality);
                        
                    $data['small_image']="small_".$get_thumb_filename;
                }
            }

            $create = SessionImage::where('id', $session_image_id)->update($data);

            if($create) {

                    return redirect(route('admin.albums'))->with('success', 'Album session image detail updated successfully.');
                    
                }

            return redirect()->back()->with('warning', 'Something went wrong please try again.');

        } catch (Exception $e) {
            
            return redirect()->back()->with('warning', $e->getMessage());   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($session_image_id)
    {
        try {           
                
                $uplode_dir = public_path('uploads/albums/'.$session_image_id);
            
                if(File::exists($uplode_dir)) {

                    File::deleteDirectory($uplode_dir);
                }

                SessionImage::where('id', $session_image_id)->delete();

                return redirect(route('admin.albums'))->with('success', 'Album session detail deleted successfully.');

        } catch (Exception $e) {
           
          return redirect()->back()->with('warning', $e->getMessage()); 
        }
    }
    
    public function imageconvert(){
        $img = new Imagick($imgname);
        if ($img) {
          $width=$img->getImageWidth();
          $height=$img->getImageHeight();
          $res=$img->getImageResolution();
          $colorspace=$img->getImageColorspace();
        
          $resx=$res['x'];
          $resy=$res['y'];
          echo 'Image is '.$width.'x'.$height.' resolution: '.$resx.'x'.$resy.' colorspace='.$colorspace.'='.$colorspace_array[$colorspace];
          $cmw=($width/$resx)*2.54;
          $cmh=($height/$resy)*2.54;
          echo 'Image is '.$cmw.'cm x '.$cmh.'cm';
        
          // creating 72dpi version
          $w72=round($width*72/$resx);
          $h72=round($height*72/$resy);
          if ($w72>$width || $h72>$height) {
            $w72=$width;
            $h72=$height;
          }
        
          $img->resizeImage($w72,$h72,imagick::FILTER_QUADRATIC,1);
          $img->writeImage('newimage.png');
        
        } else {
          die('Unknown image format');
        }
        
        die;
    }
}
