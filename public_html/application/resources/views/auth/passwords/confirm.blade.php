@extends('layouts.master')

@section('content')
<div class="contact-pageheader">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 login-box">
                <div class="col-lg-12 login-title">{{ __('Confirm Password') }}</div>
                <div class="col-lg-12">
                    <div class="login-form">
                        <p>{{ __('Please confirm your password before continuing.') }}</p>
                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="form-group">
                                <label for="password" class="form-control-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-outline-primary">{{ __('Confirm Password') }}</button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
