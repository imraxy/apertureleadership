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
    
    .category-filter nav {
        display: block;
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
    
    .category-filter nav ul li {
        display: list-item;
    }
    
    .category-filter nav ul li a {
        display: inline-block;
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
        line-height: 1.4;
        overflow: visible;
    }
    
    .category-filter nav ul li a:hover {
        color: #d4a65a;
        background: rgba(212, 166, 90, 0.1);
        border-color: rgba(212, 166, 90, 0.3);
    }
    
    /* Active/Current Tab - Force Dark Text on Gold Background */
    .category-filter nav ul li.tab-current a,
    .category-filter nav ul li#tab-current a,
    .category-filter nav ul li.tab-current a:link,
    .category-filter nav ul li.tab-current a:visited,
    .category-filter nav ul li.tab-current a:hover,
    .category-filter nav ul li.tab-current a:active,
    section.category-filter nav ul li.tab-current a {
        color: #0a0a0c !important;
        background: #d4a65a !important;
        border-color: #d4a65a !important;
        -webkit-text-fill-color: #0a0a0c !important;
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
    
    /* Masonry Grid Layout - Preserve Aspect Ratios */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }
    
    /* Gallery Item - Maintain Natural Aspect Ratio */
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #1a1a1e;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        height: fit-content;
    }
    
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }
    
    .gallery-item a.photoswipe-trigger {
        display: block;
        width: 100%;
        line-height: 0;
    }
    
    .gallery-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
        /* Remove object-fit: cover to preserve natural aspect ratio */
    }
    
    .gallery-item:hover img {
        transform: scale(1.02);
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
        pointer-events: none;
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
        padding: 8px 16px;
        background: rgba(212, 166, 90, 0.95);
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        z-index: 10;
        pointer-events: auto;
        color: #0a0a0c;
        font-size: 13px;
        font-weight: 600;
    }
    
    .add-to-folder::before {
        content: '+';
        font-size: 16px;
        font-weight: bold;
    }
    
    .gallery-item:hover .add-to-folder {
        opacity: 1;
        transform: scale(1);
    }
    
    .add-to-folder:hover {
        background: #d4a65a;
        transform: scale(1.05) !important;
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
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 0 20px;
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
                @php
                    // Determine which tab is active - default to "people" if no slug
                    $activeSlug = request('slug') ?: 'people';
                @endphp
                
                @foreach($albumcategories as $albumcategory)
                @php
                    $isActive = $activeSlug == $albumcategory->slug;
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
                // Get category name
                $categoryName = $album->albumCategory ? $album->albumCategory->name : 'Uncategorized';
            @endphp
            
            <figure itemprop="associatedMedia" class="gallery-item ext{{$album->id}}" itemscope itemtype="http://schema.org/ImageObject">
                <a href="{{asset('application/public/uploads/albums')}}/{{$album->id}}/{{$album->session_image}}" 
                   itemprop="contentUrl" 
                   data-size="{{$album->width}}x{{$album->height}}"
                   class="photoswipe-trigger">
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
                <button class="add-to-folder toCart addtocartbtn btn btn-primary btn-lg" data-val="{{$album->id}}" title="Click to add this photo to your personal folder" style="padding: 12px 24px; font-size: 16px; font-weight: 600;">
                    + Add to Folder
                </button>
                <small style="display: block; margin-top: 8px; color: #a0a0a8; font-size: 12px; text-align: center;">Click to save to your folder</small>
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
    
    <!--***** PHOTOSWIPE *****-->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
                
                <!-- Add to Folder Button in PhotoSwipe -->
                <div class="pswp__add-to-folder" style="position: absolute; bottom: 80px; right: 20px; z-index: 100;">
                    <button class="pswp-folder-btn" style="background: #d4a65a; color: #0a0a0c; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                        <span style="font-size: 18px; font-weight: bold;">+</span>
                        Add to Folder
                    </button>
                </div>
            </div>
        </div>
    </div>
    
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
            
            linkEl = figureEl.querySelector('.photoswipe-trigger');
            if(!linkEl) continue;
            
            size = linkEl.getAttribute('data-size').split('x');
            
            item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10),
                albumId: figureEl.querySelector('.add-to-folder')?.getAttribute('data-val') || ''
            };

            var titleEl = figureEl.querySelector('.image-title');
            var categoryEl = figureEl.querySelector('.image-category');
            if(titleEl) {
                item.title = '<div style="text-align: center;">' + 
                    '<h3 style="margin: 0 0 8px 0; font-size: 18px;">' + titleEl.textContent + '</h3>' +
                    (categoryEl ? '<span style="color: #d4a65a; font-size: 12px; text-transform: uppercase;">' + categoryEl.textContent + '</span>' : '') +
                    '</div>';
            }

            var imgEl = linkEl.querySelector('img');
            if(imgEl) {
                item.msrc = imgEl.getAttribute('src');
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
        // Don't open PhotoSwipe if clicking the Add to Folder button
        if(e.target.closest('.add-to-folder')) {
            return true;
        }
        
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

    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        options = {
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),
            getThumbBoundsFn: function(index) {
                var thumbnail = items[index].el.querySelector('img'),
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect();
                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },
            addCaptionHTMLFn: function(item, captionEl) {
                if(!item.title) {
                    captionEl.children[0].innerHTML = '';
                    return false;
                }
                captionEl.children[0].innerHTML = item.title;
                return true;
            }
        };

        if(fromURL) {
            if(options.galleryPIDs) {
                for(var j = 0; j < items.length; j++) {
                    if(items[j].pid == index) {
                        options.index = j;
                        break;
                    }
                }
            } else {
                options.index = parseInt(index, 10) - 1;
            }
        } else {
            options.index = parseInt(index, 10);
        }

        if(isNaN(options.index)) return;

        if(disableAnimation) {
            options.showAnimationDuration = 0;
        }

        gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        
        // Update Add to Folder button in PhotoSwipe
        gallery.listen('afterChange', function() {
            var currItem = gallery.currItem;
            var folderBtn = document.querySelector('.pswp-folder-btn');
            if(folderBtn && currItem) {
                folderBtn.setAttribute('data-val', currItem.albumId);
                folderBtn.onclick = function() {
                    // Trigger the add to cart functionality
                    var albumId = this.getAttribute('data-val');
                    if(albumId && typeof addtocart === 'function') {
                        addtocart(albumId);
                    }
                };
            }
        });
        
        gallery.init();
    };

    var galleryElements = document.querySelectorAll(gallerySelector);
    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }

    var hashData = photoswipeParseHash();
    if(hashData.pid && hashData.gid) {
        openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
    }
};

var photoswipeParseHash = function() {
    var hash = window.location.hash.substring(1),
    params = {};

    if(hash.length < 5) {
        return params;
    }

    var vars = hash.split('&');
    for(var i = 0; i < vars.length; i++) {
        if(!vars[i]) continue;
        var pair = vars[i].split('=');
        if(pair.length < 2) continue;
        params[pair[0]] = pair[1];
    }

    if(params.gid) {
        params.gid = parseInt(params.gid, 10);
    }

    return params;
};

// Initialize PhotoSwipe
initPhotoSwipeFromDOM('.gallery-grid');
</script>
@endpush
