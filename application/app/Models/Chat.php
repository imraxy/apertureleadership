<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Helper;
use Profile;
use Protocol;

class Chat extends Model
{
    /**
     * @var string
     */
    protected $table = 'chats';

    /**
     * @var array
     */
    protected $fillable = ['user_type', 'sender_user_id', 'msg', 'access_code'];

    public function getadminUserChatsCode($access_code){
		
		
		 
		$from_user_id = Auth::id();
			
		$chats = Chat::where(['access_code'=>$access_code])->orderBy('created_at','ASC')->get();
		 
		$conversation = '';
			 
		foreach($chats as $chat){
			
			$conversation .= '<div class="m-messenger__wrapper">';
			
			if($chat->sender_user_id != $from_user_id) {
				if($chat->user_type=="admin"){
					$name="Admin";
					$image=  Protocol::home().'/application/public/avatars/adminavatar.png';
				}else{ 
					$name= Profile::getUserName($chat->sender_user_id);
					$image= Profile::picture($chat->sender_user_id);
				}

				$conversation .= '<div class="m-messenger__message m-messenger__message--in">
								<div class="m-messenger__message-pic">
									<img src="'.$image.'" alt="" />
								</div>
								
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-username">
											'.$name.'
										</div>
										<div class="m-messenger__message-text">
											'.$chat->msg.'
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
												'.$chat->msg.'
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
	
	public function insertChat($access_code,$chat_message)
    {
 
			$user_id = Auth::id();
		 
	    	$create = Chat::create([
	    		'user_type' => 'user',
	    		'sender_user_id' => $user_id,
	    		'msg' => $chat_message,
	    		'access_code' => $access_code,
	    	]);	

	    	if($create) {
 
	    		$conversation = $this->getadminUserChatsCode($access_code);

				$data = ["conversation" => $conversation];

				echo json_encode($data);

	    	}else{

	    		return 'falid';
	    	}

	     
    }
	
	 public function getadminUserChats($from_user_id,$access_code)
    {
		 
		 
		$chats = Chat::where(['access_code'=>$access_code])->orderBy('created_at','ASC')->get();
		
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
											'.$chat->msg.'
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
												'.$chat->msg.'
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
	
	public function admininsertChat($access_code,$chat_message,$from_user_id)
    {
    	  

		    	$create = Chat::create([
	    		'user_type' => 'admin',
	    		'sender_user_id' => $from_user_id,
	    		'msg' => $chat_message,
	    		'access_code' => $access_code,
				]);		

		    	if($create) {
	
		    		$conversation = $this->getadminUserChats($from_user_id,$access_code);

					$data = ["conversation" => $conversation];

					echo json_encode($data);

		    	}else{

		    		return 'falid';
		    	}

		    

	    
    }

   
}
