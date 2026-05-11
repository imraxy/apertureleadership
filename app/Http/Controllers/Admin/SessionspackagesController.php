<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Http\Requests\Admin\PackageaddRequest;
use App\Http\Requests\Admin\PackageupdateRequest;
use App\User;
use App\Models\SessionPackage;
use Helper;
use Exception;

class SessionspackagesController extends Controller
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
        return view('admin.packages.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function packages(Request $request)
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
            $totalRecords = SessionPackage::count();

            ## Total number of record with filtering
            $totalRecordwithFilter = SessionPackage::filter($searchValue)->count();

            ## Fetch records            
            $users = SessionPackage::select('id', 'title')
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
                $actions .='<a href="'.route('admin.edit_packages', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_packages', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
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
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageaddRequest $request)
    {
        try {
            
            $data['title'] = $request->title ? : NULL;
            $data['price'] = $request->price ? : NULL;
            $data['currency'] = $request->currency ? : NULL;
            $data['discount'] = $request->discount ? : NULL;
            $data['details'] = $request->details ? : NULL;
            
            $create = SessionPackage::create($data);

            if($create) {

                return redirect(route('admin.packages'))->with('success', 'New package created successfully.');
                    
            }

            return redirect(route('admin.packages'))->with('warning', 'Something went wrong please try again.');

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
        $package = SessionPackage::findOrFail($id);
        
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackageupdateRequest $request, $id)
    {
        try {
            
            $data['title'] = $request->title ? : NULL;
            $data['price'] = $request->price ? : NULL;
            $data['currency'] = $request->currency ? : NULL;
            $data['discount'] = $request->discount ? : NULL;
            $data['details'] = $request->details ? : NULL;
            
            $update = SessionPackage::where('id', $id)->update($data);

            if($update) {

                return redirect(route('admin.packages'))->with('success', 'Package detial updated successfully.');
                    
            }

            return redirect(route('admin.packages'))->with('warning', 'Something went wrong please try again.');

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
            
            $package = SessionPackage::findOrFail($id);

            $package->delete();

            return redirect(route('admin.packages'))->with('success', 'Package deleted successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('warning', $e->getMessage());            
        }
    }
}
