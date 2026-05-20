<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\SessionImage;
use App\Models\ChatMessage;
use App\Services\CollaborationService;

class CartController extends Controller
{
    protected $collaboration;

    public function __construct(CollaborationService $collaboration)
    {
        $this->collaboration = $collaboration;
    }

    public function index($folder_id = null)
    {
        $user = Auth::user();
        $folders = Cart::where('user_id', $user->id)->get();

        $chatKeys = $this->collaboration->getActiveChatKeysForUser($user);
        $memberIds = $this->collaboration->memberUserIds($user);
        $panel = $this->collaboration->getPanelData($user);

        $chats = !empty($chatKeys)
            ? Chat::whereIn('access_code', $chatKeys)->orderBy('created_at', 'ASC')->get()
            : collect();

        $chatEnabled = !empty($chatKeys);

        $from_user_id = 1;
        $to_user_id = $user->id;
        $numRows = ChatMessage::where('sender_user_id', $from_user_id)
            ->where('reciever_user_id', $to_user_id)
            ->where('is_read', 1)
            ->count();

        $output = $numRows > 0 ? $numRows : '';

        return view('folder_list', array_merge(compact(
            'folders',
            'chats',
            'output',
            'chatEnabled',
            'chatKeys',
            'memberIds'
        ), $panel));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $output = array('error' => '', 'unauthenticated' => '', 'message' => '', 'snackbar' => '');

            if (Auth::user()) {
                $user_id = Auth::id();
                $image_id = $request->cart_id;
                $tbl = new SessionImage;
                $image_detail = $tbl->imageFindById($image_id);

                if ($image_detail) {
                    $check_cart_detail = Cart::where(['user_id' => $user_id, 'session_image_id' => $image_id])->first();

                    if ($check_cart_detail) {
                        $output['snackbar'] = 'Album session is already added to your folder.';
                    } else {
                        $addfolder = new Cart;
                        $addfolder->user_id = $user_id;
                        $addfolder->session_image_id = $image_id;
                        $addfolder->save();

                        if ($addfolder) {
                            $output['snackbar'] = 'Session image added to folder successfully.';
                        } else {
                            $output['error'] = 'Oops! Something went wrong.';
                        }
                    }

                    return response()->json($output);
                }

                $output['error'] = 'Oops! Something went wrong.';
                return response()->json($output);
            }

            $output['error'] = 'Login first after add to folder.';
            $output['unauthenticated'] = 1;
            return response()->json($output);
        }

        return response()->json([
            'status' => 'fail',
            'reason' => ['reason_code' => 'UNAUTHORIZED'],
            'response_time' => now(),
        ]);
    }

    public function destroy($user_id, $cart_id)
    {
        $addfolder = Cart::where(['user_id' => $user_id, 'id' => $cart_id])->first();

        if (!$addfolder) {
            return abort('404');
        }

        $addfolder->delete();

        return redirect()->back()->with('success', 'Image removed successfully from folder.');
    }

    public function get_album_images()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $memberIds = $this->collaboration->memberUserIds($user);

        $all_users_data = User::whereIn('id', $memberIds)->orderBy('name')->get();

        $i = 1;
        $html = '';

        foreach ($all_users_data as $data) {
            $folders = Cart::where('user_id', $data->id)->get();

            if ($folders && $folders->count() > 0) {
                $person = '';
                if ($data->id == $user_id) {
                    $person = ' (ME)';
                }

                $html .= "<tr>
                    <td scope='row'>" . $i . "</td>
                    <td scope='row'>" . e($data->name) . $person . "</td>
                    <td style='width:65%'>
                        <div class='span6' style='width: 356px;'>
                            <div id='owl-demo1' class='owl-carousel owl-theme themeofowl'>";

                foreach ($folders as $imge) {
                    $ss = SessionImage::where(['id' => $imge->session_image_id])->first();
                    if ($ss) {
                        $path_image = url('application/public/uploads/albums/' . $imge->session_image_id . '/' . $ss->session_image);
                        $html .= "<div class='item'><img src='" . $path_image . "' onClick='imageclick(this)' imagelink='" . $path_image . "' alt='Owl Image'></div>";
                    }
                }

                $html .= "</div>
                        </div>
                    </td>
                </tr>";

                $i++;
            }
        }

        return $html;
    }
}
