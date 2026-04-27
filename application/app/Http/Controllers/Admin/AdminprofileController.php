<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\Rules\MatchOldPassword;
use App\User;
use App\Models\Admin;
use Carbon\Carbon;
use App\Traits\UploadTrait;
use Helper;

class AdminprofileController extends Controller
{
    private $image_dir = "uploads/users";

    use UploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = Admin::find(self::adminID());
        
        return view('admin.admin.profile', compact('profile'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $profile = Admin::find(self::adminID());
        
        return view('admin.admin.profile', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request)
    {
        $user_id = self::adminID();

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191|unique:admins,user_name,'.$user_id,
            'email' => 'required|string|email|max:191|unique:admins,email,'.$user_id,
            'user_image' => 'nullable|image|mimes:jpeg,jpg,png',
            'password' => 'nullable|string|min:8|max:16|confirmed',
        ]);

        $data['name'] = $request->name ? : NULL;
        $data['user_name'] = $request->name ? : NULL;
        $data['email'] = $request->email ? : NULL;

        if ($request->password != ''){

            $data['password'] = Hash::make($request->password);
        }

        $admin_detail = Auth::guard('admin')->user();

        if($request->hasFile('profile_image')) {

            if ($request->file('profile_image')->isValid()) {

                $uplode_dir = public_path($this->image_dir);

                $user_image = $request->file('profile_image');

                $data['avatar'] =  $this->uploadTwo($user_image, $uplode_dir);

                //Unlik old file
                if(!empty($admin_detail->avatar) && File::exists($uplode_dir.'/'.$admin_detail->avatar)){

                    unlink($uplode_dir.'/'.$admin_detail->avatar);
                }

            }
        }

        Admin::where('id', self::adminID())->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }



    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
           
        return redirect(route('admin_login'));
    }

    protected function adminID()
    {
        return Auth::guard('admin')->user()->id;
    }

}
