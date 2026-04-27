@extends('layouts.master')

@push('css')
<style>
    /* Unified Hero Section */
    .unified-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #15151a 50%, #0a0a0c 100%);
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 40px 20px 80px;
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
    
    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
        animation: bounce 2s infinite;
    }
    
    .scroll-indicator:hover {
        opacity: 1;
    }
    
    .scroll-text {
        color: #a0a0a8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 8px;
    }
    
    .scroll-arrow {
        color: #d4a65a;
        animation: bounce-arrow 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
        40% { transform: translateX(-50%) translateY(-10px); }
        60% { transform: translateX(-50%) translateY(-5px); }
    }
    
    @keyframes bounce-arrow {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(5px); }
        60% { transform: translateY(3px); }
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
    
    /* Cinematic Image Showcase Section */
    .image-showcase-section {
        background: linear-gradient(180deg, #0a0a0c 0%, #121216 50%, #0a0a0c 100%);
        padding: 80px 0;
        overflow: hidden;
    }
    
    .showcase-header {
        text-align: center;
        margin-bottom: 60px;
        padding: 0 20px;
    }
    
    .showcase-header h2 {
        font-size: clamp(28px, 4vw, 42px);
        color: #ffffff;
        margin-bottom: 12px;
        font-weight: 600;
    }
    
    .showcase-header p {
        font-size: 18px;
        color: #808088;
    }
    
    /* Kinetic Marquee */
    .marquee-container {
        width: 100%;
        overflow: hidden;
        margin-bottom: 20px;
        position: relative;
    }
    
    .marquee-container::before,
    .marquee-container::after {
        content: '';
        position: absolute;
        top: 0;
        width: 150px;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }
    
    .marquee-container::before {
        left: 0;
        background: linear-gradient(90deg, #0a0a0c 0%, transparent 100%);
    }
    
    .marquee-container::after {
        right: 0;
        background: linear-gradient(270deg, #0a0a0c 0%, transparent 100%);
    }
    
    .marquee-track {
        display: flex;
        gap: 20px;
        width: max-content;
    }
    
    .marquee-left {
        animation: marquee-left 50s linear infinite;
    }
    
    .marquee-right {
        animation: marquee-right 45s linear infinite;
    }
    
    @keyframes marquee-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    @keyframes marquee-right {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }
    
    .marquee-container:hover .marquee-track {
        animation-play-state: paused;
    }
    
    /* Marquee Image Cards */
    .marquee-image {
        position: relative;
        width: 350px;
        height: 250px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .marquee-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .marquee-image:hover {
        transform: scale(1.05);
        z-index: 10;
    }
    
    .marquee-image:hover img {
        transform: scale(1.1);
    }
    
    /* Image Overlay */
    .image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.8) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 20px;
    }
    
    .marquee-image:hover .image-overlay {
        opacity: 1;
    }
    
    .view-hint {
        color: #d4a65a;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 8px 20px;
        border: 1px solid #d4a65a;
        border-radius: 20px;
        background: rgba(212, 166, 90, 0.1);
        transition: all 0.3s ease;
    }
    
    .view-hint:hover {
        background: #d4a65a;
        color: #0a0a0c;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .marquee-image {
            width: 280px;
            height: 200px;
        }
        
        .marquee-left {
            animation-duration: 35s;
        }
        
        .marquee-right {
            animation-duration: 30s;
        }
    }
    
    /* Lightbox Modal */
    .lightbox-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 40px;
        backdrop-filter: blur(10px);
    }
    
    .lightbox-modal img {
        max-width: 90%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 40px;
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 48px;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
        z-index: 10000;
    }
    
    .lightbox-close:hover {
        opacity: 1;
        color: #d4a65a;
    }
    
    .lightbox-caption {
        color: #a0a0a8;
        font-size: 18px;
        margin-top: 20px;
        text-align: center;
        max-width: 800px;
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
        
        {{-- Scroll Indicator --}}
        <div class="scroll-indicator" onclick="document.querySelector('.image-showcase-section').scrollIntoView({behavior: 'smooth'})">
            <span class="scroll-text">Featured Photography</span>
            <div class="scroll-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12l7 7 7-7"/>
                </svg>
            </div>
        </div>
    </section>
    
    {{-- Cinematic Image Showcase - Kinetic Marquee + Grid --}}
    @if(count($sessionImagesRow1) > 0)
    <section class="image-showcase-section">
        <div class="showcase-header">
            <h2>Featured Photography</h2>
            <p>Visual stories from around the world</p>
        </div>
        
        {{-- Kinetic Marquee - Top Row --}}
        <div class="marquee-container">
            <div class="marquee-track marquee-left">
                @foreach($sessionImagesRow1 as $image)
                <div class="marquee-image" onclick="openLightbox('{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}', '{{ $image->title ?? 'Featured Image' }}')">
                    <img src="{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}" alt="{{ $image->title ?? 'Featured Image' }}" loading="lazy">
                    <div class="image-overlay">
                        <span class="view-hint">View</span>
                    </div>
                </div>
                @endforeach
                {{-- Duplicate for seamless loop --}}
                @foreach($sessionImagesRow1 as $image)
                <div class="marquee-image" onclick="openLightbox('{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}', '{{ $image->title ?? 'Featured Image' }}')">
                    <img src="{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}" alt="{{ $image->title ?? 'Featured Image' }}" loading="lazy">
                    <div class="image-overlay">
                        <span class="view-hint">View</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Kinetic Marquee - Bottom Row (Different Images, Reverse Direction) --}}
        <div class="marquee-container">
            <div class="marquee-track marquee-right">
                @foreach($sessionImagesRow2 as $image)
                <div class="marquee-image" onclick="openLightbox('{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}', '{{ $image->title ?? 'Featured Image' }}')">
                    <img src="{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}" alt="{{ $image->title ?? 'Featured Image' }}" loading="lazy">
                    <div class="image-overlay">
                        <span class="view-hint">View</span>
                    </div>
                </div>
                @endforeach
                {{-- Duplicate for seamless loop --}}
                @foreach($sessionImagesRow2 as $image)
                <div class="marquee-image" onclick="openLightbox('{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}', '{{ $image->title ?? 'Featured Image' }}')">
                    <img src="{{ asset('application/public/uploads/albums/'.$image->id.'/'.$image->session_image) }}" alt="{{ $image->title ?? 'Featured Image' }}" loading="lazy">
                    <div class="image-overlay">
                        <span class="view-hint">View</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
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
    
    {{-- Lightbox Modal --}}
    <div id="imageLightbox" class="lightbox-modal" onclick="closeLightbox(event)">
        <button class="lightbox-close" onclick="closeLightbox(event)">&times;</button>
        <img id="lightboxImage" src="" alt="">
        <div id="lightboxCaption" class="lightbox-caption"></div>
    </div>
    
    @push('js')
    <script>
        function openLightbox(imageSrc, caption) {
            const lightbox = document.getElementById('imageLightbox');
            const img = document.getElementById('lightboxImage');
            const cap = document.getElementById('lightboxCaption');
            
            img.src = imageSrc;
            cap.textContent = caption;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Pause marquee animations when lightbox is open
            document.querySelectorAll('.marquee-track').forEach(track => {
                track.style.animationPlayState = 'paused';
            });
        }
        
        function closeLightbox(event) {
            // Only close if clicking the background or close button, not the image
            if (event.target.id === 'imageLightbox' || event.target.classList.contains('lightbox-close')) {
                const lightbox = document.getElementById('imageLightbox');
                lightbox.style.display = 'none';
                document.body.style.overflow = 'auto';
                
                // Resume marquee animations
                document.querySelectorAll('.marquee-track').forEach(track => {
                    track.style.animationPlayState = 'running';
                });
            }
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const lightbox = document.getElementById('imageLightbox');
                if (lightbox.style.display === 'flex') {
                    lightbox.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    document.querySelectorAll('.marquee-track').forEach(track => {
                        track.style.animationPlayState = 'running';
                    });
                }
            }
        });
    </script>
    @endpush
    
@endsection
