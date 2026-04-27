				@php $sub_menu_class = 'm-menu__item--open m-menu__item--expanded'; @endphp
				<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
				<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

					<!-- BEGIN: Aside Menu -->
					<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
						<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
							
							<li class="m-menu__item {{ Route::currentRouteName() == 'admin_dashboard' ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin_dashboard')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-home"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Dashboard</span> </span></span></a>
							</li>
							
							
							<li class="m-menu__item {{ request()->is('admin/settings*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.settings.update')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-cogs"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Settings/ SEO</span> </span></span></a>
							</li>
							
							<li class="m-menu__item {{ request()->is('admin/about-us*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.about_us')}}" class="m-menu__link "><i class="m-menu__link-icon fab fa-telegram-plane"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">About Me</span> </span></span></a>
							</li>
							
							<li class="m-menu__item {{ request()->is('admin/users*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.users')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-users-cog"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Users</span> </span></span></a>
							</li>
							
							<li class="m-menu__item {{ request()->is('admin/sliders*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.sliders')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-sliders-h"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Sliders</span> </span></span></a>
							</li>
							<li class="m-menu__item {{ request()->is('admin/approval*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.approval')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-sliders-h"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Access code</span> </span></span></a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu {{ request()->is('admin/albums*') ? $sub_menu_class : ''}}" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon fa fa-folder-open"></i><span class="m-menu__link-text">Albums</span><i
									 class="m-menu__ver-arrow la la-angle-right"></i></a>
								<div class="m-menu__submenu "><span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
									
										<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Albums</span></span></li>
										
										<li class="m-menu__item {{ request()->is('admin/albums/categories*') ? 'm-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('admin.albums_categories')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text"> Albums Categories</span></a></li>
										
										<li class="m-menu__item {{ Route::currentRouteName() == 'admin.albums' ? '  m-menu__item--active' : '' || Route::currentRouteName() == 'admin.create_albums' ? '  m-menu__item--active' : '' || Route::currentRouteName() == 'admin.edit_albums' ? '  m-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('admin.albums')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text"> Sessions</span></a></li>										
										
									</ul>
								</div>
							</li>
							
							<li class="m-menu__item  m-menu__item--submenu {{ request()->is('admin/posts*') ? $sub_menu_class : ''}}" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon fa fa-newspaper"></i><span class="m-menu__link-text">Journal</span><i
									 class="m-menu__ver-arrow la la-angle-right"></i></a>
								<div class="m-menu__submenu "><span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
									
										<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Journal</span></span></li>
										
										<li class="m-menu__item {{ request()->is('admin/posts/categories*') ? 'm-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('admin.posts_categories')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text"> Journal Categories</span></a></li>
										
										<li class="m-menu__item {{ Route::currentRouteName() == 'admin.posts' ? '  m-menu__item--active' : '' || Route::currentRouteName() == 'admin.create_posts' ? '  m-menu__item--active' : '' || Route::currentRouteName() == 'admin.edit_posts' ? '  m-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('admin.posts')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text"> Journal Posts</span></a></li>										
										
									</ul>
								</div>
							</li>
							
							<li class="m-menu__item {{ request()->is('admin/session-packages*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.packages')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-money-bill"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Sessions Packages</span> </span></span></a>
							</li>
							
							<li class="m-menu__item {{ request()->is('admin/testimonials*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true">
								<a href="{{route('admin.testimonials')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-comments"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Testimonials</span> </span></span></a>
							</li>

							<li class="m-menu__item {{ request()->is('admin/chat-conversation*') ? '  m-menu__item--active' : '' }}" aria-haspopup="true"><a href="{{route('admin.available_group')}}" class="m-menu__link "><i class="m-menu__link-icon flaticon-chat"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Chat</span>
											<!--span class="m-menu__link-badge"><span class="m-badge m-badge--danger">2</span></span--> </span></span></a></li>
						</ul>
					</div>

					<!-- END: Aside Menu -->
				</div>