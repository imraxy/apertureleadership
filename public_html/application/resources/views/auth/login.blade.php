@extends('layouts.master')
@push('css')
	<style>
	
	</style>	
@endpush	
@section('content')
	<div class="contact-pageheader">
        
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
					@if(session('login_error'))
					<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
						<span>{{session('login_error')}}</span>
					</div>
					@endif
					 
				</div>
                <div class="col-lg-3 col-md-2"></div>
                <div class="col-lg-6 col-md-8 login-box">
                    <div class="col-lg-12 login-key">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </div>
                    <div class="col-lg-12 login-title">
                        Login
                    </div>
    
                    <div class="col-lg-12">
                        <div class="login-form">
                            <form method="POST" action="{{ route('login') }}">
                        		@csrf
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

	                                @error('email')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                                </div>
                                <div class="form-group ">
                                    <label class="form-control-label d-flex justify-content-between">PASSWORD
                                    @if (Route::has('password.request'))
										<a class="btn btn-link" href="{{ route('password.request') }}">
											{{ __('Forgot Your Password?') }}
										</a>
									@endif
                                    </label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

	                                @error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                                </div>
								<!--div class="form-group ">
                                    <label class="form-control-label d-flex justify-content-between">Access Code
                                     </label>
                                    <input id="approval_code" type="text" class="form-control @error('approval_code') is-invalid @enderror" name="approval_code" required >

	                                @error('approval_code')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                                </div-->
                                <div class="col-lg-12 loginbttm">
                                    <div class="col-lg-6 login-btm login-text">
                                        <!-- Error Message -->
                                    </div>
                                    <div class="col-lg-6 login-btm login-button">
                                        <button type="submit" class="btn btn-outline-primary">LOGIN</button>
                                    </div>
                                    
                                </div>
                            </form>
                            
                            
						
                        </div>
                        
                    </div>
                    
                    <div class="col-lg-3 col-md-2"></div>
                    
                </div>
                
                <div class="col-lg-3 col-md-2"></div>
                
            </div>
            @if (Route::has('register'))
            <div class="row">
                <div class="col-lg-3 col-md-2"></div>
                <div class="col-lg-6 col-md-8 login-box">
                    <div class="col-lg-12">
                        <div class="login-form m-0">
                            
                            <div class="m-login__account text-center p-4">
    							<span class="m-login__account-msg">
    								Don't have an account yet ?
    							</span>&nbsp;&nbsp;
    							<a href="{{ route('register') }}" class="m-link m-link--light m-login__account-link">Sign Up</a>
    						</div>
                            
                        </div>
                        
                    </div>
                    
                    <div class="col-lg-3 col-md-2"></div>
                    
                </div>
                <div class="col-lg-3 col-md-2"></div>
            </div>
            @endif
        </div>
    </div>

@endsection
