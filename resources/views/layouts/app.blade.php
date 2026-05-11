<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<!-- CSRF Token -->
    	<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@if(config('settings.site_title')) {{ config('settings.site_title') }} @else {{ config('app.name', 'Aperture ') }}  @endif</title>
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

		<!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="{{asset('content/assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Global Theme Styles -->

		<!--begin::Page Vendors Styles -->
		<link href="{{asset('content/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
		
		<link rel="stylesheet" type="text/css" href="{{asset('content/assets/main.css')}}">

		<!--link href="{{asset('content/assets/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" -->
		
		<link href="{{asset('content/assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
		
		<style>
			a.m-brand__logo-wrapper._barnd_title {
				font-weight: 700 !important;
				font-size: 1.54em !important;
				color: #ffffff;
				text-decoration: none;
			}
		</style>
		@stack('css')
		
		<!--end::Page Vendors Styles -->
		
		<link rel="shortcut icon" href="{{ asset('application/storage/app/public/'.config('settings.site_favicon')) }}" />
		
	</head>

	<!-- end::Head -->

	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default" data-root="{{ url('/') }}" id="root">
		
		<!-- begin::Page loader -->
		<div class="m-page-loader m-page-loader--base">
			<div class="m-blockui">
				<span>Please wait...</span>
				<span>
					<div class="m-loader m-loader--brand"></div>
				</span>
			</div>
		</div>
		
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			
			<!-- BEGIN: Header -->
			@include('dashboard.includes.header')
			<!-- END: Header -->
			
			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				
				<!-- BEGIN: Left Aside -->
				@include('dashboard.includes.side-nav')
				<!-- END: Left Aside -->
				
				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title @if(Route::currentRouteName() != 'admin_dashboard') m-subheader__title--separator @endif">@yield('subheader__title')</h3>
								@if(Route::currentRouteName() != 'admin_dashboard')
									<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
										<li class="m-nav__item m-nav__item--home">
											<a href="{{route('admin_dashboard')}}" class="m-nav__link m-nav__link--icon">
												<i class="m-nav__link-icon la la-home"></i>
											</a>
										</li>
										<li class="m-nav__separator">-</li>
										<li class="m-nav__item">
											<a href="{{route('admin_dashboard')}}" class="m-nav__link">
												<span class="m-nav__link-text">Dashboard</span>
											</a>
										</li>
										@yield('breadcrumbs')
									</ul>
								@endif
							</div>
						</div>
					</div>
					<!-- END: Subheader -->
					
					<div class="m-content">	
						
						@include('dashboard.partials.flash')
						
						@yield('content')
						
					</div>
					
				</div>
				
			</div>
			<!-- end:: Body -->
			
			<!-- begin::Footer -->
			<footer class="m-grid__item m-footer ">
				<div class="m-container m-container--fluid m-container--full-height m-page__container">
					<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
							<span class="m-footer__copyright">
							© {{date('Y')}} @if(config('settings.copyright')) {{ config('settings.copyright') }} @else Copyrights reserved @endif
							</span>
						</div>
					</div>
				</div>
			</footer>
			<!-- end::Footer -->
		
		</div>

		<!-- end:: Page -->
		
		<!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>

		<!-- end::Scroll Top -->
		
		<!--begin::Global Theme Bundle -->
		<script src="{{ asset('content/assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('content/assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors -->
		<script src="{{ asset('content/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts -->
		<script src="{{ asset('content/assets/app/js/dashboard.js') }}" type="text/javascript"></script>
		
		<script src="{{ asset('content/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
		
		<script src="{{asset('content/assets/demo/default/custom/components/base/toastr.js')}}" type="text/javascript"></script>
		
		<script src="{{ asset('content/assets/app/js/main.js') }}" type="text/javascript"></script>

		<!-- begin::Page Loader -->
		<script>
			$(window).on('load', function() {
				$('body').removeClass('m-page--loading');
			});
		</script>

		<!-- end::Page Loader -->
		<script>
			function NumericValidation(evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode > 31 && (charCode < 46 || charCode > 57) )
					return false;

				return true;
			}
		</script>
		@stack('js')
		<!--end::Page Scripts -->
		
	</body>
	<!-- end::Body -->
	
</html>
