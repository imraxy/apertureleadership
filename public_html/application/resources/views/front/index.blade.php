@extends('layouts.master')

@push('css')
<style>
    .testinn_img{
        border-radius: 100%;
    }
    
    /* Cinematic Hero Section - Scoped to avoid conflicts */
    .cinematic-hero-section {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 50%, #0a0a0c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .cinematic-hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        padding: 80px 40px;
        max-width: 900px;
    }
    
    .cinematic-hero-content .hero-tagline {
        font-size: clamp(24px, 4vw, 42px);
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 16px;
        line-height: 1.3;
        letter-spacing: -0.01em;
    }
    
    .cinematic-hero-content .hero-subtitle {
        font-size: 16px;
        color: #a0a0a8;
        line-height: 1.6;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .cinematic-cta {
        display: inline-block;
        padding: 16px 40px;
        background: #d4a65a;
        color: #0a0a0c;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(212, 166, 90, 0.3);
    }
    
    .cinematic-cta:hover {
        background: #e4b66a;
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(212, 166, 90, 0.4);
    }
    
    /* Decorative elements */
    .hero-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
    }
    
    .hero-glow-1 {
        width: 400px;
        height: 400px;
        background: #d4a65a;
        top: -100px;
        left: -100px;
    }
    
    .hero-glow-2 {
        width: 300px;
        height: 300px;
        background: #4a90d9;
        bottom: -50px;
        right: -50px;
    }
    
    /* About Section */
    .cinematic-about {
        background: #0f0f12;
        padding: 100px 0;
    }
    
    .cinematic-about .container {
        max-width: 1000px;
    }
    
    .cinematic-about p {
        font-size: 20px;
        line-height: 1.8;
        color: #c0c0c8;
        text-align: center;
        margin-bottom: 30px;
    }
    
    /* Testimonials Section */
    .cinematic-testimonials {
        background: #0a0a0c;
        padding: 80px 0;
        overflow: hidden;
    }
    
    .cinematic-testimonials h2 {
        text-align: center;
        color: #ffffff;
        font-size: 36px;
        margin-bottom: 50px;
        font-weight: 600;
    }
    
    .testimonial-marquee {
        position: relative;
    }
    
    .marquee-track {
        display: flex;
        animation: marquee 40s linear infinite;
    }
    
    .marquee-track:hover {
        animation-play-state: paused;
    }
    
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .testimonial-card {
        flex-shrink: 0;
        width: 400px;
        margin: 0 20px;
        background: #151519;
        border: 1px solid #25252a;
        border-radius: 16px;
        padding: 32px;
        transition: transform 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        border-color: #d4a65a;
    }
    
    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .testimonial-header img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 16px;
        border-radius: 50%;
        border: 2px solid #d4a65a;
    }
    
    .testimonial-header h5 {
        color: #ffffff;
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .testimonial-header p {
        color: #6a6a70;
        margin: 4px 0 0 0;
        font-size: 13px;
    }
    
    .testimonial-card > p {
        color: #a0a0a8;
        font-size: 15px;
        line-height: 1.6;
        font-style: italic;
        margin: 0;
    }
</style>
@endpush

@section('content')
    {{-- Cinematic Hero Section - Compact, elegant, no duplicate title --}}
    <section class="cinematic-hero-section">
        <div class="hero-glow hero-glow-1"></div>
        <div class="hero-glow hero-glow-2"></div>
        
        <div class="cinematic-hero-content">
            <p class="hero-tagline">Visual metaphors for leadership, strategy, and organizational development</p>
            <p class="hero-subtitle">Photography from educational and humanitarian projects worldwide</p>
            <a href="{{ route('front.albums') }}" class="cinematic-cta">Explore the Gallery</a>
        </div>
    </section>
    
    {{-- Original slider section (preserved but hidden) --}}
    @if(count($sliders))
    <div style="display: none;">
        <div class="swiper-container swip-slider">
            <div class="swiper-wrapper">
                @foreach($sliders as $slider)
                <div class="swiper-slide" style="background-image:url('{{ asset('application/public/uploads/sliders/'.$slider->image) }}')">
                </div>  
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    @endif
    
    {{-- About Section --}}
    <section class="cinematic-about">
        <div class="container">
            <p>Henrik Ibsen first said "A thousand words leave not the same deep impression as does a single deed" which over time evolved into "A picture is worth a thousand words". This has been our inspiration behind Aperture, a system of metaphors developed to explore any question in the realm of leadership, strategy, change, innovation, creativity, or personal and organizational development.</p>
            <p>As a visual aid to dialogue, each image or set of images prompts us to explore and examine the questions and challenges we are facing in business and in life. Used in settings from consulting to coaching, mentoring, training and education, we took all of our photographs during travel assignments around the world for educational, social and humanitarian projects.</p>
        </div>
    </section>
    
    @if(count($testimonials))
    {{-- Testimonials Section with Kinetic Marquee --}}
    <section class="cinematic-testimonials">
        <div class="container">
            <h2>What People Say</h2>
        </div>
        
        <div class="testimonial-marquee">
            <div class="marquee-track">
                @foreach($testimonials as $testimonial)
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="{{asset('application/public/uploads/testimonials/'.$testimonial->image)}}" alt="{{$testimonial->name}}" />
                        <div>
                            <h5>{{$testimonial->name}}</h5>
                            <p>{{$testimonial->designation ?? 'Client'}}</p>
                        </div>
                    </div>
                    <p>"{{$testimonial->message}}"</p>
                </div>
                @endforeach
                {{-- Duplicate for seamless loop --}}
                @foreach($testimonials as $testimonial)
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="{{asset('application/public/uploads/testimonials/'.$testimonial->image)}}" alt="{{$testimonial->name}}" />
                        <div>
                            <h5>{{$testimonial->name}}</h5>
                            <p>{{$testimonial->designation ?? 'Client'}}</p>
                        </div>
                    </div>
                    <p>"{{$testimonial->message}}"</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
@endsection
