@extends('layouts.master')
@push('css')
<style>
    .text-color {
        color: #ff5f6a;
    }
</style>
@endpush
@section('content')
    
     <section class="main-banner">
       <div class="banner-inn">
            <img src="{{asset('content/images/testimonials-bg.jpg')}}" class="img-responsive" title="contact" alt="contact image">
        </div>
        
        <div class="aperture-page-title">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="slide-title-box">
                            <div class="page-title-heading">
                                <h1 class="title"> About Us</h1>
                            </div>
                            <div class="slide-breadcrumb-wrapper">
                                <p class="contact-text">
                                    
                                </p>
                            </div>  
                        </div>
                    </div>
                </div> 
            </div>                    
        </div>
    </section>

    <!-- content related to page -->

    <section class="ex-aboutsection">
        <div class="container pt-80 pb-80">
            <div class="row">
        		<div class="col-md-6">
        		    <div class="about-welcome">
        		        <h2>{{$about_me->name ?? 'Andy Craggs Biography'}}</h2>
            		    {!! $about_me->content !!}
            		</div>
        		</div>
        		<div class="col-md-6">
        		    <div class="about-img">
        		        <img src="{{ asset('application/public/uploads/about/'.$about_me->image) }}" alt="" class="img-responsive">
        		    </div>
        		</div>
        	</div>
        </div>
    </section>
      
    
@endsection
