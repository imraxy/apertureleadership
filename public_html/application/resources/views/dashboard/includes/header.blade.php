		@php $admin_detail = Auth::user(); @endphp
			<header id="m_header" class="m-grid__item m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">

						<!-- BEGIN: Brand -->
						<div class="m-stack__item m-brand  m-brand--skin-dark ">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="{{route('admin_dashboard')}}" class="m-brand__logo-wrapper _barnd_title">
										@if(config('settings.site_title'))
											{{ config('settings.site_title') }}
										@else
											Aperture
										@endif
									</a>
								</div>
								
								<div class="m-stack__item m-stack__item--middle m-brand__tools">

									<!-- BEGIN: Left Aside Minimize Toggle -->
									<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
										<span></span>
									</a>

									<!-- END -->

									<!-- BEGIN: Responsive Aside Left Menu Toggler -->
									<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>

									<!-- END -->
									
									<!-- BEGIN: Topbar Toggler -->
									<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
										<i class="flaticon-more"></i>
									</a>

									<!-- BEGIN: Topbar Toggler -->
								</div>
							</div>
						</div>

						<!-- END: Brand -->
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<!-- BEGIN: Topbar -->
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">
										
										<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
										 m-dropdown-toggle="click">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												@if(!empty($admin_detail->avatar) && File::exists('application/public/uploads/users/'.$admin_detail->avatar))
												<span class="m-topbar__userpic">
													<img src="{{asset('application/public/uploads/users/'.$admin_detail->avatar)}}" alt="{{ $admin_detail->name ? : ''  }}" class="m--img-rounded m--marginless" />
												</span>
												@endif
												<span class="m-topbar__username m--{{$admin_detail->avatar ? 'hide' : '' }}" style="color:#282a3c !important;">
													{{$admin_detail->name}} 
												</span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url({{asset('content/assets/app/media/img/misc/user_profile_bg.jpg')}}); background-size: cover;">
														<div class="m-card-user m-card-user--skin-dark">
															@if($admin_detail->avatar && File::exists('application/public/uploads/users/'.$admin_detail->avatar))
															<div class="m-card-user__pic">
																<img src="{{asset('application/public/uploads/users/'.$admin_detail->avatar)}}" alt="{{ $admin_detail->name ? : ''  }}" class="m--img-rounded m--marginless" alt="" />
															</div>
															@endif
															<div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500">{{$admin_detail->name}}</span>
																<a href="" class="m-card-user__email m--font-weight-300 m-link">{{$admin_detail->email}}</a>
															</div>
														</div>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">
															<ul class="m-nav m-nav--skin-light">
																<li class="m-nav__section m--hide">
																	<span class="m-nav__section-text">Section</span>
																</li>
																
																
																<li class="m-nav__item">
																	<a href="{{url('/')}}" class="m-nav__link" target="_blank">
																		<i class="m-nav__link-icon flaticon-visible"></i>
																		<span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																				<span class="m-nav__link-text">View Site</span>
																			</span>
																		</span>
																	</a>
																</li>
																<li class="m-nav__separator m-nav__separator--fit">
																</li>
																<li class="m-nav__item">
																	<a class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder" href="{{ route('logout') }}"
																	   onclick="event.preventDefault();
																					 document.getElementById('logout-form').submit();">
																		{{ __('Logout') }}
																	</a>
																	
																	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
																		@csrf
																	</form>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<!-- END: Topbar -->
						</div>
					</div>
				</div>
			</header>