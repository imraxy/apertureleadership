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
		// console.log("start");
    // parse slide data (url, title, size ...) from DOM elements
    // (children of gallerySelector)
    var parseThumbnailElements = function(el) {
		// console.log(el.childNodes);
        var thumbElements = el.childNodes,
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;
			 

        for(var i = 0; i < numNodes; i++) {
			// console.log("for loop");
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
		 // console.log(e);
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
			// console.log(clickedGallery); 
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
		// console.log("openPhotoSwipe");
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;
			// console.log(galleryElement);
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
			gallery.listen('gettingData', function(index, item) {
					if (item.w < 1 || item.h < 1) { // unknown size
					var img = new Image(); 
					img.onload = function() { // will get size after load
					item.w = this.width; // set image width
					item.h = this.height; // set image height
					   gallery.invalidateCurrItems(); // reinit Items
					   gallery.updateSize(true); // reinit Items
					}
				img.src = item.src; // let's download image
				}
			});
        gallery.init();
		
		
		// Author 
		var totalIN =gallery.options.getNumItemsFn();
		var currentIN= gallery.getCurrentIndex();
		var cur =parseInt(currentIN) + 1	;	
		if(totalIN == cur){
			checkSlide();
			setTimeout(function(){ 
				if(parseInt(totalIN) != $('.album-box').length){
						gallery.close();
						gallery.destroy();
						var allslides= $("#section-bar-all").children()[0];
						openPhotoSwipe( currentIN, allslides );
				}
			}, 1000);
		}
		gallery.listen('afterChange', function() {  
		    var totalINdex =gallery.options.getNumItemsFn();
			var currentINdex= gallery.getCurrentIndex();
 
			  
			var cur =parseInt(currentINdex) + 1	;	
			if(cur == totalINdex || currentINdex == totalINdex){
				gallery.close();
				gallery.destroy();
				checkSlide();
				
				setTimeout(function(){ 
					var allslide= $("#section-bar-all").children()[0];
					openPhotoSwipe( currentINdex, allslide );

				}, 1000);
			}
		});
		
		 
		
		
		
		
		
		
		
		
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

<script>
 	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	
	var start=0;
	
	var slugfind= "{{request('slug')}}";
	var slug=slugfind;
	 
	$.ajax({
        url: "{{url('/getAlbumImage')}}",
        type: "POST",
        data: {start:start,slug:slug},
        enctype: 'multipart/form-data',
		async: false,
    }).done(function(data) {

		//console.log(data.albums);
		var html='';
		var totalveriable= data.albums.length;
		 
		  $.each(data.albums, function(index, value) {
			   
				if ($(".ext"+value.id)[0]){
					alert("alredy ext");
				} else {
						// Do something if class does not exist
					
					var img = new Image();
					img.src = "{{asset('application/public/uploads/albums')}}"+"/"+ value.id + "/" + value.session_image;
					var data = this;
					data.value= value;
					img.onload = function() {
						//console.log(this.width + 'x' + this.height);
						//console.log(data.value.id);
						var maxWidth = 334.64; // Max width for the image
						var maxHeight = 250;    // Max height for the image
						var image_dimension= this.width + 'x' + this.height;
						var url ="{{asset('application/public/uploads/albums')}}/"+data.value.id+'/'+ data.value.session_image;
						var width = this.width;    // Current image width
						var height = this.height;  // Current image height
						 
						var ratio = Math.min(maxWidth / this.width, maxHeight / this.height);
						var w = this.width * ratio ;
						var h =	this.height * ratio;
						
						if($('.album-box').length < 9){
							var status ="block";
						}else{
							var status ="none";
						}
						html = '<figure class="album-box ext'+data.value.id+'"  style="display:'+status+'"  itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"> <a href="'+url+'" itemprop="contentUrl" data-size="'+image_dimension+'"> <img src="'+url+'" itemprop="thumbnail" alt="'+data.value.title+'"  style="width:'+w+'px; height:'+h+'px;" /> </a> <figcaption itemprop="caption description" class="_figcaptiondescription" style="width:'+w+'px;"> <p class="img-title">'+data.value.title+'</p> <div class="_description"></div> <p class="plus-icon toCart addtocartbtn" style="color:#fff;" data-val="'+ data.value.id +'"><img src="{{asset("content/images/folder-plus1.png")}}"> Add to Folder</p> </figcaption> </figure>';
						//console.log(html);
						$(".album-gallery").append(html);
					}
				}
		 });
		 
		 
         
    }); 
	function fatchdata(){
		 
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	
		 
		var slugfind= "{{request('slug')}}";
		var slug=slugfind;
		$.ajax({
			url: "{{url('/getAlbumImage')}}",
			type: "POST",
			data: {start:start,slug:slug},
			enctype: 'multipart/form-data',
			async: false,
		}).done(function(data) {
			  
			if(data.auth== true){
			//console.log(data.albums);
			var html='';
			var totalveriable= data.albums.length;
			var loopcount= 1;
			  $.each(data.albums, function(index, value) {
				  // console.log(value);
				 
					var img = new Image();
					img.src = "{{asset('application/public/uploads/albums')}}"+"/"+ value.id + "/" + value.session_image;
					var data = this;
					data.value= value;
					img.onload = function() {
						//console.log(this.width + 'x' + this.height);
						//console.log(data.value.id);
						var maxWidth = 334.64; // Max width for the image
						var maxHeight = 250;    // Max height for the image
						var image_dimension= this.width + 'x' + this.height;
						var url ="{{asset('application/public/uploads/albums')}}/"+data.value.id+'/'+ data.value.session_image;
						var width = this.width;    // Current image width
						var height = this.height;  // Current image height
						 
						var ratio = Math.min(maxWidth / this.width, maxHeight / this.height);
						var w = this.width * ratio ;
						var h =	this.height * ratio;
						if($('.album-box').length < 9){
							var status ="block";
						}else{
							var status ="none";
						}
						html = '<figure class="album-box ext'+data.value.id+'" style="display:'+status+'" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject"> <a href="'+url+'" itemprop="contentUrl" data-size="'+image_dimension+'"> <img src="'+url+'" itemprop="thumbnail" alt="'+data.value.title+'"  style="width:'+w+'px; height:'+h+'px;" /> </a> <figcaption itemprop="caption description" class="_figcaptiondescription" style="width:'+w+'px;"> <p class="img-title">'+data.value.title+'</p> <div class="_description"></div> <p class="plus-icon toCart addtocartbtn" style="color:#fff;" data-val="'+ data.value.id +'"><img src="{{asset("content/images/folder-plus1.png")}}"> Add to Folder</p> </figcaption> </figure>';
						//console.log(html);
						$(".album-gallery").append(html);
					}
				 
				
			 });
			 
			 
			}
		}); 
	}
	function checkSlide()
		{		
		 	 start +=9; 
			 fatchdata();
	 	}
	
	$( document ).ready(function() {
		 
 
		
		function onScroll(){ 
			if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
				start +=9; 
				fatchdata();
			 }
		}
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$(document.body).on('touchmove', onScroll); // for mobile
			$(window).on('scroll', onScroll); 
		 	
		}else{
		 
			$(window).scroll(function() {
				 
				if($(window).scrollTop() == $(document).height() - $(window).height()) {
				// ajax call get data from server and append to the div
						start +=9; 
						fatchdata();
					 
				}
			});
		}
		
	});
 
</script>

<script type="text/javascript" src="{{asset('content/js/cart.js')}}"></script>
@endpush
