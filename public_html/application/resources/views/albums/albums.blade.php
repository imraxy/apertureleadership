@extends('layouts.master')

@push('css')
<style>
    /* Albums Hero Section */
    .albums-hero {
        background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .albums-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .albums-hero .page-title {
        color: #ffffff;
        font-size: 48px;
        font-weight: 600;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    
    .albums-hero .page-subtitle {
        color: #a0a0a8;
        font-size: 18px;
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Category Filter Bar */
    .category-filter {
        background: #151519;
        padding: 24px 0;
        border-bottom: 1px solid #2a2a30;
        position: sticky;
        top: 0;
        z-index: 100;
    }
    
    .category-filter nav ul {
        display: flex;
        justify-content: center;
        gap: 12px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
    }
    
    .category-filter nav ul li a {
        display: block;
        padding: 12px 24px;
        color: #a0a0a8;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .category-filter nav ul li a:hover {
        color: #d4a65a;
        background: rgba(212, 166, 90, 0.1);
        border-color: rgba(212, 166, 90, 0.3);
    }
    
    .category-filter nav ul li.tab-current a,
    .category-filter nav ul li#tab-current a {
        color: #0a0a0c;
        background: #d4a65a;
        border-color: #d4a65a;
    }
    
    .category-filter nav ul li.tab-current a:hover,
    .category-filter nav ul li#tab-current a:hover {
        background: #e4b66a;
    }
    
    /* Gallery Section */
    .gallery-section {
        background: #0f0f12;
        padding: 60px 0 100px;
        min-height: 60vh;
    }
    
    /* Masonry Grid Layout */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-auto-rows: 10px;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }
    
    /* Gallery Item */
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #1a1a1e;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    /* Dynamic heights for masonry effect */
    .gallery-item.portrait {
        grid-row: span 35;
    }
    
    .gallery-item.landscape {
        grid-row: span 25;
    }
    
    .gallery-item.square {
        grid-row: span 30;
    }
    
    /* Image Overlay */
    .gallery-item .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px;
    }
    
    .gallery-item:hover .overlay {
        opacity: 1;
    }
    
    /* Image Info */
    .image-info {
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover .image-info {
        transform: translateY(0);
    }
    
    .image-title {
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    
    .image-category {
        color: #d4a65a;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Add to Folder Button */
    .add-to-folder {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
        background: rgba(212, 166, 90, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        z-index: 10;
    }
    
    .gallery-item:hover .add-to-folder {
        opacity: 1;
        transform: scale(1);
    }
    
    .add-to-folder:hover {
        background: #d4a65a;
        transform: scale(1.1);
    }
    
    .add-to-folder img {
        width: 20px;
        height: 20px;
        filter: brightness(0);
    }
    
    /* Login Section */
    .login-section {
        background: #0a0a0c;
        padding: 60px 0;
        text-align: center;
    }
    
    .login-section p {
        color: #a0a0a8;
        font-size: 16px;
        margin-bottom: 24px;
    }
    
    .login-btn {
        display: inline-block;
        background: #d4a65a;
        color: #0a0a0c;
        padding: 16px 48px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .login-btn:hover {
        background: #e4b66a;
        transform: translateY(-2px);
        color: #0a0a0c;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 16px;
            padding: 0 20px;
        }
        
        .gallery-item.portrait {
            grid-row: span 30;
        }
        
        .gallery-item.landscape {
            grid-row: span 20;
        }
        
        .gallery-item.square {
            grid-row: span 25;
        }
        
        .category-filter nav ul {
            gap: 8px;
        }
        
        .category-filter nav ul li a {
            padding: 10px 16px;
            font-size: 12px;
        }
    }
    
    /* Loading Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .gallery-item {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .gallery-item:nth-child(1) { animation-delay: 0.05s; }
    .gallery-item:nth-child(2) { animation-delay: 0.1s; }
    .gallery-item:nth-child(3) { animation-delay: 0.15s; }
    .gallery-item:nth-child(4) { animation-delay: 0.2s; }
    .gallery-item:nth-child(5) { animation-delay: 0.25s; }
    .gallery-item:nth-child(6) { animation-delay: 0.3s; }
    .gallery-item:nth-child(7) { animation-delay: 0.35s; }
    .gallery-item:nth-child(8) { animation-delay: 0.4s; }
    .gallery-item:nth-child(9) { animation-delay: 0.45s; }
    .gallery-item:nth-child(10) { animation-delay: 0.5s; }
    .gallery-item:nth-child(11) { animation-delay: 0.55s; }
    .gallery-item:nth-child(12) { animation-delay: 0.6s; }
</style>

<link rel="stylesheet" href="{{asset('content/css/photoswipe.css')}}"> 
<link rel="stylesheet" href="{{asset('content/css/default-skin/default-skin.css')}}">
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="albums-hero">
        <div class="container">
            <h1 class="page-title">Photo Gallery</h1>
            <p class="page-subtitle">Explore our collection of leadership metaphors from around the world</p>
        </div>
    </section>
    
    <!-- Category Filter -->
    <section class="category-filter">
        <nav>
            <ul>
                @if(request('slug'))
                <li><a href="{{route('front.albums')}}">All</a></li>
                @else
                <li class="tab-current"><a href="{{route('front.albums')}}">All</a></li>
                @endif
                
                @foreach($albumcategories as $albumcategory)
                @php
                    $isActive = request('slug') == $albumcategory->slug;
                @endphp
                <li class="{{ $isActive ? 'tab-current' : '' }}">
                    <a href="{{route('front.albums', $albumcategory->slug)}}">{{$albumcategory->name}}</a>
                </li>
                @endforeach
            </ul>
        </nav>
    </section>
    
    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="gallery-grid" itemscope itemtype="http://schema.org/ImageGallery">
            @foreach($albums as $album)
            @php
                // Determine aspect ratio class
                $width = $album->width ?? 800;
                $height = $album->height ?? 600;
                $ratio = $width / $height;
                
                if ($ratio < 0.8) {
                    $aspectClass = 'portrait';
                } elseif ($ratio > 1.3) {
                    $aspectClass = 'landscape';
                } else {
                    $aspectClass = 'square';
                }
                
                // Get category name
                $categoryName = $album->album_category->name ?? 'Uncategorized';
            @endphp
            
            <figure itemprop="associatedMedia" class="gallery-item {{ $aspectClass }} ext{{$album->id}}" itemscope itemtype="http://schema.org/ImageObject">
                <a href="{{asset('application/public/uploads/albums')}}/{{$album->id}}/{{$album->session_image}}" 
                   itemprop="contentUrl" 
                   data-size="{{$album->width}}x{{$album->height}}">
                    <img src="{{asset('application/public/uploads/albums')}}/{{$album->id}}/{{$album->small_image && file_exists(public_path('uploads/albums/'.$album->id.'/'.$album->small_image)) ? $album->small_image : $album->session_image}}" 
                         itemprop="thumbnail" 
                         alt="{{$album->title}}" 
                         loading="lazy" />
                </a>
                
                <div class="overlay">
                    <div class="image-info">
                        <h3 class="image-title">{{$album->title}}</h3>
                        <span class="image-category">{{ $categoryName }}</span>
                    </div>
                </div>
                
                @guest
                <button class="add-to-folder toCart addtocartbtn" data-val="{{$album->id}}" title="Add to Folder">
                    <img src="{{asset('content/images/folder-plus1.png')}}" alt="Add to folder">
                </button>
                @endguest
            </figure>
            @endforeach
        </div>
    </section>
    
    @guest
    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <p>Sign in to save images to your personal folder</p>
            <a href="{{route('login')}}" class="login-btn">Login to Continue</a>
        </div>
    </section>
    @endguest
    
@endsection

@push('js')
<script src="{{asset('content/js/photoswipe.min.js')}}"></script>
<script src="{{asset('content/js/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('content/js/cart.js')}}"></script>

<script>
// PhotoSwipe initialization
var initPhotoSwipeFromDOM = function(gallerySelector) {
    var parseThumbnailElements = function(el) {
        var thumbElements = el.childNodes,
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {
            figureEl = thumbElements[i];
            if(figureEl.nodeType !== 1) continue;
            linkEl = figureEl.children[0];
            size = linkEl.getAttribute('data-size').split('x');
            
            item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10)
            };

            if(figureEl.children.length > 1) {
                item.title = figureEl.querySelector('.image-title')?.textContent || '';
            }

            if(linkEl.children.length > 0) {
                item.msrc = linkEl.children[0].getAttribute('src');
            }

            item.el = figureEl;
            items.push(item);
        }
        return items;
    };

    var closest = function closest(el, fn) {
        return el && (fn(el) ? el : closest(el.parentNode, fn));
    };

    var onThumbnailsClick = function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;
        var clickedListItem = closest(eTarget, function(el) {
            return el.tagName && el.tagName.toUpperCase() === 'FIGURE';
        });

        if(!clickedListItem) return;

        var clickedGallery = clickedListItem.parentNode,
            childNodes = clickedListItem.parentNode.childNodes,
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;

        for (var i = 0; i < numChildNodes; i++) {
            if(childNodes[i].nodeType !== 1) continue;
            if(childNodes[i] === clickedListItem) {
                index = nodeIndex;
                break;
            }
            nodeIndex++;
        }

        if(index >= 0) {
            openPhotoSwipe(index, clickedGallery);
        }
        return false;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        options = {
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),
            getThumbBoundsFn: function(index) {
                var thumbnail = items[index].el.getElementsByTagName('img')[0],
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect();
                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            }
        };

        if(disableAnimation) options.showAnimationDuration = 0;
        options.index = parseInt(index, 10);
        if(isNaN(options.index)) return;

        gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };

    var galleryElements = document.querySelectorAll(gallerySelector);
    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }
};

initPhotoSwipeFromDOM('.gallery-grid');
</script>
@endpush
