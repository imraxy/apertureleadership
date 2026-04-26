<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Helper;
use Profile;

class ChatMessage extends Model
{
    /**
     * @var string
     */
    protected $table = 'chat_messages';

    /**
     * @var array
     */
    protected $fillable = ['sender_user_id', 'reciever_user_id', 'cart_id', 'data', 'is_read'];

    public function admininsertChat($reciever_userid, $user_id, $chat_message, $to_folder_id)
    {
    	$folder_id = base64_decode($to_folder_id);

    	$user_detail = \App\User::findOrFail($reciever_userid);
		
		if($user_detail) {
			
			$to_folder_id = trim($folder_id, "&".$user_detail->user_name);
			
			if(!empty($to_folder_id)) {

		    	$create = ChatMessage::create([
		    		'reciever_user_id' => $reciever_userid,
		    		'sender_user_id' => $user_id,
		    		'cart_id' => $to_folder_id,
		    		'data' => $chat_message,
		    		'is_read' => 1,
		    	]);	

		    	if($create) {

		    		$to_folder_id = base64_encode($to_folder_id.'&'.$user_detail->user_name);

		    		$conversation = $this->getadminUserChats($user_id, $reciever_userid, $to_folder_id);

					$data = ["conversation" => $conversation];

					echo json_encode($data);

		    	}else{

		    		return 'falid';
		    	}

		    }else{

	    		return 'falid';
	    	}

	    }else{

    		return 'falid';
    	}		
    }


    public function getadminUserChats($from_user_id, $to_user_id, $to_folder_id)
    {
		$folder_id = base64_decode($to_folder_id);
		
		$user_detail = \App\User::findOrFail($to_user_id);

		$to_folder_id = trim($folder_id, "&".$user_detail->user_name);
		
		$chats = DB::select("SELECT * FROM `chat_messages` 
			WHERE (`sender_user_id` = '".$from_user_id."' 
			AND `reciever_user_id` = '".$to_user_id."' AND `cart_id` = '".$to_folder_id."') 
			OR (`sender_user_id` = '".$to_user_id."' 
			AND `reciever_user_id` = '".$from_user_id."' AND `cart_id` = '".$to_folder_id."') 
			ORDER BY `created_at` ASC");
		
		$conversation = '';

		foreach($chats as $chat){
			
			$conversation .= '<div class="m-messenger__wrapper">';
			
			if($chat->sender_user_id != $from_user_id) {

				$conversation .= '<div class="m-messenger__message m-messenger__message--in">
								<div class="m-messenger__message-pic">
									<img src="'.Profile::picture($chat->sender_user_id).'" alt="" />
								</div>
								
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-username">
											'.Profile::getUserName($chat->sender_user_id).'
										</div>
										<div class="m-messenger__message-text">
											'.$chat->data.'
										</div>
									</div>
								</div>
							</div>';	
			}else{

				$conversation .= '<div class="m-messenger__wrapper">
								<div class="m-messenger__message m-messenger__message--out">
									<div class="m-messenger__message-body">
										<div class="m-messenger__message-arrow"></div>
										<div class="m-messenger__message-content">
											<div class="m-messenger__message-text">
												'.$chat->data.'
											</div>
										</div>
									</div>
								</div>
							</div>';
			}
			
			$conversation .= '</div>';
		}


		return $conversation;
	}

    public function insertChat($reciever_userid, $user_id, $chat_message, $to_folder_id)
    {

    	$folder_id = base64_decode($to_folder_id);

        $to_folder_id = trim($folder_id, "&".auth::user()->user_name);
		
		if(!empty($to_folder_id)) {

	    	$create = ChatMessage::create([
	    		'reciever_user_id' => $reciever_userid,
	    		'sender_user_id' => $user_id,
	    		'cart_id' => $to_folder_id,
	    		'data' => $chat_message,
	    		'is_read' => 1,
	    	]);	

	    	if($create) {

	    		$to_folder_id = base64_encode($to_folder_id.'&'.auth::user()->user_name);

	    		$conversation = $this->getUserChats($user_id, $reciever_userid, $to_folder_id);

				$data = ["conversation" => $conversation];

				echo json_encode($data);

	    	}else{

	    		return 'falid';
	    	}

	    }else{

    		return 'falid';
    	}	
    }


    public function getUserChats($from_user_id, $to_user_id, $to_folder_id)
    {
		$folder_id = base64_decode($to_folder_id);

		$to_folder_id = trim($folder_id, "&".auth::user()->user_name);
		
		$chats = DB::select("SELECT * FROM `chat_messages` 
			WHERE (`sender_user_id` = '".$from_user_id."' 
			AND `reciever_user_id` = '".$to_user_id."' AND `cart_id` = '".$to_folder_id."') 
			OR (`sender_user_id` = '".$to_user_id."' 
			AND `reciever_user_id` = '".$from_user_id."' AND `cart_id` = '".$to_folder_id."') 
			ORDER BY `created_at` ASC");
		
		$conversation = '';

		foreach($chats as $chat){
			
			$conversation .= '<div class="m-messenger__wrapper">';
			
			if($chat->sender_user_id != $from_user_id) {

				$conversation .= '<div class="m-messenger__message m-messenger__message--in">
								<div class="m-messenger__message-pic">
									<img src="'.Profile::picture($chat->sender_user_id).'" alt="" />
								</div>
								
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-username">
											'.Profile::getUserName($chat->sender_user_id).'
										</div>
										<div class="m-messenger__message-text">
											'.$chat->data.'
										</div>
									</div>
								</div>
							</div>';	
			}else{

				$conversation .= '<div class="m-messenger__wrapper">
								<div class="m-messenger__message m-messenger__message--out">
									<div class="m-messenger__message-body">
										<div class="m-messenger__message-arrow"></div>
										<div class="m-messenger__message-content">
											<div class="m-messenger__message-text">
												'.$chat->data.'
											</div>
										</div>
									</div>
								</div>
							</div>';
			}
			
			$conversation .= '</div>';
		}


		return $conversation;
	}

    public function getUserChat($from_user_id, $to_user_id)
    {
		$chats = DB::select("SELECT * FROM `chat_messages` 
			WHERE (`sender_user_id` = '".$from_user_id."' 
			AND `reciever_user_id` = '".$to_user_id."') 
			OR (`sender_user_id` = '".$to_user_id."' 
			AND `reciever_user_id` = '".$from_user_id."') 
			ORDER BY `created_at` ASC");
			
		$conversation = '';

		foreach($chats as $chat){
			
			$conversation .= '<div class="m-messenger__wrapper">';
			
			if($chat->sender_user_id != $from_user_id) {

				$conversation .= '<div class="m-messenger__message m-messenger__message--in">
								<div class="m-messenger__message-pic">
									<img src="'.Profile::picture($chat->sender_user_id).'" alt="" />
								</div>
								
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-username">
											'.Profile::getUserName($chat->sender_user_id).'
										</div>
										<div class="m-messenger__message-text">
											'.$chat->data.'
										</div>
									</div>
								</div>
							</div>';	
			}else{

				$conversation .= '<div class="m-messenger__wrapper">
								<div class="m-messenger__message m-messenger__message--out">
									<div class="m-messenger__message-body">
										<div class="m-messenger__message-arrow"></div>
										<div class="m-messenger__message-content">
											<div class="m-messenger__message-text">
												'.$chat->data.'
											</div>
										</div>
									</div>
								</div>
							</div>';
			}
			
			$conversation .= '</div>';
		}


		return $conversation;
	}


	public function getUnreadMessageCount($senderUserid, $recieverUserid)
	{
		$numRows = DB::select("SELECT count(*) FROM `chat_messages` WHERE `sender_user_id` = '$senderUserid' AND `reciever_user_id` = '$recieverUserid' AND `status` = '1'");
		$output = '';
		
		if($numRows > 0){
			$output = $numRows;
		}
		return $output;
	}
}
