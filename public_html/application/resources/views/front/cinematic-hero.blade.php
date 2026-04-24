{{-- Cinematic Zoom Parallax Hero Section --}}
@push('css')
<style>
/* Cinematic Hero Styles */
.cinematic-hero {
    position: relative;
    height: 500vh;
    background: #060608;
}

.cinematic-sticky {
    position: sticky;
    top: 0;
    min-height: 100vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    perspective: 1000px;
}

/* Layer Architecture */
.cinematic-layer {
    position: absolute;
    will-change: transform;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Background - slowest layer */
.layer-bg {
    inset: -20%;
    z-index: 1;
    background: radial-gradient(ellipse at 50% 40%, #1a150d 0%, #060608 70%);
}

/* Mid layer - atmospheric elements */
.layer-mid {
    inset: 0;
    z-index: 2;
}

.aperture-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.15;
}

.shape-gold {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, #d4a65a 0%, transparent 70%);
    top: 10%;
    left: 5%;
}

.shape-blue {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, #4a90d9 0%, transparent 70%);
    top: 50%;
    right: 10%;
}

.shape-rose {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #c9a0dc 0%, transparent 70%);
    bottom: 15%;
    left: 35%;
}

/* Foreground - fastest layer (aperture blades) */
.layer-fg {
    inset: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
}

.aperture-blade {
    position: absolute;
    width: 200px;
    height: 600px;
    background: linear-gradient(180deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.3) 100%);
    border-radius: 2px;
}

.blade-left {
    left: -100px;
    transform: rotate(-15deg);
}

.blade-right {
    right: -100px;
    transform: rotate(15deg);
}

/* Hero Content - appears mid-scroll */
.layer-content {
    inset: 0;
    z-index: 4;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
}

.hero-content-card {
    background: rgba(17, 17, 20, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 60px 48px;
    text-align: center;
    max-width: 700px;
    box-shadow: 0 40px 80px rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
}

.hero-content-card h1 {
    font-size: clamp(32px, 5vw, 56px);
    font-weight: 600;
    letter-spacing: -0.025em;
    margin-bottom: 20px;
    color: #eae7e2;
    line-height: 1.15;
}

.hero-content-card h1 span {
    color: #d4a65a;
}

.hero-content-card p {
    font-size: 18px;
    color: #5a5a5e;
    line-height: 1.6;
    margin-bottom: 32px;
    max-width: 550px;
}

.hero-cta {
    display: inline-block;
    padding: 14px 36px;
    background: #d4a65a;
    color: #060608;
    font-size: 15px;
    font-weight: 500;
    border-radius: 10px;
    text-decoration: none;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-cta:hover {
    transform: translateY(-2px) scale(1.03);
}

/* Scroll indicator */
.scroll-indicator {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    text-align: center;
    color: #5a5a5e;
    font-size: 13px;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.4; }
    50% { opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content-card {
        padding: 40px 24px;
        margin: 0 20px;
    }
    
    .aperture-blade {
        width: 100px;
        height: 400px;
    }
    
    .shape-gold, .shape-blue, .shape-rose {
        width: 200px;
        height: 200px;
    }
}
</style>
@endpush

<!-- Cinematic Zoom Parallax Hero -->
<section class="cinematic-hero" id="cinematic-hero">
    <div class="cinematic-sticky">
        <!-- Background Layer -->
        <div class="cinematic-layer layer-bg"></div>
        
        <!-- Mid Layer - Atmospheric Shapes -->
        <div class="cinematic-layer layer-mid">
            <div class="aperture-shape shape-gold"></div>
            <div class="aperture-shape shape-blue"></div>
            <div class="aperture-shape shape-rose"></div>
        </div>
        
        <!-- Foreground Layer - Aperture Blades -->
        <div class="cinematic-layer layer-fg">
            <div class="aperture-blade blade-left"></div>
            <div class="aperture-blade blade-right"></div>
        </div>
        
        <!-- Content Layer -->
        <div class="cinematic-layer layer-content">
            <div class="hero-content-card">
                <h1>Aperture: <span>Through the Lens</span> Leadership</h1>
                <p>Visual metaphors for leadership, strategy, and organizational development. Photography from educational and humanitarian projects worldwide.</p>
                <a href="{{ route('front.albums') }}" class="hero-cta">Explore the Gallery</a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div>Scroll to explore</div>
            <div style="margin-top: 8px; font-size: 20px;">↓</div>
        </div>
    </div>
</section>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
    gsap.registerPlugin(ScrollTrigger);
    
    // Get elements
    const heroSection = document.querySelector('.cinematic-hero');
    const layerBg = document.querySelector('.layer-bg');
    const layerMid = document.querySelector('.layer-mid');
    const bladeLeft = document.querySelector('.blade-left');
    const bladeRight = document.querySelector('.blade-right');
    const layerContent = document.querySelector('.layer-content');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    
    // Create main timeline
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: heroSection,
            start: "top top",
            end: "bottom bottom",
            scrub: 1,
            pin: false
        }
    });
    
    // Phase 1: Background subtle movement (0-30%)
    tl.fromTo(layerBg, 
        { scale: 1 },
        { scale: 1.1, ease: "none" },
        0
    );
    
    // Phase 2: Mid layer shapes drift (0-50%)
    tl.fromTo(layerMid,
        { x: 0, y: 0 },
        { x: -50, y: -30, ease: "none" },
        0
    );
    
    // Phase 3: Aperture blades zoom outward (0-60%)
    tl.fromTo(bladeLeft,
        { x: 0, rotation: -15 },
        { x: -300, rotation: -25, ease: "power2.out" },
        0
    );
    
    tl.fromTo(bladeRight,
        { x: 0, rotation: 15 },
        { x: 300, rotation: 25, ease: "power2.out" },
        0
    );
    
    // Phase 4: Content fades in and scales (30-70%)
    tl.fromTo(layerContent,
        { opacity: 0, scale: 0.8 },
        { opacity: 1, scale: 1, ease: "power2.out" },
        0.3
    );
    
    // Phase 5: Scroll indicator fades out (0-20%)
    tl.fromTo(scrollIndicator,
        { opacity: 1 },
        { opacity: 0, ease: "none" },
        0
    );
    
    // Phase 6: Content holds then fades (70-100%)
    tl.to(layerContent,
        { opacity: 0, y: -50, ease: "power2.in" },
        0.7
    );
</script>
@endpush
