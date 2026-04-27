@extends('layouts.master')

@push('css')
<style>
    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .about-hero .page-title {
        text-align: center;
        color: #ffffff;
        font-size: 48px;
        font-weight: 600;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    
    .about-hero .page-subtitle {
        text-align: center;
        color: #a0a0a8;
        font-size: 18px;
        position: relative;
        z-index: 1;
    }
    
    /* About Content Section */
    .about-content {
        background: #0f0f12;
        padding: 80px 0;
        min-height: calc(100vh - 300px);
    }
    
    .about-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 40px;
        align-items: center;
    }
    
    .about-text h2 {
        color: #ffffff;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 2px solid #d4a65a;
        display: inline-block;
    }
    
    .about-text p {
        color: #c0c0c8;
        font-size: 16px;
        line-height: 1.8;
        margin-bottom: 20px;
    }
    
    .about-text p:last-child {
        margin-bottom: 0;
    }
    
    .about-image {
        position: relative;
    }
    
    .about-image img {
        width: 100%;
        border-radius: 16px;
        border: 2px solid #2a2a30;
        transition: all 0.3s ease;
    }
    
    .about-image:hover img {
        border-color: #d4a65a;
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }
    
    .about-image::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: -20px;
        bottom: -20px;
        border: 2px solid #d4a65a;
        border-radius: 16px;
        z-index: -1;
        opacity: 0.3;
    }
    
    @media (max-width: 992px) {
        .about-layout {
            grid-template-columns: 1fr;
            padding: 0 20px;
            gap: 40px;
        }
        
        .about-image {
            order: -1;
        }
        
        .about-image::before {
            display: none;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1 class="page-title">About Us</h1>
            <p class="page-subtitle">Learn about the vision behind Aperture and our founder</p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="about-content">
        <div class="about-layout">
            <div class="about-text">
                <h2>{{$about_me->name ?? 'Andy Craggs Biography'}}</h2>
                {!! $about_me->content !!}
            </div>
            
            <div class="about-image">
                <img src="{{ asset('application/public/uploads/about/'.$about_me->image) }}" alt="{{$about_me->name ?? 'Andy Craggs'}}" class="img-responsive">
            </div>
        </div>
    </section>
      
    
@endsection
