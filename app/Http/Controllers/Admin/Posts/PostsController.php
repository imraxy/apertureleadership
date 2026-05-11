<?php

namespace App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Http\Requests\Admin\AddpostRequest;
use App\Http\Requests\Admin\UpdatepostRequest;
use App\User;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Traits\UploadTrait;
use Helper;
use Exception;

class PostsController extends Controller
{
    private $image_dir = "uploads/journals";

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
        return view('admin.posts.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts(Request $request)
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
            $totalRecords = Post::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = Post::filter($searchValue)->count();

            ## Fetch records            
            $users = Post::select('id', 'blog_category_id', 'title')
                ->filter($searchValue)
                ->offset($row)
                ->limit($rowperpage)
                ->orderebycoloumn($columnName, $columnSortOrder)
                ->get();

            $data = array();

            $i = 1;            

            $delete_confirmation_msg = "'Are you sure you want to delete?'";
            
            foreach($users as $key => $row) {

                $actions = '';

                //Actions
                $actions .='<a href="'.route('admin.edit_posts', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_posts', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                          <i class="la la-trash"></i>
                        </a>';

                $data[$key]['title'] = $row->title;
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
        $categories = BlogCategory::where('status', 1)->get();
        
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddpostRequest $request)
    {
        try {
            
            $data['blog_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['permalink'] = $request->permalink ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['meta_keywords'] = $request->meta_keywords ? : NULL;
            $data['meta_description'] = $request->meta_description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');

            if($request->hasFile('featured_image')) {

                if ($request->file('featured_image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $featured_image = $request->file('featured_image');

                    $data['featured_image'] =  $this->uploadwithWartermark($featured_image, $uplode_dir);

                }
            }

            $create = Post::create($data);

            if($create) {

                return redirect(route('admin.posts'))->with('success', 'New post created successfully.');
                    
            }

            return redirect(route('admin.posts'))->with('warning', 'Something went wrong please try again.');

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
        $post = Post::findOrFail($id);
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        try {
            
            $data['blog_category_id'] = $request->category ? : NULL;
            $data['title'] = $request->title ? : NULL;
            $data['permalink'] = $request->permalink ? : NULL;
            $data['description'] = $request->description ? : NULL;
            $data['meta_keywords'] = $request->meta_keywords ? : NULL;
            $data['meta_description'] = $request->meta_description ? : NULL;
            $data['slug'] = Str::slug($request->title, '-');

            if($request->hasFile('featured_image')) {

                if ($request->file('featured_image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $featured_image = $request->file('featured_image');

                    //Unlik old file
                    if(!empty($post->featured_image) && File::exists($uplode_dir.'/'.$post->featured_image)) {

                        unlink($uplode_dir.'/'.$post->featured_image);
                    }

                    $data['featured_image'] =  $this->uploadwithWartermark($featured_image, $uplode_dir);

                }
            }

            $update = Post::where('id', $id)->update($data);

            if($update) {

                return redirect(route('admin.posts'))->with('success', 'Post updated successfully.');
                    
            }

            return redirect(route('admin.posts'))->with('warning', 'Something went wrong please try again.');

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
    public function destroy($id)
    {
        try {
            
            $post = Post::findOrFail($id);

            $uplode_dir = public_path($this->image_dir);

            //Unlik old file
            if(!empty($post->featured_image) && File::exists($uplode_dir.'/'.$post->featured_image)) {

                unlink($uplode_dir.'/'.$post->featured_image);
            }

            $post->delete();

            return redirect(route('admin.posts'))->with('success', 'Post deleted successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('warning', $e->getMessage());            
        }
    }
}
