<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(config('settings.site_title')) {{ config('settings.site_title') }} @else {{ config('app.name', 'Aperture ') }}  @endif</title>
	
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	
	<link href="{{ asset('content/css/owl.carousel.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('content/css/bootstrap.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('content/css/style.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('content/css/responsive.css') }}" type="text/css" rel="stylesheet">
    
    <!-- Cinematic Dark Theme Override -->
    <link href="{{ asset('content/css/cinematic-dark-theme.css') }}" type="text/css" rel="stylesheet">
    
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('content/js/owl.carousel.js') }}"></script>
    <script src="{{ asset('content/js/bootstrap.js') }}"></script>
	
	<!-- swiper style -->
	<link rel="stylesheet" href="{{ asset('content/css/swiper.min.css') }}">
	<link rel="stylesheet" href="{{ asset('content/css/main.css') }}">
	
    <link rel="icon" href="{{ asset('application/storage/app/public/'.config('settings.site_favicon')) }}" type="image/x-icon">
	@stack('css')
</head>
<body data-root="{{ url('/') }}" id="root">
    
	<!-- BEGIN: Header -->
	@include('includes.header')
	<!-- END: Header -->
	<!-- BEGIN: Page content -->
	@yield('content')
    <!-- END: Page content -->
	<!-- BEGIN: Footer -->
	@include('includes.footer')
	<!-- END: Footer -->
	
	<!-- The actual snackbar -->
	<div id="snackbar">Some text some message..</div>
	
	@stack('js')
	
	<script src="{{ asset('content/js/swiper.min.js') }}"></script>

	<script src="{{ asset('content/js/main.js') }}"></script>
		
    <!-- CarouselScripts -->
    <script>
        @if(Route::currentRouteName() == 'front.index')
       
            $('.owl-one').owlCarousel({
                loop: true,
                margin: 10,
                nav: false,
                dots: false,
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 80,
                    },
                    600: {
                        items: 1,
                        stagePadding: 160,
                    },
                    1000: {
                        items: 1,
                        stagePadding: 244,
                    }
                }
            });

            $('.owl-two').owlCarousel({
                loop: false,
                margin: 0,
                autoplay: true,
                nav: false,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    }
                }
            });
    		
            //NuberCounter
            var counted = 0;
            $(window).scroll(function() {

                var oTop = $('#counter').offset().top - window.innerHeight;
                if (counted == 0 && $(window).scrollTop() > oTop) {
                    $('.count').each(function() {
                        var $this = $(this),
                            countTo = $this.attr('data-count');
                        $({
                            countNum: $this.text()
                        }).animate({
                                countNum: countTo
                            },

                            {

                                duration: 2000,
                                easing: 'swing',
                                step: function() {
                                    $this.text(Math.floor(this.countNum));
                                },
                                complete: function() {
                                    $this.text(this.countNum);
                                    //alert('finished');
                                }

                            });
                    });
                    counted = 1;
                }

            });
        

		@endif
    </script>
</body>
</html>
