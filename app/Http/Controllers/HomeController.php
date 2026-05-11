<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ChatMessage;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $from_user_id = 1;
        
        $to_user_id = Auth::id();

        $chats = DB::select("SELECT * FROM `chat_messages` WHERE (sender_user_id = '".$from_user_id."' AND reciever_user_id = '".$to_user_id."') OR (sender_user_id = '".$to_user_id."' AND reciever_user_id = '".$from_user_id."') ORDER BY created_at ASC");
        
        //$numRows = DB::select("SELECT count(*) FROM `chat_messages` WHERE `sender_user_id` = '$from_user_id' AND `reciever_user_id` = '$to_user_id' AND `is_read` = '1'");
        
        $numRows = ChatMessage::where('sender_user_id', $from_user_id)->where('reciever_user_id', $to_user_id)->where('is_read', 1)->count();

        $output = '';
        
        if($numRows > 0) {
            $output = $numRows;
        }

        return view('home', compact('chats', 'output'));

        return view('home');
    }
}
