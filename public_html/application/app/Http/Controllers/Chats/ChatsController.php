<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Cart;
use App\Services\CollaborationService;

class ChatsController extends Controller
{
    protected $collaboration;

    public function __construct(CollaborationService $collaboration)
    {
        $this->middleware('auth');
        $this->collaboration = $collaboration;
    }

    public function store(Request $request)
    {
        if ($request->action !== 'insert_chat') {
            return response()->json(['error' => 'Invalid action'], 400);
        }

        $user = Auth::user();
        $message = trim((string) $request->chat_message);

        if ($message === '') {
            return response()->json(['conversation' => '', 'error' => 'Message cannot be empty.'], 422);
        }

        $chatKeys = $this->collaboration->getActiveChatKeysForUser($user);

        if (empty($chatKeys)) {
            return response()->json([
                'conversation' => '',
                'error' => 'Invite a collaborator and wait for them to accept before chatting.',
            ], 422);
        }

        foreach ($chatKeys as $chatKey) {
            Chat::create([
                'user_type' => 'user',
                'sender_user_id' => $user->id,
                'msg' => $message,
                'access_code' => $chatKey,
            ]);
        }

        $chat = new Chat();
        $conversation = $chat->getadminUserChatsCodeMerged($chatKeys);

        return response()->json(['conversation' => $conversation]);
    }

    public function chatmessageList(Request $request)
    {
        if ($request->action !== 'update_user_chat') {
            return response()->json(['conversation' => '']);
        }

        $user = Auth::user();
        $chatKeys = $this->collaboration->getActiveChatKeysForUser($user);

        if (empty($chatKeys)) {
            return response()->json(['conversation' => '']);
        }

        $chat = new Chat();
        $conversation = $chat->getadminUserChatsCodeMerged($chatKeys);

        return response()->json(['conversation' => $conversation]);
    }

    public function show($folder_id)
    {
        $folder_id = base64_decode($folder_id);
        $folder_id = trim($folder_id, '&' . Auth::user()->user_name);

        $folder_detail = Cart::where(['id' => $folder_id, 'user_id' => Auth::id()])->first();

        if (!$folder_detail) {
            return abort(404);
        }

        $user = Auth::user();
        $folders = Cart::where('user_id', $user->id)->get();
        $chatKeys = $this->collaboration->getActiveChatKeysForUser($user);
        $memberIds = $this->collaboration->memberUserIds($user);
        $panel = $this->collaboration->getPanelData($user);

        $chats = !empty($chatKeys)
            ? Chat::whereIn('access_code', $chatKeys)->orderBy('created_at', 'ASC')->get()
            : collect();

        $chatEnabled = !empty($chatKeys);

        return view('folder_list', array_merge(compact(
            'folders',
            'chats',
            'folder_detail',
            'chatEnabled',
            'chatKeys',
            'memberIds'
        ), $panel));
    }
}
