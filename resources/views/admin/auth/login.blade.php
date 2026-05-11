<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <!-- CSRF Token -->
	    <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'Aperture') }}  - Login</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

		<!--end::Web font -->

		<!--begin::Global Theme Styles -->
		<link href="{{asset('content/assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

		<link href="{{asset('content/assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->
		<link rel="shortcut icon" href="{{ asset('application/storage/app/public/'.config('settings.site_favicon')) }}" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url({{asset('content/assets/app/media/img//bg/bg-3.jpg')}});">
				<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo">
							@if (config('settings.site_logo') != null)
							<a href="javascript:;">
								<img src="{{ asset('application/storage/app/public/'.config('settings.site_logo')) }}">
							</a>
							@else
							<h2>{{ config('app.name', 'Aperture') }}</h2>
							@endif
						</div>
						<div class="m-login__signin">
							<div class="m-login__head">
								<h3 class="m-login__title">Sign In To Admin</h3>
							</div>
							<form class="m-login__form m-form" method="post" action="{{ route('admin_login') }}">
								@csrf
								@if(session('error'))
								<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
									<span>{{session('error')}}</span>
								</div>
								@endif
								<div class="form-group m-form__group">
									<input class="form-control m-input @error('email') is-invalid @enderror" type="text" placeholder="Email" name="email" value="{{ old('email') }}" autofocus autocomplete="email">
									@error('email')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								
								<div class="form-group m-form__group">
									<input class="form-control m-input m-login__form-input--last @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" autocomplete="current-password">
									@error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>

								<div class="row m-login__form-sub">
									<div class="col m--align-left m-login__form-left">
										<label class="m-checkbox  m-checkbox--focus">
											<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
											<span></span>
										</label>
									</div>
									{{--
									<div class="col m--align-right m-login__form-right">
										<a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
									</div>
									--}}
								</div>

								<div class="m-login__form-action">
									<button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Sign In</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!--begin::Global Theme Bundle -->
		<script src="{{asset('content/assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('content/assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts -->
		<script src="{{asset('content/assets/snippets/custom/pages/user/login.js')}}" type="text/javascript"></script>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>