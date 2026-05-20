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

    public function getadminUserChatsCodeMerged(array $access_codes)
    {
        if (empty($access_codes)) {
            return '';
        }

        $chats = Chat::whereIn('access_code', $access_codes)
            ->orderBy('created_at', 'ASC')
            ->get();

        $from_user_id = Auth::id();
        $conversation = '';

        foreach ($chats as $chat) {
            $conversation .= $this->renderChatMessageHtml($chat, $from_user_id);
        }

        return $conversation;
    }

    protected function renderChatMessageHtml($chat, $from_user_id)
    {
        $isMine = (int) $chat->sender_user_id === (int) $from_user_id;
        $bubbleClass = $isMine ? 'folder-chat-bubble--out' : 'folder-chat-bubble--in';
        $msg = htmlspecialchars($chat->msg, ENT_QUOTES, 'UTF-8');
        $time = $chat->created_at ? date('M j, g:i A', strtotime($chat->created_at)) : '';

        $html = '<div class="folder-chat-bubble ' . $bubbleClass . '">';

        if (!$isMine) {
            if ($chat->user_type == 'admin') {
                $name = 'Admin';
                $image = Protocol::home() . '/application/public/avatars/adminavatar.png';
            } else {
                $name = htmlspecialchars(Profile::getUserName($chat->sender_user_id), ENT_QUOTES, 'UTF-8');
                $image = Profile::picture($chat->sender_user_id);
            }

            $html .= '<div class="folder-chat-bubble__avatar"><img src="' . $image . '" alt=""></div>';
            $html .= '<div class="folder-chat-bubble__body">';
            $html .= '<span class="folder-chat-bubble__name">' . $name . '</span>';
            $html .= '<p class="folder-chat-bubble__text">' . $msg . '</p>';
            if ($time) {
                $html .= '<time class="folder-chat-bubble__time">' . $time . '</time>';
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="folder-chat-bubble__body">';
            $html .= '<p class="folder-chat-bubble__text">' . $msg . '</p>';
            if ($time) {
                $html .= '<time class="folder-chat-bubble__time">' . $time . '</time>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    public function insertChatMerged(array $access_codes, $chat_message)
    {
        $user_id = Auth::id();
        $created = false;

        foreach ($access_codes as $access_code) {
            if (empty($access_code)) {
                continue;
            }
            $row = Chat::create([
                'user_type' => 'user',
                'sender_user_id' => $user_id,
                'msg' => $chat_message,
                'access_code' => $access_code,
            ]);
            if ($row) {
                $created = true;
            }
        }

        if ($created) {
            $conversation = $this->getadminUserChatsCodeMerged($access_codes);
            echo json_encode(['conversation' => $conversation]);
        } else {
            echo json_encode([
                'conversation' => '',
                'error' => 'Unable to send message. Start or join a collaboration session first.',
            ]);
        }
    }

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
