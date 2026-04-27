<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use App\Models\ChatMessage;
use App\Models\Chat;
use App\Models\Cart;
use App\Models\SessionImage;
use Helper;
use Exception;

class ChatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
			$access_code = Auth::user()->approval_code;		
            $chat->insertChat($access_code, $request->chat_message);
        }   
    }

    public function chatmessageList(Request $request)
    {   
        $chat = new Chat;
        
        if($request->action == 'update_user_chat') {
			$access_code = Auth::user()->approval_code;
            $conversation = $chat->getadminUserChatsCode($access_code);

            $data = array(
                "conversation" => $conversation         
            );
            
            echo json_encode($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $folder_id
     * @return \Illuminate\Http\Response
     */
    public function show($folder_id)
    {
        $folder_id = base64_decode($folder_id);

        $folder_id = trim($folder_id, "&".auth::user()->user_name);

        $folder_detail = Cart::where(['id' => $folder_id, 'user_id' => Auth::id()])->first();

        if(!$folder_detail) {
            return abort(404);
        }

        $folders = Cart::where('user_id', Auth::id())->get();

        $from_user_id = 1;
        
        $to_user_id = Auth::id();

        $chats = DB::select("SELECT * FROM `chat_messages` WHERE (sender_user_id = '".$from_user_id."' AND reciever_user_id = '".$to_user_id."' AND `cart_id` = '".$folder_detail->id."') OR (sender_user_id = '".$to_user_id."' AND reciever_user_id = '".$from_user_id."' AND `cart_id` = '".$folder_detail->id."') ORDER BY created_at ASC");
        
        return view('folder_list', compact('folders', 'chats', 'folder_detail'));
    }

}
