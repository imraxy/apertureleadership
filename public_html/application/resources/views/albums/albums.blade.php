@extends('layouts.master')
@push('css')
<style>
.album-box {
	margin-top: 27px !important;
	position: relative;
}

.album-box .photoswipe-trigger {
	display: block;
}

/* Visible Add to Folder on gallery tiles */
.album-gallery .add-to-folder {
	position: absolute;
	top: 12px;
	right: 12px;
	z-index: 5;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	min-width: 120px;
	height: 38px;
	padding: 0 14px;
	background: rgba(212, 166, 90, 0.95);
	color: #1a1a1a !important;
	border: none;
	border-radius: 20px;
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
	opacity: 0;
	transform: scale(0.92);
	transition: opacity 0.2s ease, transform 0.2s ease, background 0.2s ease;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
	pointer-events: auto;
}

.album-gallery .album-box:hover .add-to-folder {
	opacity: 1;
	transform: scale(1);
}

.album-gallery .add-to-folder:hover {
	background: #d4a65a;
	transform: scale(1.05) !important;
}

.album-gallery .add-to-folder img {
	width: 16px;
	height: 16px;
	filter: brightness(0);
	pointer-events: none;
}

/* Hide legacy figcaption button (was display:none in style.css anyway) */
.album-gallery ._figcaptiondescription p.addtocartbtn {
	display: none !important;
}

/* PhotoSwipe Add to Folder */
.pswp__add-to-folder-wrap {
	position: absolute;
	bottom: 72px;
	right: 20px;
	z-index: 1200;
}

.pswp-folder-btn {
	display: flex;
	align-items: center;
	gap: 8px;
	background: #d4a65a;
	color: #1a1a1a !important;
	padding: 12px 20px;
	border: none;
	border-radius: 8px;
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
}

.pswp-folder-btn:hover {
	background: #e4b66a;
}

.pswp-folder-btn img {
	width: 18px;
	height: 18px;
	filter: brightness(0);
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
						@php
							$activeSlug = $slug ?: 'people';
						@endphp
						@foreach($albumcategories as $albumcategory)
						@php
							$tab_current_cls = ($activeSlug === $albumcategory->slug) ? 'tab-current' : '';
						@endphp
						<li class="{{ $tab_current_cls }}">
							<a href="{{ route('front.albums', $albumcategory->slug) }}" class="icon icon-box"><span>{{ $albumcategory->name }}</span></a>
						</li>
						@endforeach
					</ul>
				</nav>
				<div class="content-wrap">
					<section id="section-bar-all" class="content-current">
						<div class="row album-gallery" itemscope itemtype="http://schema.org/ImageGallery">
							@foreach($albums as $album)
							@php
								$maxWidth = 334.64;
								$maxHeight = 250;
								$width = $album->width;
								$height = $album->height;
								$ratio = min($maxWidth / $width, $maxHeight / $height);
								$w = $width * $ratio;
								$h = $height * $ratio;
							@endphp

							<figure itemprop="associatedMedia" class="album-box ext{{ $album->id }}" itemscope itemtype="http://schema.org/ImageObject">
								<a href="{{ asset('application/public/uploads/albums') }}/{{ $album->id }}/{{ $album->session_image }}"
									itemprop="contentUrl"
									data-size="{{ $album->width }}x{{ $album->height }}"
									class="photoswipe-trigger">
									<img src="{{ asset('application/public/uploads/albums') }}/{{ $album->id }}/{{ $album->small_image }}"
										itemprop="thumbnail"
										alt="{{ $album->title }}"
										loading="lazy"
										style="width:{{ $w }}px; height:{{ $h }}px;">
								</a>
								<figcaption itemprop="caption description" class="_figcaptiondescription">
									<p class="img-title">{{ $album->title }}</p>
								</figcaption>
								@auth
								<button type="button"
									class="add-to-folder addtocartbtn"
									data-val="{{ $album->id }}"
									title="Add to Folder">
									<img src="{{ asset('content/images/folder-plus1.png') }}" alt="">
									Add to Folder
								</button>
								@endauth
							</figure>
							@endforeach
						</div>
					</section>
				</div>
			</div>

			@guest
			<div class="col-sm-12">
				<div class="ex-letswork pt-50 pb-50 text-center">
					<p class="mb-3">Sign in to save images to your personal folder.</p>
					<a class="ex-btncontact" href="{{ route('login') }}">Login</a>
				</div>
			</div>
			@endguest
		</div>
	</section>

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

				@auth
				<div class="pswp__add-to-folder-wrap">
					<button type="button" class="pswp-folder-btn addtocartbtn" data-val="" title="Add to Folder">
						<img src="{{ asset('content/images/folder-plus1.png') }}" alt="">
						Add to Folder
					</button>
				</div>
				@endauth
			</div>
		</div>
	</div>

	<input type="hidden" class="userid" value="{{ auth()->id() ?? 0 }}" />

@endsection

@push('js')
<script src="{{asset('content/js/photoswipe.min.js')}}"></script>
<script src="{{asset('content/js/photoswipe-ui-default.min.js')}}"></script>
<script type="text/javascript" src="{{asset('content/js/cart.js')}}"></script>
<script>
var initPhotoSwipeFromDOM = function(gallerySelector) {

	var parseThumbnailElements = function(el) {
		var thumbElements = el.childNodes,
			numNodes = thumbElements.length,
			items = [],
			figureEl,
			linkEl,
			size,
			item;

		for (var i = 0; i < numNodes; i++) {
			figureEl = thumbElements[i];
			if (figureEl.nodeType !== 1) {
				continue;
			}

			linkEl = figureEl.querySelector('.photoswipe-trigger') || figureEl.children[0];
			if (!linkEl || !linkEl.getAttribute('href')) {
				continue;
			}

			size = linkEl.getAttribute('data-size').split('x');

			var folderBtn = figureEl.querySelector('.add-to-folder');
			item = {
				src: linkEl.getAttribute('href'),
				w: parseInt(size[0], 10),
				h: parseInt(size[1], 10),
				albumId: folderBtn ? folderBtn.getAttribute('data-val') : ''
			};

			var titleEl = figureEl.querySelector('.img-title');
			if (titleEl) {
				item.title = titleEl.textContent;
			}

			var imgEl = linkEl.querySelector('img') || linkEl.getElementsByTagName('img')[0];
			if (imgEl) {
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
		if (e.target.closest('.add-to-folder')) {
			return true;
		}

		e = e || window.event;
		e.preventDefault ? e.preventDefault() : e.returnValue = false;

		var eTarget = e.target || e.srcElement;
		var clickedListItem = closest(eTarget, function(el) {
			return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		});

		if (!clickedListItem) {
			return;
		}

		var clickedGallery = clickedListItem.parentNode,
			childNodes = clickedListItem.parentNode.childNodes,
			numChildNodes = childNodes.length,
			nodeIndex = 0,
			index;

		for (var i = 0; i < numChildNodes; i++) {
			if (childNodes[i].nodeType !== 1) {
				continue;
			}
			if (childNodes[i] === clickedListItem) {
				index = nodeIndex;
				break;
			}
			nodeIndex++;
		}

		if (index >= 0) {
			openPhotoSwipe(index, clickedGallery);
		}
		return false;
	};

	var photoswipeParseHash = function() {
		var hash = window.location.hash.substring(1),
			params = {};

		if (hash.length < 5) {
			return params;
		}

		var vars = hash.split('&');
		for (var i = 0; i < vars.length; i++) {
			if (!vars[i]) {
				continue;
			}
			var pair = vars[i].split('=');
			if (pair.length < 2) {
				continue;
			}
			params[pair[0]] = pair[1];
		}

		if (params.gid) {
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

		options = {
			galleryUID: galleryElement.getAttribute('data-pswp-uid'),
			getThumbBoundsFn: function(index) {
				var thumbnail = items[index].el.querySelector('img'),
					pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
					rect = thumbnail.getBoundingClientRect();

				return { x: rect.left, y: rect.top + pageYScroll, w: rect.width };
			}
		};

		if (fromURL) {
			if (options.galleryPIDs) {
				for (var j = 0; j < items.length; j++) {
					if (items[j].pid == index) {
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

		if (isNaN(options.index)) {
			return;
		}

		if (disableAnimation) {
			options.showAnimationDuration = 0;
		}

		gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);

		var syncPswpFolderBtn = function() {
			var folderBtn = document.querySelector('.pswp-folder-btn');
			if (!folderBtn || !gallery.currItem) {
				return;
			}
			var albumId = gallery.currItem.albumId || '';
			folderBtn.setAttribute('data-val', albumId);
		};

		gallery.listen('afterChange', syncPswpFolderBtn);
		gallery.listen('initialZoomInEnd', syncPswpFolderBtn);

		gallery.init();
	};

	var galleryElements = document.querySelectorAll(gallerySelector);

	for (var i = 0, l = galleryElements.length; i < l; i++) {
		galleryElements[i].setAttribute('data-pswp-uid', i + 1);
		galleryElements[i].onclick = onThumbnailsClick;
	}

	var hashData = photoswipeParseHash();
	if (hashData.pid && hashData.gid) {
		openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
	}
};

initPhotoSwipeFromDOM('.album-gallery');
</script>
@endpush
