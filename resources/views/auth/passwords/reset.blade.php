@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 login-box">
            <!--<div class="col-lg-12 login-title">{{ __('Reset Password') }}</div>-->

            <div class="col-lg-12 login-title"> Reset Password </div>
            
            <div class="col-lg-12">
                <div class="login-form">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-control-label">{{ __('Password') }}</label>

                            
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="form-control-label">{{ __('Confirm Password') }}</label>

                            
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            
                        </div>

                        <div class="form-group mb-0">
                            <div class="col-md-6 offset-md-6 login-btm login-button">
                                <button type="submit" class="btn btn-outline-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
