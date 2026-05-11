@extends('layouts.master')
@push('css')
<style>
    .testinn_img{
        border-radius: 100%;
    }
</style>
@endpush
@section('content')
    @if(count($sliders))
    <!-- Slider section start -->
    <div class="swiper-container swip-slider">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
            <div class="swiper-slide" style="background-image:url('{{ asset('application/public/uploads/sliders/'.$slider->image) }}')">
    <!--            @if(!empty($slider->title))-->
    <!--            <p>{{$slider->title}}</p>-->
				<!--@endif-->
            </div>  
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!-- Slider section end -->
    @endif
    <!-- About -->
    <section class="ex-aboutsection">
        <div class="container pt-80 pb-80">
            <div class="row">
                <div class="col-sm-12">
                    <div class="ex-aboutright pl-5 pr-5 pt-3 pb-3">
                        <p>Henrik Ibsen first said “A thousand words leave not the same deep impression as does a single deed” which over time evolved into “A picture is worth a thousand words”. This has been our inspiration behind Aperture, a system of metaphors developed to explore any question in the realm of leadership, strategy, change, innovation, creativity, or personal and organizational development. As a visual aid to dialogue, each image or set of images prompts us to explore and examine the questions and challenges we are facing in business and in life. Used in settings from consulting to coaching, mentoring, training and education, we took all of our photographs during travel assignments around the world for educational, social and humanitarian projects. We hope these images will trigger metaphors and generate new ideas, perspectives, and meaning for you and your organization.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @if(count($testimonials))
    <!-- Testimonials -->
    <section class="ex-testimonialsection pt-80 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Testimonials</h2>
                    <div id="home-testimonails" class="owl-two owl-carousel owl-theme ex-testimonials">
                        @foreach($testimonials as $testimonial)
                        <div class="item">
                            <div class="ex-testinn">
                                <img src="{{asset('application/public/uploads/testimonials/'.$testimonial->image)}}" class="testinn_img" alt="{{$testimonial->name}}" />
                                <p>{{$testimonial->message}}
                                </p>
                                <h5>{{$testimonial->name}}</h5>
                            </div>
                        </div>
                        @endforeach                     
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
@endsection
