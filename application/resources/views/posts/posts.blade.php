@extends('layouts.master')
@push('css')
<style>
    /* Elegant Guidelines Page Styling */
    .guidelines-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
        padding: 60px 0 20px;
        position: relative;
        overflow: hidden;
    }
    
    .guidelines-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .guidelines-hero .page-title {
        text-align: center;
        color: #ffffff;
        font-size: 48px;
        font-weight: 600;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    
    .guidelines-hero .page-subtitle {
        text-align: center;
        color: #a0a0a8;
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }
    
    .guidelines-container {
        background: #0f0f12;
        min-height: 100vh;
        padding: 60px 0;
    }
    
    .guidelines-layout {
        display: flex;
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }
    
    .guidelines-sidebar {
        width: 300px;
        flex-shrink: 0;
    }
    
    .guidelines-nav {
        background: #151519;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #25252a;
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    
    .guidelines-nav h3 {
        color: #d4a65a;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }
    
    .guidelines-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .guidelines-nav li {
        margin-bottom: 8px;
    }
    
    .guidelines-nav a {
        display: block;
        padding: 12px 16px;
        color: #a0a0a8;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 15px;
    }
    
    .guidelines-nav a:hover,
    .guidelines-nav a.active {
        background: rgba(212, 166, 90, 0.1);
        color: #d4a65a;
    }
    
    .guidelines-content {
        flex: 1;
    }
    
    .guideline-card {
        background: #151519;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 24px;
        border: 1px solid #25252a;
        scroll-margin-top: 120px;
    }
    
    .guideline-card h3 {
        color: #ffffff;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #d4a65a;
    }
    
    .guideline-card p {
        color: #c0c0c8;
        font-size: 16px;
        line-height: 1.8;
        margin-bottom: 16px;
    }
    
    /* Mobile Styles */
    @media (max-width: 992px) {
        .guidelines-container {
            padding: 0;
        }
        
        .guidelines-layout {
            flex-direction: column;
            padding: 0 20px;
            gap: 0;
        }
        
        .guidelines-sidebar {
            width: 100%;
            margin: 0 -20px;
            padding: 15px 20px;
            background: #0f0f12;
            border-bottom: 1px solid #25252a;
            position: relative;
        }
        
        .guidelines-sidebar.is-sticky {
            position: fixed;
            top: 62px;
            left: 0;
            right: 0;
            z-index: 1000;
            margin: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        .guidelines-sidebar.is-sticky + .guidelines-content {
            padding-top: 120px;
        }
        
        .guidelines-nav {
            position: relative;
            top: auto;
            max-height: none;
            overflow-y: visible;
            background: transparent;
            border: none;
            padding: 0;
        }
        
        .guidelines-nav h3 {
            display: none;
        }
        
        .guidelines-nav ul {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }
        
        .guidelines-nav li {
            margin-bottom: 0;
        }
        
        .guidelines-nav a {
            white-space: nowrap;
            font-size: 12px;
            padding: 8px 12px;
            background: #1a1a1e;
            border: 1px solid #2a2a30;
            border-radius: 20px;
        }
        
        .guidelines-nav a.active {
            background: rgba(212, 166, 90, 0.2);
            border-color: #d4a65a;
            color: #d4a65a;
        }
        
        .desktop-only {
            display: none !important;
        }
    }
    
    @media (max-width: 576px) {
        .guidelines-nav a {
            font-size: 11px;
            padding: 6px 10px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Elegant Hero -->
    <section class="guidelines-hero">
        <div class="container">
            <h1 class="page-title">Guidelines</h1>
            <p class="page-subtitle">Discover how Aperture uses visual metaphors to explore leadership, strategy, and organizational development</p>
        </div>
    </section>

    <!-- Content -->
    <section class="guidelines-container">
        <div class="guidelines-layout">
            <!-- Sidebar Navigation -->
            <aside class="guidelines-sidebar">
                <nav class="guidelines-nav">
                    <h3>Topics</h3>
                    <ul>
                        @php $i = 0; @endphp
                        @foreach($posts as $row_tab)
                        @php $i++; @endphp
                        <li>
                            <a href="#guideline-{{$i}}" class="nav-link @if($i==1) active @endif">
                                {{$row_tab->title}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="guidelines-content">
                @php $s = 0; @endphp
                @foreach($posts as $row_post)
                @php $s++; @endphp
                <div class="guideline-card" id="guideline-{{$s}}">
                    <h3>{{$row_post->title}}</h3>
                    {!!$row_post->description!!}
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.guidelines-nav a');
        const sidebar = document.querySelector('.guidelines-sidebar');
        const hero = document.querySelector('.guidelines-hero');
        let isSticky = false;
        
        // Handle sticky behavior on scroll
        function handleScroll() {
            if (window.innerWidth > 992) return;
            
            const mainHeaderEl = document.querySelector('header, .main-header');
            const headerHeight = mainHeaderEl ? mainHeaderEl.offsetHeight : 62;
            
            const heroBottom = hero.offsetTop + hero.offsetHeight;
            const scrollPos = window.scrollY;
            
            if (scrollPos > heroBottom - headerHeight) {
                if (!isSticky) {
                    sidebar.classList.add('is-sticky');
                    isSticky = true;
                }
            } else {
                if (isSticky) {
                    sidebar.classList.remove('is-sticky');
                    isSticky = false;
                }
            }
        }
        
        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', handleScroll);
        
        // Smooth scroll for nav links
        let isManualNavigation = false;
        let manualNavTimeout;
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    // Set flag to prevent scroll spy from overriding
                    isManualNavigation = true;
                    clearTimeout(manualNavTimeout);
                    
                    // Update active state immediately
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                        // On mobile (< 992px), calculate offset accounting for sticky sidebar
                        if (window.innerWidth <= 992) {
                            // Force sidebar to sticky state BEFORE calculating scroll position
                            // This ensures the .is-sticky CSS (including padding-top on content) is active
                            if (!isSticky) {
                                sidebar.classList.add('is-sticky');
                                isSticky = true;
                            }
                            
                            // Measure actual obstruction at click time
                            const mainHeaderEl = document.querySelector('header, .main-header');
                            const headerHeight = mainHeaderEl ? mainHeaderEl.offsetHeight : 62;
                            const sidebarHeight = sidebar.offsetHeight;
                            // On mobile: obstruction = header + sticky sidebar pills
                            const offset = headerHeight + sidebarHeight + 20; // 20px buffer
                            // Use getBoundingClientRect for reliable positioning
                            const top = targetSection.getBoundingClientRect().top + window.scrollY - offset;
                            window.scrollTo({ top, behavior: 'smooth' });
                        } else {
                            // Desktop: just use scrollIntoView with CSS scroll-margin-top
                            targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    
                    // Clear flag after smooth scroll completes
                    manualNavTimeout = setTimeout(() => {
                        isManualNavigation = false;
                        // Force an immediate active link update after the timeout to correct any mismatch
                        updateActiveLink();
                    }, 800);
                }
            });
        });
        
        // Update active link on scroll
        const sections = document.querySelectorAll('.guideline-card');
        
        function updateActiveLink() {
            // Don't update during manual navigation
            if (isManualNavigation) return;
            
            let current = '';
            
            // On mobile, account for sticky sidebar as extra obstruction
            const mainHeaderEl = document.querySelector('header, .main-header');
            let headerHeight = mainHeaderEl ? mainHeaderEl.offsetHeight : 62;
            if (window.innerWidth <= 992) {
                // Include sticky sidebar height in the detection threshold
                headerHeight += sidebar.offsetHeight;
            }
            
            // Active section = the one closest to the header bottom (topmost visible section)
            let bestSection = null;
            let smallestEffectiveTop = Infinity;
            
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                const sectionId = section.getAttribute('id');
                
                if (rect.bottom > headerHeight) {
                    // For sections scrolled past (rect.top < headerHeight), their effective position is at the header
                    const effectiveTop = Math.max(headerHeight, rect.top);
                    if (effectiveTop < smallestEffectiveTop) {
                        smallestEffectiveTop = effectiveTop;
                        bestSection = sectionId;
                    }
                }
            });
            
            if (bestSection) {
                current = bestSection;
            }
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', updateActiveLink);
        
        // Initial check
        handleScroll();
    });
</script>
@endpush