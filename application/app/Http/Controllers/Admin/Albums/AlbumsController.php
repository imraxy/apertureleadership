<?php

namespace App\Http\Controllers\Admin\Albums;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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

class AlbumsController extends Controller
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
    public function index()
    {
        return view('admin.albums.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function albums(Request $request)
    {
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
            $totalRecords = SessionImage::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = SessionImage::filter($searchValue)->count();

            ## Fetch records            
            $users = SessionImage::select('id', 'album_category_id', 'title')
                ->filter($searchValue)
                ->offset($row)
                ->limit($rowperpage)
                ->orderebycoloumn($columnName, $columnSortOrder)
                ->get();

            $data = array();

            $i = 1;            

            $delete_confirmation_msg = "'Are you sure you want to delete?'";
            
            foreach($users as $key => $row) {

                $actions = '<a href="'.route('admin.edit_albums', [$row->id]).'" class="btn btn-sm btn-outline-success m-btn m-btn--icon">
                    <span>
                        <i class="la la-edit"></i>
                        <span>Edit</span>
                    </span>
                </a> &nbsp;
                <a href="'.route('admin.delete_albums_session_image', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.')" class="btn btn-sm btn-outline-danger m-btn m-btn--icon">
                    <span>
                        <i class="la la-trash"></i>
                        <span>Delete</span>
                    </span>
                </a>';

                $data[$key]['title'] = $row->title;
                $data[$key]['category'] = $row->albumCategory->name ?? 'Uncategorized';
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = AlbumCategory::where('status', 1)->get();
        
        return view('admin.albums.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(AlbumaddRequest $request)
    {
        try {
            
            $data['album_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['permalink'] = $request->permalink ? : NULL;
            $data['video_link'] = $request->video_link ? : NULL;
            $data['meta_keywords'] = $request->meta_keywords ? : NULL;
            $data['meta_description'] = $request->meta_description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');

            $create = AlbumSession::create($data);

            if($create) {

                if($request->hasFile('featured_image')) {

                    if ($request->file('featured_image')->isValid()) {

                        //$uplode_dir = public_path($this->image_dir);
                        $uplode_dir = public_path('uploads/albums/'.$create->id);
                        
                        $featured_image = $request->file('featured_image');

                        //$data['featured_image'] =  $this->uploadTwo($featured_image, $uplode_dir);
                        $data['featured_image'] =  $this->uploadwithWartermark($featured_image, $uplode_dir);

                        AlbumSession::where('id', $create->id)->update($data);

                    }
                }
                
                return redirect(route('admin.albums'))->with('success', 'New album session created successfully.');
                    
            }

            return redirect(route('admin.albums'))->with('warning', 'Something went wrong please try again.');

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
    public function edit($id)
    {
        $album = SessionImage::findOrFail($id);
        $categories = AlbumCategory::where('status', 1)->get();
        return view('admin.albums.edit', compact('album', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlbumupdateRequest $request, $id)
    {
        $album = AlbumSession::findOrFail($id);

        try {
            
            $data['album_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['permalink'] = $request->permalink ? : NULL;
            $data['video_link'] = $request->video_link ? : NULL;
            $data['meta_keywords'] = $request->meta_keywords ? : NULL;
            $data['meta_description'] = $request->meta_description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');

            if($request->hasFile('featured_image')) {

                if ($request->file('featured_image')->isValid()) {

                    $uplode_dir = public_path('uploads/albums/'.$id);

                    $featured_image = $request->file('featured_image');

                    //Unlik old file
                    if(!empty($album->featured_image) && File::exists($uplode_dir.'/'.$album->featured_image)) {

                        unlink($uplode_dir.'/'.$album->featured_image);
                    }

                    //$data['featured_image'] =  $this->uploadTwo($featured_image, $uplode_dir);
                    $data['featured_image'] =  $this->uploadwithWartermark($featured_image, $uplode_dir);

                }
            }

            $update = AlbumSession::where('id', $id)->update($data);

            if($update) {

                return redirect(route('admin.albums'))->with('success', 'Album session detail updated successfully.');
                    
            }

            return redirect(route('admin.albums'))->with('warning', 'Something went wrong please try again.');

        } catch (Exception $e) {
            
            return redirect()->back()->with('warning', $e->getMessage());   
        }
    }


    private function uploadsGalleryimages($gallery_photos, $session_id)
    {
        try {
            
            
            //uploade sessions gallery images
            if(is_array($gallery_photos)) {

                $uplode_image_path = public_path('uploads/albums/'.$session_id.'/sessions');

                if(!File::isDirectory($uplode_image_path)) {

                    File::makeDirectory($uplode_image_path, 0777, true, true);

                }

                foreach ($gallery_photos as $fileKey => $fileObject ) {

                    // make sure each file is valid
                    if ($fileObject->isValid()) {

                        $photo_img  = $gallery_photos[$fileKey] ?? '';

                        if(!empty($photo_img)) {

                            $salt_image  = time().rand(1111, 9999);

                            $session_mage = $salt_image.'.'.$photo_img->getClientOriginalExtension();

                            $photo_img->move($uplode_image_path, $session_mage);

                            $creategallery = new SessionImage;
                            $creategallery->album_session_id = $session_id;
                            $creategallery->session_mage = $session_mage;
                            $creategallery->save();
                        }
                    }
                }           
            }

        } catch (Exception $e) {
            
        }       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $album = AlbumSession::findOrFail($id);

            $uplode_dir = public_path('uploads/albums/'.$id);
            
            if(File::exists($uplode_dir)) {

                File::deleteDirectory($uplode_dir);
            }

            SessionImage::where('album_session_id', $id)->delete();

            $album->delete();

            return redirect(route('admin.albums'))->with('success', 'Album session detail deleted successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('warning', $e->getMessage());            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroygalleryImage(Request $request)
    {
        try {           
        
            if($request->ajax()) {

                $output = array('error' => '', 'message' => '');

                $image = $request->image;
                
                $ablum_id = $request->ablum_id;

                $album = SessionImage::where('id', $ablum_id)->first();
                
                if($album) {

                    $mystring = $album->session_mage;

                    $uplode_gallery_dir = public_path('uploads/albums/'.$album->album_session_id.'/sessions');

                    //Unlik file
                    if(!empty($album->session_mage) && File::exists($uplode_gallery_dir.'/'.$album->session_mage)){

                        unlink($uplode_gallery_dir.'/'.$album->session_mage);
                    }

                    
                    $album->delete();

                    $output['message'] = 'Gallery image removed succssfully.';

                } else {

                    $output['error'] = 'Something went wrong please try again.';  
                }

                return response()->json($output);
            }

        } catch (Exception $e) {
           
           $output['error'] = $e->getMessage();
           return response()->json($output); 
        }    
    }

    private function removeItemString($str, $item)
    {
        $parts = explode(',', $str);

        while(($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }

        return implode(',', $parts);
    }
}
