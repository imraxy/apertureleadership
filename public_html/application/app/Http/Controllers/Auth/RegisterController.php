<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\GroupSessionService;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Validate CAPTCHA
        $expected_answer = $data['captcha_num1'] + $data['captcha_num2'];
        $expected_hash = md5($expected_answer . 'aperture_secret_key');
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha' => ['required', 'string'],
            'session_type' => ['required', 'in:solo,group_create,group_join'],
            'group_code' => ['nullable', 'string', 'max:6'],
        ];
        
        $validator = Validator::make($data, $rules);
        
        // Add custom CAPTCHA validation
        $validator->after(function ($validator) use ($data, $expected_hash) {
            if ($data['captcha_hash'] !== $expected_hash) {
                $validator->errors()->add('captcha', 'Security check failed. Please try again.');
            }
            if (($data['session_type'] ?? '') === 'group_join' && empty(trim($data['group_code'] ?? ''))) {
                $validator->errors()->add('group_code', 'Enter the group access code you received.');
            }
        });
        
        return $validator;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        $sessionType = $request->input('session_type', 'solo');
        $service = app(GroupSessionService::class);

        try {
            if ($sessionType === 'group_create') {
                $code = $service->assignToUser($user);

                return redirect(route('account.folders'))->with(
                    'success',
                    'Registration successful! Your group access code is ' . $code . ' — share it with your participants.'
                );
            }

            if ($sessionType === 'group_join') {
                $service->assignToUser($user, $request->input('group_code'));

                return redirect(route('account.folders'))->with(
                    'success',
                    'Registration successful! You have joined the group session.'
                );
            }
        } catch (ValidationException $e) {
            Auth::logout();
            throw $e;
        }

        return redirect(route('front.albums'))->with('success', 'Registration successful! Welcome to Aperture.');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'user_name' => Str::slug($data['name'],''),
            'email' => $data['email'],
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($data['password']),
           // 'phone_no' => $data['phone_no'],
        ]);
    }
}
