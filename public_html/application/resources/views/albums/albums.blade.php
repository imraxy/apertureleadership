@extends('layouts.master')
@push('css')
<style>
.album-box {
margin-top: 27px!important;
}
</style>

<link rel="stylesheet" href="{{asset('content/css/photoswipe.css')}}"> 
<link rel="stylesheet" href="{{asset('content/css/default-skin/default-skin.css')}}">
	
@endpush
@section('content')
    
	<section class="ex-latestsession">
		<div class="container pt-80 pb-65">
			
			<div class="tabs tabs-style-bar">
				<nav>
					<ul>
						@if(request('slug'))
						<li id="tab-current">
						@else
						<li class="tab-current">	
						@endif
							<a href="{{route('front.albums')}}" class="icon icon-home"><span>All</span></a>
						</li>
						@php $i = 0; @endphp
						@foreach($albumcategories as $albumcategory)
						@php 
							$i++; 
							$tab_current_cls = '';
							if(request('slug')==$albumcategory->slug) {
								$tab_current_cls = 'tab-current';
							}	
						@endphp
						<li class="{{$tab_current_cls}}">
							<a href="{{route('front.albums', $albumcategory->slug)}}" class="icon icon-box"><span>{{$albumcategory->name}}</span></a>
						</li>
					@endforeach
					</ul>
				</nav>
				<div class="content-wrap">
					<section id="section-bar-all" class="content-current">
						<div class="row album-gallery" itemscope itemtype="http://schema.org/ImageGallery">
						    @foreach($albums as $album)
						    
					    <?php 
					    $maxWidth = 334.64; // Max width for the image
						$maxHeight = 250;    // Max height for the image
					 
						$width = $album->width;    // Current image width
						$height = $album->height;  // Current image height
						 
						$ratio = min($maxWidth / $width, $maxHeight / $height);
						$w = $width * $ratio ;
						$h = $height * $ratio;
					    
					    ?>
						    
					         <figure itemprop="associatedMedia" class="album-box ext{{$album->id}}" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="{{asset('application/public/uploads/albums')}}/{{$album->id}}/{{$album->session_image}}" itemprop="contentUrl" data-size="{{$album->width}}x{{$album->height}}">
                                    <img src="{{asset('application/public/uploads/albums')}}/{{$album->id}}/{{$album->small_image}}" itemprop="thumbnail" alt="Image description"  loading="lazy" style="width:{{$w}}px; height:{{$h}}px;" />
                                </a>
                               <figcaption itemprop="caption description" class="_figcaptiondescription"  <p class="img-title">{{$album->title}}</p> <div class="_description"></div> <p class="plus-icon toCart addtocartbtn" style="color:#fff;" data-val="{{$album->id}}"><img src="{{asset("content/images/folder-plus1.png")}}"> Add to Folder</p> </figcaption>
                            </figure>
                             @endforeach
						</div>
					</section>
				</div><!-- /content -->
			</div><!-- /tabs -->
			
			@guest
				<div class="col-sm-12">
					<div class="ex-letswork pt-50 pb-50 text-center">
						<a class="ex-btncontact" href="{{route('login')}}">Login</a>
					</div>
				</div>
			@endif
		</div>
	</section>
	
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
								<div class="pswp__preloader__donut">
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip">
					</div>
				</div>

				<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
				<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

				<div class="pswp__caption">
					<div class="pswp__caption__center"> </div>
				</div>

			</div>
		</div>
	</div>
	<input type="hidden" class="userid" value="1" />

@endsection

@push('js')

<script src="{{asset('content/js/photoswipe.min.js')}}"></script> 
<!-- UI JS file -->
<script src="{{asset('content/js/photoswipe-ui-default.min.js')}}"></script>	
	<script>
var initPhotoSwipeFromDOM = function(gallerySelector) {

    // parse slide data (url, title, size ...) from DOM elements 
    // (children of gallerySelector)
    var parseThumbnailElements = function(el) {
        var thumbElements = el.childNodes,
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {

            figureEl = thumbElements[i]; // <figure> element

            // include only element nodes 
            if(figureEl.nodeType !== 1) {
                continue;
            }

            linkEl = figureEl.children[0]; // <a> element

            size = linkEl.getAttribute('data-size').split('x');

            // create slide object
            item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10)
            };



            if(figureEl.children.length > 1) {
                // <figcaption> content
                item.title = figureEl.children[1].innerHTML; 
            }

            if(linkEl.children.length > 0) {
                // <img> thumbnail element, retrieving thumbnail url
                item.msrc = linkEl.children[0].getAttribute('src');
            } 

            item.el = figureEl; // save link to element for getThumbBoundsFn
            items.push(item);
        }

        return items;
    };

    // find nearest parent element
    var closest = function closest(el, fn) {
        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
    };

    // triggers when user clicks on thumbnail
    var onThumbnailsClick = function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;

        // find root element of slide
        var clickedListItem = closest(eTarget, function(el) {
            return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
        });

        if(!clickedListItem) {
            return;
        }

        // find index of clicked item by looping through all child nodes
        // alternatively, you may define index via data- attribute
        var clickedGallery = clickedListItem.parentNode,
            childNodes = clickedListItem.parentNode.childNodes,
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;

        for (var i = 0; i < numChildNodes; i++) {
            if(childNodes[i].nodeType !== 1) { 
                continue; 
            }

            if(childNodes[i] === clickedListItem) {
                index = nodeIndex;
                break;
            }
            nodeIndex++;
        }



        if(index >= 0) {
            // open PhotoSwipe if valid index found
            openPhotoSwipe( index, clickedGallery );
        }
        return false;
    };

    // parse picture index and gallery index from URL (#&pid=1&gid=2)
    var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
        params = {};

        if(hash.length < 5) {
            return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
            if(!vars[i]) {
                continue;
            }
            var pair = vars[i].split('=');  
            if(pair.length < 2) {
                continue;
            }           
            params[pair[0]] = pair[1];
        }

        if(params.gid) {
            params.gid = parseInt(params.gid, 10);
        }

        return params;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        // define options (if needed)
        options = {

            // define gallery index (for URL)
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

            getThumbBoundsFn: function(index) {
                // See Options -> getThumbBoundsFn section of documentation for more info
                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            }

        };

        // PhotoSwipe opened from URL
        if(fromURL) {
            if(options.galleryPIDs) {
                // parse real index when custom PIDs are used 
                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                for(var j = 0; j < items.length; j++) {
                    if(items[j].pid == index) {
                        options.index = j;
                        break;
                    }
                }
            } else {
                // in URL indexes start from 1
                options.index = parseInt(index, 10) - 1;
            }
        } else {
            options.index = parseInt(index, 10);
        }

        // exit if index not found
        if( isNaN(options.index) ) {
            return;
        }

        if(disableAnimation) {
            options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };

    // loop through all gallery elements and bind events
    var galleryElements = document.querySelectorAll( gallerySelector );

    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }

    // Parse URL and open gallery if it contains #&pid=3&gid=1
    var hashData = photoswipeParseHash();
    if(hashData.pid && hashData.gid) {
        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
    }
};

// execute above function

initPhotoSwipeFromDOM('.album-gallery');
</script>

 
<script type="text/javascript" src="{{asset('content/js/cart.js')}}"></script>
@endpush
