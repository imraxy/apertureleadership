<?php

namespace App\Http\Controllers\Admin\Testimonials;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use App\Models\Testimonial;
use App\Traits\UploadTrait;
use Helper;
use Exception;

class TestimonialsController extends Controller
{

    private $image_dir = "uploads/testimonials";

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
        return view('admin.testimonials.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function testimonials(Request $request)
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
            $totalRecords = Testimonial::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = Testimonial::filter($searchValue)->count();

            ## Fetch records            
            $users = Testimonial::select('id', 'name', 'image')
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
                $actions .='<a href="'.route('admin.edit_testimonial', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_testimonial', $row->id).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-trash"></i>
                        </a>';

                $img_dir = public_path($this->image_dir);

                
                $user_name = $row->name;
                
                if(!empty($row->image) && File::exists($img_dir.'/'.$row->image)) {        
                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><img src="'.asset('application/public/uploads/testimonials/'.$row->image).'" class="m--img-rounded m--marginless" alt="photo"></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->name).'</span><a href="javascript:;" class="m-card-user__email m-link"></a></div></div></span>';
                }else{

                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><div class="m-card-user__no-photo m--bg-fill-success"><span>'.$user_name[0].'</span></div></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->name).'</span><a href="javascript:;" class="m-card-user__email m-link"></div></div></span>';
                }

                $data[$key]['name'] = $user_detail;
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
        return view('admin.testimonials.create');
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
            'name' => 'required|string|max:191',
            'message' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        try {

            $data['name'] = $request->name ? : NULL;
            $data['message'] = $request->message ? : NULL;

            if($request->hasFile('image')) {

                if ($request->file('image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $image = $request->file('image');

                    $data['image'] =  $this->uploadTwo($image, $uplode_dir);

                }
            }

            $create = Testimonial::create($data);

            if($create) {

                return redirect(route('admin.testimonials'))->with('success', 'New testimonial created successfully.');

                exit();
            }

            return redirect(route('admin.testimonials'))->with('warning', 'Something went wrong please try again.');

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
        $testimonial = Testimonial::findOrFail($id);
        
        return view('admin.testimonials.edit', compact('testimonial'));
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
        $testimonial = Testimonial::findOrFail($id);

        try {
            
            $required_image = $testimonial->image ? 'nullable' : 'required';

            //Validation request data
            $request->validate([
                'name' => 'required|string|max:191',
                'message' => 'required|string',
                'image' => $required_image.'|image|mimes:jpeg,jpg,png',
            ]);

            $data['name'] = $request->name ? : NULL;
            $data['message'] = $request->message ? : NULL;

            if($request->hasFile('image')) {

                if ($request->file('image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $image = $request->file('image');

                    //Unlik old file
                    if(!empty($testimonial->image) && File::exists($uplode_dir.'/'.$testimonial->image)) {

                        unlink($uplode_dir.'/'.$testimonial->image);
                    }

                    $data['image'] =  $this->uploadTwo($image, $uplode_dir);

                }
            }

            $update = Testimonial::where('id', $id)->update($data);

            if($update) {

                return redirect(route('admin.testimonials'))->with('success', 'Testimonial detail updated successfully.');

                exit();
            }

            return redirect(route('admin.testimonials'))->with('warning', 'Something went wrong please try again.');

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

            $testimonial = Testimonial::findOrFail($id);
            
            $uplode_dir = public_path($this->image_dir);

            //Unlik file
            if(!empty($testimonial->image) && File::exists($uplode_dir.'/'.$testimonial->image)) {

                unlink($uplode_dir.'/'.$testimonial->image);
            }

            $testimonial->delete();

            return redirect()->back()->with('success', 'Testimonial detail deleted successfully.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    
    }
}
