@extends('layouts.master')
@push('css')
	<style>

	</style>	
@endpush
@section('content')
<!-- BannerMain -->
    <section class="main-banner ex-register-banner">
        <div class="banner-inn">
			<img src="{{asset('content/images/testimonials-bg.jpg')}}" class="img-responsive" title="contact" alt="contact image">
        </div>        
        <div class="banner-text text-center">
            <h2>Registration Form</h2>
        </div>        
    </section>

    <section class="ex-aboutsection ex-register">
        
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-2"></div>
                <div class="col-lg-6 col-md-8 login-box">
                    <div class="col-lg-12 login-title">
                        <h2>Registration Form</h2>
                        <p>Enter your information to register</p>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="col-lg-12">
                        <div class="login-form">
                            <form method="POST" action="{{ route('register') }}">
								@csrf
                                <div class="form-group">
									<label for="firstname">{{ __('Name') }}</label>
									<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
									@error('name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
                              
								<div class="form-group">
									<label for="Email1">{{ __('E-Mail Address') }}</label>
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							  
                              <!--div class="form-group">
                                <label for="phoneno">Phone Number</label>
                                <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" maxlength="10" value="{{ old('phone_no') }}" onkeypress="return NumericValidation(event);">
                                @error('phone_no')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
                              </div-->
                              
                              <div class="form-group">
                                <label for="Password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
							  
							  <div class="form-group">
                                <label for="Password">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                              </div>

                              <div class="form-group">
                                <label>How will you use Aperture?</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="session_solo" name="session_type" value="solo" class="custom-control-input" {{ old('session_type', 'solo') === 'solo' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="session_solo">Solo — browse the photo libraries</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="session_group_create" name="session_type" value="group_create" class="custom-control-input" {{ old('session_type') === 'group_create' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="session_group_create">Group — start a new session (you receive an access code)</label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="session_group_join" name="session_type" value="group_join" class="custom-control-input" {{ old('session_type') === 'group_join' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="session_group_join">Group — join with an access code</label>
                                </div>
                                @error('session_type')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                              </div>

                              <div class="form-group" id="group-code-wrap" style="display: {{ old('session_type') === 'group_join' ? 'block' : 'none' }};">
                                <label for="group_code">Group access code</label>
                                <input id="group_code" type="text" class="form-control @error('group_code') is-invalid @enderror" name="group_code" value="{{ old('group_code') }}" maxlength="6" placeholder="6-character code" autocomplete="off" style="text-transform: uppercase;">
                                @error('group_code')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                              </div>

                              @php
                                  $num1 = rand(1, 10);
                                  $num2 = rand(1, 10);
                                  $captcha_hash = md5(($num1 + $num2) . 'aperture_secret_key');
                              @endphp
                              <div class="form-group">
                                <label for="captcha">Security check: What is {{ $num1 }} + {{ $num2 }}?</label>
                                <input id="captcha" type="text" class="form-control @error('captcha') is-invalid @enderror" name="captcha" required>
                                <input type="hidden" name="captcha_hash" value="{{ $captcha_hash }}">
                                <input type="hidden" name="captcha_num1" value="{{ $num1 }}">
                                <input type="hidden" name="captcha_num2" value="{{ $num2 }}">
                                @error('captcha')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                              </div>

                              <button type="submit" class="btn btn-outline-primary">Sign up</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center mt-3 mb-4">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">Sign In</a>
                    </div>
                    <div class="col-lg-3 col-md-2"></div>
                </div>
            </div>
                
            </div>
        </div>
    
    </section>
@endsection

@push('js')
	<script type="text/javascript">
		(function () {
			var radios = document.querySelectorAll('input[name="session_type"]');
			var codeWrap = document.getElementById('group-code-wrap');
			function toggleGroupCode() {
				var join = document.getElementById('session_group_join');
				if (codeWrap && join) {
					codeWrap.style.display = join.checked ? 'block' : 'none';
				}
			}
			radios.forEach(function (r) { r.addEventListener('change', toggleGroupCode); });
			toggleGroupCode();
		})();
	</script>
@endpush
	
