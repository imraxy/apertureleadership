<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Requests\Admin\AdduserRequest;
use App\Http\Requests\Admin\UpdateuserRequest;
use App\User;
use App\Models\Slider;
use App\Traits\UploadTrait;
use Helper;
use Exception;

class HomeslidersController extends Controller
{
    private $image_dir = "uploads/sliders";

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
        return view('admin.sliders.sliders');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sliders(Request $request)
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

            ## Search 
            
            ## Total number of records without filtering
            $totalRecords = Slider::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = Slider::filter($searchValue)->count();

            ## Fetch records            
            $users = Slider::select('id', 'title', 'image')
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
                $actions .='<a href="'.route('admin.edit_slider', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_slider', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-trash"></i>
                        </a>';

                $data[$key]['title'] = $row->title;
                $data[$key]['image'] = '<img src="'.asset('application/public/uploads/sliders/'.$row->image).'" class="" alt="'.$row->title.'" style="max-height: 70px;">';
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
        return view('admin.sliders.create');
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
            'title' => 'nullable|string|max:191',
            'slider_image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        try {            
            
            $data['title'] = $request->title ? : NULL;
           
            $create = Slider::create($data);
            
            if($create) {

                if($request->hasFile('slider_image')) {

                    if ($request->file('slider_image')->isValid()) {

                        $uplode_dir = public_path($this->image_dir);

                        $slider_image = $request->file('slider_image');

                       // $image['image'] =  $this->uploadsliderhWartermark($slider_image, $uplode_dir);
                        $image['image'] =  $this->uplaodsliderwithoutwatermark($slider_image, $uplode_dir);
                        Slider::whereId($create->id)->update($image);

                    }
                }
            }

            return redirect(route('admin.sliders'))->with('success', 'New slider detail created successfully.');

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
        $slider = Slider::findOrFail($id);
        
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        
        return view('admin.sliders.edit', compact('slider'));
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

        $slider = Slider::findOrFail($id);

        $slider_image_required = $slider->image ? 'nullable' : 'required';

        //Validation request data
        $request->validate([
            'title' => 'nullable|string|max:191',
            'slider_image' => $slider_image_required.'|image|mimes:jpeg,jpg,png',
        ]);

        try {            
            
            $data['title'] = $request->title ? : NULL;
            
            if($request->hasFile('slider_image')) {

                if ($request->file('slider_image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $slider_image = $request->file('slider_image');

                    //$data['image'] =  $this->uploadwithWartermark($slider_image, $uplode_dir);
                    //$data['image'] =  $this->uploadsliderhWartermark($slider_image, $uplode_dir);
                      $data['image'] =  $this->uplaodsliderwithoutwatermark($slider_image, $uplode_dir);
                    //Unlik old file
                    if(!empty($slider->image) && File::exists($uplode_dir.'/'.$slider->image)){

                        unlink($uplode_dir.'/'.$slider->image);
                    }

                }
            }

            $update = Slider::where('id', $id)->update($data);
            
            if($update) {

                return redirect(route('admin.sliders'))->with('success', 'Slider detail update successfully.');
                
            }

            return redirect(route('admin.sliders'))->with('warning', 'Something went wrong please try again.');

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

            $slider = Slider::findOrFail($id);
            
            $uplode_dir = public_path($this->image_dir);

            //Unlik profile
            if(!empty($slider->image) && File::exists($uplode_dir.'/'.$slider->image)) {

                unlink($uplode_dir.'/'.$slider->image);
            }

            $slider->delete();

            return redirect(route('admin.sliders'))->with('success', 'Slider detail deleted successfully.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    }
    
}
