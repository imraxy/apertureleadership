<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Admin;

class AdminloginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {        
        return view('admin.auth.login');
    }
	
	/**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function adminUserLogin(Request $request)
    {
        $this->validateLogin($request);

        //Check user admin or not
        $admin = Admin::whereEmail($request->email)->where('is_role', 1)->first(['id', 'name', 'email', 'is_role']);
        
        if($admin) {


            if(Auth::guard('admin')->attempt($request->only('email','password'), $request->filled('remember'))) {
                //Authentication passed...
                // Get the currently authenticated user's ID...
                $admin = Auth::guard('admin')->user();
                Admin::where('id', $admin->id)->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]);

                return redirect(route('admin_dashboard'));
            }

            //Authentication failed...
            return $this->loginFailed();

        }

        //Authentication failed...
        return $this->loginFailed();
        
    }


    private function loginFailed() {
        return redirect()
            ->back()
            ->with('error','Incorrect username or password. Please try again.');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}
