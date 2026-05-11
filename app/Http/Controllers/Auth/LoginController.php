<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo()
    {

        if(!empty(session()->get('url.intended'))) {
                        
            return redirect(session()->get('url.intended'));
            
        }

        return redirect(route('account.folders'));
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        // Get URLs
        $urlPrevious = url()->previous();
        $urlBase = url()->to('/');

        // Set the previous url that we came from to redirect to after successful login but only if is internal
        if(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase)) {
            session()->put('url.intended', $urlPrevious);
        }

        return view('auth.login');
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
// 			if($user->approval_code != ""){
// 				if($user->approval_code == $request->approval_code){
// 					if(strtotime($user->approval_code_end_time) < strtotime(date('d-m-Y H:i:s'))){
// 						Auth::logout();
// 						session(['login_error' => 'Your access code is expired!!']);
// 						return redirect('/login')->with('danger','Your access code is expired!!');
// 					}
// 				}else{
// 						Auth::logout();
// 						session(['login_error' => 'Your access code is wrong!!']);
// 						return redirect('/login')->with('danger','Your access code is wrong!!');
// 				}
// 			}else{
// 					Auth::logout();
// 					session(['login_error' => 'Your access code is invalid!!']);
// 					return redirect('/login')->with('danger','Your access code is invalid!!');
// 			}
			
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
