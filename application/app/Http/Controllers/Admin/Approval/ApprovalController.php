<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ApprovalController extends Controller
{
	private $image_dir = "uploads/users"; 
	 
    public function __construct()
    {
        $this->middleware('admin');
    }
 
    public function index()
    {
       return view('admin.approval.approval_index');
    }
	
	public function datatablesapproval(Request $request)
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
            $totalRecords = User::where('approval_code', '!=', '')->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = User::where('approval_code', '!=', '')->filter($searchValue)->count();

            ## Fetch records            
            $users = User::select('id', 'name', 'user_name', 'email', 'avatar', 'last_login_at', 'last_login_ip', 'status', 'created_at', 'updated_at','approval_code','approval_code_create_time','approval_code_end_time','approval_code_time')
                ->where('approval_code', '!=', '')
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
               
				if(strtotime($row->approval_code_end_time) > strtotime(date('d-m-Y H:i:s'))){
					$status = 'Active';
					$status_cls = 'success';
				}else{
					$status = 'Expire';
					$status_cls = 'danger';
				}
				

                $status = '<span style="width: 110px;"><span class="m-badge  m-badge--'.$status_cls.' m-badge--wide">'.$status.'</span></span>';

                $actions = '';

                //Actions
                $actions .='<a href="'.route('admin.approval.assign_code', ['user'=>$row->id]).'" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-edit"></i>
                        </a>';

                $actions .='<a href="'.route('admin.approval.delete_approval_code', [$row->id]).'" onclick="return confirm('.$delete_confirmation_msg.');" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                          <i class="la la-trash"></i>
                        </a>';

                $img_dir = public_path($this->image_dir);

                
                $user_name = $row->user_name;
                
                if(!empty($row->avatar) && File::exists($img_dir.'/'.$row->avatar)) {        
                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><img src="'.asset('application/public/uploads/users/'.$row->avatar).'" class="m--img-rounded m--marginless" alt="photo"></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->name).'</span><a href="javascript:;" class="m-card-user__email m-link"></a></div></div></span>';
                }else{

                    

                    $user_detail = '<span style="width: 200px;"><div class="m-card-user m-card-user--sm"><div class="m-card-user__pic"><div class="m-card-user__no-photo m--bg-fill-success"><span>'.$user_name[0].'</span></div></div><div class="m-card-user__details"><span class="m-card-user__name">'.Str::ucfirst($row->name).'</span><a href="javascript:;" class="m-card-user__email m-link"></div></div></span>';
                }

				$data[$key]['id'] = $i++;
                $data[$key]['name'] = $user_detail; //Str::ucfirst($row->user_name);
                $data[$key]['email'] = $row->email;
				$data[$key]['code'] = $row->approval_code;
				$data[$key]['start_time'] = date('d-m-Y H:i:s',strtotime($row->approval_code_create_time));
				$data[$key]['expiry_time'] = date('d-m-Y H:i:s',strtotime($row->approval_code_end_time));
				$data[$key]['time'] = $row->approval_code_time." minutes";
				$data[$key]['status'] = $status;
               
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
	
	public function select_user(Request $request){
		
		$users=User::get();
		
		return view('admin.approval.select_user',compact('users'));
	}
	
	public function assign_code(Request $request){
		
		$user_id=$request->query('user');
		$data= User::where(['id'=>$user_id])->first();
		 
		if(!$data){
			return redirect()->route('admin.approval.select_user');
		}
		if($data->approval_code==""){
			$status = "Not assign any code";
			$status_cls = "success";
		}elseif(strtotime($data->approval_code_end_time) > strtotime(date('d-m-Y H:i:s'))){
			$status = "Approval code active";
			$status_cls = "success";
		}else{
			$status = "Approval code expire";
			$status_cls = "danger";
		}
		if($request->isMethod('post')){
			 
			$id=$request->user_id;
			 
			$rules =['approval_code' => 'required|max:6|unique:users,approval_code'];
            $rules =['approval_code_time' => 'required'];
			$rules =['approval_code_time_end' => 'required'];
			$validator = Validator::make($request->all(), $rules);
			$validator->after(function ($validator)use($request) {
				if(strtotime($request->approval_code_time) >  strtotime($request->approval_code_time_end)){
					  $validator->errors()->add('approval_code_time_end', 'Assign Expiry Date-Time should be greater than Assign Start Date-Time');
				}
			});
            if ($validator->fails()){
				 return back()->withErrors($validator);
            }else{
				 
				
			
				
				
				$startTime = date('Y-m-d H:i:s',strtotime($request->approval_code_time));
				$endtime= date('Y-m-d H:i:s', strtotime($request->approval_code_time_end));
			 	
				$start = strtotime($startTime);
				$end = strtotime($endtime);
				$mins = ($end - $start) / 60;
				 
				User::where(['id'=>$id])->update(['approval_code_create_time'=>$startTime,
												'approval_code_end_time'=>$endtime,
												 'approval_code'=>$request->approval_code,
												 'approval_code_time'=>$mins
											]);
											
			  return redirect()->route('admin.approval.assign_code',['user'=>$id])->with('success', 'Detail update successfully.');

               
				
			}
		
		}
		return view('admin.approval.assign_code',compact('data','status_cls','status')); 
	}
	
	public function assign_code_multipel(Request $request){
		$user_id= $request->query('user');
		$user_list= explode(",",$user_id);
		
		$userfind=User::select(['id','name'])->whereIn('id', $user_list)->get();
		// $six_digit_random_number = mt_rand(100000, 999999);
		// $extcheck= User::where('approval_code', $six_digit_random_number)->count();
		// if($extcheck){
			// return redirect()->route('admin.approval.assign_code_multipel',['user'=>$user_id]);
		// }
		if(empty($userfind)){
			return redirect()->route('admin.approval.select_user');
		}
		if($request->isMethod('post')){
		 
			$id=$request->user_id;
			$count= User::where(['approval_code'=>$request->approval_code])->count();
			if($count){
				return redirect()->route('admin.approval.assign_code_multipel',['user'=>$user_id])->with('error', 'Approval code already in use!!');
			}
			$rules =['approval_code' => 'required'];
            $rules =['approval_code_time' => 'required'];
			$rules =['approval_code_time_end' => 'required'];
			$validator = Validator::make($request->all(), $rules);
			$validator->after(function ($validator)use($request) {
				if(strtotime($request->approval_code_time) >  strtotime($request->approval_code_time_end)){
					  $validator->errors()->add('approval_code_time_end', 'Assign Expiry Date-Time should be greater than Assign Start Date-Time');
				}
			});
			 
            if ($validator->fails()){
				 return back()->withErrors($validator);
            }else{
				 
				
				$startTime = date('Y-m-d H:i:s',strtotime($request->approval_code_time));
				$endtime= date('Y-m-d H:i:s', strtotime($request->approval_code_time_end));
			 	
				$start = strtotime($startTime);
				$end = strtotime($endtime);
				$mins = ($end - $start) / 60;
				
				
				$user_ids_arry= explode(",",$request->user_ids);
				foreach($user_ids_arry as $uids){
						User::where(['id'=>$uids])->update([
							'approval_code_create_time'=>$startTime,
							'approval_code_end_time'=>$endtime,
							 'approval_code'=>$request->approval_code,
							 'approval_code_time'=>$mins
						]);
											
				}
			  return redirect()->route('admin.approval.assign_code',['user'=>$id])->with('success', 'Detail update successfully.');

               
				
			}
		
		}
		
		
		return view('admin.approval.assign_code_multipel',compact('user_id','userfind')); 
		
	}
	
	public function delete_approval_code($id){
		User::where(['id'=>$id])->update(['approval_code_create_time'=>Null,
												'approval_code_end_time'=>Null,
												 'approval_code'=>Null,
												 'approval_code_time'=>Null
											]);
		return redirect()->route('admin.approval')->with('success', 'Approval details delete successfully.');									
											
	}
	
	 

}
