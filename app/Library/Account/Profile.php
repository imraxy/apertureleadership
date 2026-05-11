<?php

namespace App\Library\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use App\User;
use App\Models\Admin;
use Protocol;

/**
* Profile class
*/
class Profile
{
	
	/**
	 * Profile Picture
	 * @param integer $user_id
	 * @return string $url
	 */
	public static function picture($user_id)
	{
		// if(Auth::guard('admin')->user()) {
			
			// $user = Admin::where('id', $user_id)->first(['avatar']);	
			
		// }else{
			// $user = User::where('id', $user_id)->first(['avatar']);	
		// }
		$user = User::where('id', $user_id)->first(['name']);	
		
		// Check user
		if ($user) {
			
			if(!empty($user->avatar) && File::exists('application/public/uploads/users/'.$user->avatar)){

				return Protocol::home().'/application/public/uploads/users/'.$user->avatar;
			
			}

		}

		// User not found
		return Protocol::home().'/application/public/avatars/noavatar.png';
	}

	public static function getUserName($user_id)
	{

		// if(Auth::guard('admin')->user()) {
			// $user = Admin::where('id', $user_id)->first(['name']);	
		// }else{
			// $user = User::where('id', $user_id)->first(['name']);	
		// }
		
		 
		$user = User::where('id', $user_id)->first(['name']);	
		 
		

		return $user->name ?? '';
	}
	
}