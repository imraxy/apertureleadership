<?php

namespace App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Models\BlogCategory;
use Helper;
use Exception;

class CategoriesController extends Controller
{

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
        return view('admin.posts.categories.categories');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
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
            $totalRecords = BlogCategory::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = BlogCategory::filter($searchValue)->count();

            ## Fetch records            
            $users = BlogCategory::select('id', 'name', 'slug')
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
                $actions .='<a href="'.route('admin.edit_posts_category', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_posts_category', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-trash"></i>
                        </a>';

                $data[$key]['name'] = $row->name;
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
        return view('admin.posts.categories.create');
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
            'category_name' => 'required|string|max:191|unique:blog_categories,name',
        ]);

        try {            
            
            $data['name'] = $request->category_name ? : NULL;
            $data['slug'] = Str::slug($request->category_name, '-');
           
            $create = BlogCategory::create($data);
            
            if($create) {

                return redirect(route('admin.posts_categories'))->with('success', 'New journal category created successfully.');
                
            }

            return redirect(route('admin.posts_categories'))->with('warning', 'Something went wrong please try again.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
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
        $category = BlogCategory::findOrFail($id);
        
        return view('admin.posts.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        //Validation request data
        $request->validate([
            'category_name' => 'required|string|max:191|unique:blog_categories,name,'.$id,
        ]);

        try {            
            
            $data['name'] = $request->category_name ? : NULL;
            $data['slug'] = Str::slug($request->category_name, '-');
           
            $update = BlogCategory::where('id', $id)->update($data);
            
            if($update) {

                return redirect(route('admin.posts_categories'))->with('success', 'Category updated successfully.');
                
            }

            return redirect(route('admin.posts_categories'))->with('warning', 'Something went wrong please try again.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
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

            $category = BlogCategory::findOrFail($id);
            
            $category->delete();

            return redirect(route('admin.posts_categories'))->with('success', 'Album category deleted successfully.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    }
}
