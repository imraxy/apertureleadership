<?php

namespace App\Http\Controllers\Admin\Chats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use App\Models\ChatMessage;
use App\Models\Cart;
use App\Models\Chat;
use Helper;
use Exception;
use App\Models\SessionImage;
class ChatsController extends Controller
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


    protected function adminID()
    {
        return Auth::guard('admin')->user()->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function available_group (){
		$from_user_id = self::adminID();

		$codes =User::select('approval_code')->distinct()->get(); 
		$users=[];
		$w=0;
		foreach($codes as $c){
			if($c->approval_code){
				$total_member= User::where(['approval_code'=>$c->approval_code])->count();
				$users[$w]['approval_code']=$c->approval_code;
				$users[$w]['total_member']=$total_member;
				$w++;
			}
		}
		return view('admin.chats.available_group', compact('users'));
	} 
	 
	 
    public function index(Request $request)
    {
		
		$group_no = base64_decode($request->input('group_no')); 
		
		
        $from_user_id = self::adminID();

        $users = User::with(['get_images'])->where(['approval_code'=>$group_no])->get();
		
		$chats = Chat::where(['access_code'=>$group_no])->orderBy('created_at','ASC')->get();
        
        return view('admin.chats.chats', compact('users','chats','group_no'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userFolders(Request $request,$user_id, $user_name)
    {
		 
        $folders = Cart::where('user_id', $user_id)->get();
        $group_no = $request->input('group_no'); 
        $user_detail = User::findOrFail($user_id);
        
        return view('admin.chats.folders', compact('folders', 'user_detail','group_no'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $folder_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $user_name, $folder_id)
    {
        $user_detail = User::findOrFail($user_id);

        $folder_id = base64_decode($folder_id);

        $folder_id = trim($folder_id, "&".$user_detail->user_name);

        $folder_detail = Cart::where(['id' => $folder_id, 'user_id' => $user_id])->first();

        if(!$folder_detail) {
            return abort(404);
        }

        $folders = Cart::where('user_id', $user_id)->get();
        
        $from_user_id = 1;
        
        $to_user_id = $user_id;

        $chats = DB::select("SELECT * FROM `chat_messages` WHERE (sender_user_id = '".$from_user_id."' AND reciever_user_id = '".$to_user_id."' AND `cart_id` = '".$folder_detail->id."') OR (sender_user_id = '".$to_user_id."' AND reciever_user_id = '".$from_user_id."' AND `cart_id` = '".$folder_detail->id."') ORDER BY created_at ASC");
		
		
        
        return view('admin.chats.folders', compact('folders', 'user_detail', 'chats', 'folder_detail'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = new Chat;

        if($request->action == 'insert_chat') {   
			$access_code = base64_decode($request->access_code);
            $chat->admininsertChat($access_code, $request->chat_message, self::adminID());
        }   
    }


    public function chatmessageList(Request $request)
    {  
        $chat = new Chat;
        
        if($request->action == 'update_user_chat') {
			$access_code = base64_decode($request->access_code);
            $conversation = $chat->getadminUserChats(self::adminID(),$access_code);
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data);
        }
    }

}
