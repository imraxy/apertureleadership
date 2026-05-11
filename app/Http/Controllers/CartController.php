<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\SessionImage;
use Carbon\Carbon;
use App\Models\ChatMessage;

class CartController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($folder_id=null)
    {
        $folders = Cart::where('user_id', Auth::id())->get();

        $from_user_id = 1;
        
        $to_user_id = Auth::id();

        // $chats = DB::select("SELECT * FROM `chat_messages` WHERE (sender_user_id = '".$from_user_id."' AND reciever_user_id = '".$to_user_id."') OR (sender_user_id = '".$to_user_id."' AND reciever_user_id = '".$from_user_id."') ORDER BY created_at ASC");
		
		
		$access_code = Auth::user()->approval_code;
		$chats = Chat::where(['access_code'=>$access_code])->orderBy('created_at','ASC')->get();
      
        //$numRows = DB::select("SELECT count(*) FROM `chat_messages` WHERE `sender_user_id` = '$from_user_id' AND `reciever_user_id` = '$to_user_id' AND `is_read` = '1'");
        
        $numRows = ChatMessage::where('sender_user_id', $from_user_id)->where('reciever_user_id', $to_user_id)->where('is_read', 1)->count();

        $output = '';
        
        if($numRows > 0) {
            $output = $numRows;
        }

        return view('folder_list', compact('folders', 'chats', 'output'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$mutable = Carbon::now();



        //return response()->json($array);
        //echo $mutable;
        //exit();
        if($request->ajax()) {
            
            $output = array('error' => '', 'unauthenticated' => '', 'message' => '', 'snackbar' => '');

            if(Auth::user()) {

                // Get the currently authenticated user...
                $user = Auth::user();

                // Get the currently authenticated user's ID...
                $user_id = Auth::id();

                $image_id = $request->cart_id;

                $tbl = new SessionImage;
                
                $image_detail = $tbl->imageFindById($image_id);
                
                if($image_detail) {

                    $check_cart_detail = Cart::where(['user_id' => $user_id, 'session_image_id' => $image_id])->first();

                    if($check_cart_detail) {

                        $output['snackbar'] = 'Album session is already added to your folder.';
                    
                    }else{

                        $addfolder = new Cart;
                        $addfolder->user_id = $user_id;
                        $addfolder->session_image_id = $image_id;
                        $addfolder->save();

                        if($addfolder) {

                            $output['snackbar'] = 'Session image added to folder successfully.';

                        }else {

                            $output['error'] = 'Oops! Something went wrong.';

                        }
                    }

                    return response()->json($output);
                }

                $output['error'] = 'Oops! Something went wrong.';
                
                return response()->json($output);

                exit();

            }else {

                $output['error'] = 'Login first after add to folder.';
                $output['unauthenticated'] = 1;
                return response()->json($output); 
            }    
        }


        $array = [
            'status' => 'fail',
            'reason' => [
                'reason_code' => 'UNAUTHORIZED'
            ],
            'response_time' => Carbon::now(),
        ];

        return response()->json($array);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $cart_id)
    {
        $addfolder = Cart::where(['user_id' => $user_id, 'id' => $cart_id])->first();
        
        if(!$addfolder) {

            return abort('404');
        }

        $addfolder->delete();

        return redirect()->back()->with('success', 'Image removed successfully from folder.');
    }

    /**
     * get the album session detail
     *
     */
    private function getalbumsessionDetail($id)
    {
        $tbl = new SessionImage;
                
        return $tbl->imageFindById($id);
    }
    
    public function get_album_images(){
		
		$access_code = Auth::user()->approval_code;
		$user_id = Auth::id();
		
		// If user has no approval_code, only show their own folder
		if(empty($access_code)) {
			$all_users_data = DB::table('users')->where('id', $user_id)->get();
		} else {
			// Show all users with the same approval_code (same group)
			$all_users_data = DB::table('users')->where('approval_code', $access_code)->get();
		}
		
		$i=1;
		$html='';
		foreach($all_users_data as $data){
			 
			$folders = Cart::where('user_id', $data->id)->get();
			if($folders && $folders->count() > 0){
				$person='';
				if($data->id ==$user_id)
				{
					$person=" (ME)";
				}
						$html .="<tr>
							<td scope='row'>".$i."</td>
							<td scope='row'>".$data->name.$person."</td>
							<td style='width:65%'>
								  
										<div class='span6' style='width: 356px;'>
												<div id='owl-demo1' class='owl-carousel owl-theme themeofowl'>";
												
												foreach($folders as $imge){	
												$ss= SessionImage::where(['id'=>$imge->session_image_id])->first();
								 
												$path_image = url("application/public/uploads/albums/".$imge->session_image_id.'/'.$ss->session_image);
												  $html .="<div class='item'><img src='".$path_image."' onClick='imageclick(this)' imagelink='".$path_image."' alt='Owl Image'></div>";
												  }
												 $html .="</div>
											</div>
										</td>
									</tr>";
					
			$i++;	
			 	
		}
		}
	
		
		
		 return $html;
	}
}
