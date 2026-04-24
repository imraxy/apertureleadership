@extends('layouts.master')

@push('css')
<style>
    /* Unified Hero Section */
    .unified-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #15151a 50%, #0a0a0c 100%);
        min-height: 90vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }
    
    .unified-hero::before {
        content: '';
        position: absolute;
        top: 5%;
        left: 10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(212, 166, 90, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(60px);
    }
    
    .unified-hero::after {
        content: '';
        position: absolute;
        bottom: 5%;
        right: 10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(74, 144, 217, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(60px);
    }
    
    .hero-content {
        text-align: center;
        max-width: 900px;
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: clamp(32px, 6vw, 56px);
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 16px;
        line-height: 1.2;
    }
    
    .hero-title span {
        color: #d4a65a;
    }
    
    .hero-subtitle {
        font-size: clamp(18px, 3vw, 24px);
        font-weight: 400;
        color: #a0a0a8;
        margin-bottom: 20px;
    }
    
    .hero-tagline {
        font-size: clamp(16px, 2vw, 20px);
        color: #808088;
        margin-bottom: 40px;
        line-height: 1.5;
    }
    
    .hero-cta {
        display: inline-block;
        background: #d4a65a;
        color: #0a0a0c !important;
        padding: 18px 48px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(212, 166, 90, 0.3);
    }
    
    .hero-cta:hover {
        background: #e4b66a;
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(212, 166, 90, 0.4);
        color: #0a0a0c !important;
    }
    
    /* About Section */
    .about-section {
        background: #121216;
        padding: 100px 0;
    }
    
    .about-section .container {
        max-width: 900px;
    }
    
    .about-section p {
        font-size: 18px;
        line-height: 1.8;
        color: #b0b0b8;
        text-align: center;
        margin-bottom: 30px;
    }
    
    /* Testimonials Section */
    .testimonials-section {
        background: #0a0a0c;
        padding: 80px 0;
    }
    
    .testimonials-section h2 {
        text-align: center;
        color: #ffffff;
        font-size: 36px;
        margin-bottom: 50px;
    }
    
    .testimonials-grid {
        display: flex;
        gap: 30px;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .testimonial-card {
        background: #1a1a1e;
        border: 1px solid #2a2a30;
        border-radius: 16px;
        padding: 32px;
        width: 100%;
        max-width: 500px;
        transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
        border-color: #d4a65a;
        transform: translateY(-5px);
    }
    
    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .testimonial-header img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 16px;
        border: 2px solid #d4a65a;
    }
    
    .testimonial-header h5 {
        color: #ffffff;
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .testimonial-header span {
        color: #6a6a70;
        font-size: 14px;
    }
    
    .testimonial-card p {
        color: #a0a0a8;
        font-size: 16px;
        line-height: 1.6;
        font-style: italic;
        margin: 0;
    }
</style>
@endpush

@section('content')
    {{-- Unified Hero Section with Title --}}
    <section class="unified-hero">
        <div class="hero-content">
            <h1 class="hero-title">Aperture: <span>Through the Lens Leadership</span></h1>
            <p class="hero-subtitle">Visual metaphors for leadership and strategy</p>
            <p class="hero-tagline">Photography from educational and humanitarian projects worldwide</p>
            <a href="{{ route('front.albums') }}" class="hero-cta">Explore the Gallery</a>
        </div>
    </section>
    
    {{-- Hidden original slider --}}
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
    <section class="about-section">
        <div class="container">
            <p>Henrik Ibsen first said "A thousand words leave not the same deep impression as does a single deed" which over time evolved into "A picture is worth a thousand words". This has been our inspiration behind Aperture, a system of metaphors developed to explore any question in the realm of leadership, strategy, change, innovation, creativity, or personal and organizational development.</p>
            <p>As a visual aid to dialogue, each image or set of images prompts us to explore and examine the questions and challenges we are facing in business and in life. Used in settings from consulting to coaching, mentoring, training and education, we took all of our photographs during travel assignments around the world for educational, social and humanitarian projects.</p>
        </div>
    </section>
    
    @if(count($testimonials))
    {{-- Testimonials Section --}}
    <section class="testimonials-section">
        <div class="container">
            <h2>What People Say</h2>
        </div>
        
        <div class="testimonials-grid">
            @foreach($testimonials as $testimonial)
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="{{asset('application/public/uploads/testimonials/'.$testimonial->image)}}" alt="{{$testimonial->name}}" />
                    <div>
                        <h5>{{$testimonial->name}}</h5>
                        <span>{{$testimonial->designation ?? 'Client'}}</span>
                    </div>
                </div>
                <p>"{{$testimonial->message}}"</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    
@endsection
