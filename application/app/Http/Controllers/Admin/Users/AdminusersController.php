<?php

namespace App\Http\Controllers\Admin\Users;

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
use App\Traits\UploadTrait;
use Helper;
use Exception;

class AdminusersController extends Controller
{
	private $image_dir = "uploads/users";

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
        return view('admin.users.users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
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
            $totalRecords = User::where('id', '!=', 1)->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = User::where('id', '!=', 1)->filter($searchValue)->count();

            ## Fetch records            
            $users = User::select('id', 'name', 'user_name', 'email', 'avatar', 'last_login_at', 'last_login_ip', 'status', 'created_at', 'updated_at')
                ->where('id', '!=', 1)
                ->filter($searchValue)
                ->offset($row)
                ->limit($rowperpage)
                ->orderebycoloumn($columnName, $columnSortOrder)
                ->get();

            $data = array();

            $i = 1;            

            $delete_confirmation_msg = "'Are you sure you want to delete?'";
            
            foreach($users as $key => $row) {

                //Status
                $status_cls = $row->status == '1' ? 'success' : 'danger';
                
                $status = $row->status == '1' ? 'Active' : 'Inactive';

                $status = '<span style="width: 110px;"><span class="m-badge  m-badge--'.$status_cls.' m-badge--wide">'.$status.'</span></span>';

                $actions = '';

                //Actions
                $actions .='<a href="'.route('admin.edit_user', [$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.delete_user', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-trash"></i>
                        </a>';

                $img_dir = public_path($this->image_dir);

                
                $user_name = $row->user_name;
                
                if(!empty($row->avatar) && File::exists($img_dir.'/'.$row->avatar)) {        
                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><img src="'.asset('application/public/uploads/users/'.$row->avatar).'" class="m--img-rounded m--marginless" alt="photo"></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->user_name).'</span><a href="javascript:;" class="m-card-user__email m-link"></a></div></div></span>';
                }else{

                    

                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><div class="m-card-user__no-photo m--bg-fill-success"><span>'.$user_name[0].'</span></div></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->user_name).'</span><a href="javascript:;" class="m-card-user__email m-link"></div></div></span>';
                }

                $data[$key]['id'] = $i++;
                $data[$key]['user_name'] = $user_detail; //Str::ucfirst($row->user_name);
                $data[$key]['email'] = $row->email;
                //$data[$key]['status'] = $status;
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
		return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdduserRequest $request)
    {
        try {
            
            $data['name'] = $request->user_name ? : NULL;
            $data['user_name'] = Str::slug($request->user_name,'');
            $data['email'] = $request->email ? : NULL;
            $data['email_verified_at'] = Carbon::now()->toDateTimeString();
            $data['password'] = Hash::make($request->password);
            $data['is_role'] = 1; 
            
            $create = User::create($data);
            
            if($create) {

                if($request->hasFile('user_image')) {

                    if ($request->file('user_image')->isValid()) {

                        $uplode_dir = public_path($this->image_dir);

                        $user_image = $request->file('user_image');

                        $avatar['avatar'] =  $this->uploadTwo($user_image, $uplode_dir);

                        User::whereId($create->id)->update($avatar);

                    }
                }
            }

            return redirect(route('admin.users'))->with('success', 'New user created successfully.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', '!=', 1)->where('id', $id)->first();
        
        if(!$user) {

            return abort(404);

            exit();
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateuserRequest $request, $id)
    {
        
        try {
            
            $user = User::where('id', '!=', 1)->where('id', $id)->first();
        
            if(!$user) {

                return abort(404);

                exit();
            }

            $data['name'] = $request->user_name ? : NULL;
            //$data['user_name'] = $request->user_name ? : NULL;
            $data['email'] = $request->email ? : NULL;
            
            if ($request->password != '') {

                $data['password'] = Hash::make($request->password);
            }

            if($request->hasFile('user_image')) {

                if ($request->file('user_image')->isValid()) {

                    $uplode_dir = public_path($this->image_dir);

                    $user_image = $request->file('user_image');

                    $data['avatar'] =  $this->uploadTwo($user_image, $uplode_dir);

                    //Unlik old file
                    if(!empty($user->avatar) && File::exists($uplode_dir.'/'.$user->avatar)){

                        unlink($uplode_dir.'/'.$user->avatar);
                    }

                }
            }

            $update = User::where('id', '!=', 1)->where('id', $id)->update($data);
            
            if($update) {

                return redirect(route('admin.users'))->with('success', 'User detail update successfully.');
                
            }

            return redirect(route('admin.users'))->with('warning', 'Something went wrong please try again.');


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

            $user = User::where('id', '!=', 1)->where('id', $id)->first();
            
            if(!$user) {

                return abort(404);

                exit();
            }

            $uplode_dir = public_path($this->image_dir);

            //Unlik profile
            if(!empty($user->avatar) && File::exists($uplode_dir.'/'.$user->avatar)) {

                unlink($uplode_dir.'/'.$user->avatar);
            }

            $user->delete();

            return redirect(route('admin.users'))->with('success', 'User deleted successfully.');

        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    }
}
