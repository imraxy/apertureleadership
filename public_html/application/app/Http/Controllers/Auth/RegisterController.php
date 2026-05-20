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

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $expected_answer = $data['captcha_num1'] + $data['captcha_num2'];
        $expected_hash = md5($expected_answer . 'aperture_secret_key');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha' => ['required', 'string'],
        ];

        $validator = Validator::make($data, $rules);

        $validator->after(function ($validator) use ($data, $expected_hash) {
            if ($data['captcha_hash'] !== $expected_hash) {
                $validator->errors()->add('captcha', 'Security check failed. Please try again.');
            }
        });

        return $validator;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        return redirect(route('front.albums'))->with('success', 'Registration successful! Welcome to Aperture.');
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'user_name' => Str::slug($data['name'], ''),
            'email' => $data['email'],
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($data['password']),
        ]);
    }
}
