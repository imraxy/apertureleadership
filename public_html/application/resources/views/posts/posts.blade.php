@extends('layouts.master')
@push('css')
<style>
    /* Elegant Guidelines Page Styling */
    .guidelines-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
        padding: 80px 0 60px;
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
        transition: all 0.3s ease;
    }
    
    .guideline-card:hover {
        border-color: #d4a65a;
        transform: translateY(-2px);
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
    
    .guideline-card p:last-child {
        margin-bottom: 0;
    }
    
    @media (max-width: 992px) {
        .guidelines-layout {
            flex-direction: column;
            padding: 0 20px;
        }
        
        .guidelines-sidebar {
            width: 100%;
        }
        
        .guidelines-nav {
            position: relative;
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

