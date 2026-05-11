	<header>
		<section class="ex-aboutsection">
			<div class="container pt-80 pb-80">
				<div class="row">
					<div class="col-sm-12">
						<div class="ex-title">
							<h1>Aperture: Through the Lens Leadership</h1>
							<p>Visual metaphors for leadership and strategy</p>
						</div>
					</div>
				</div>
			</div>
		</section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
					<div class="ex-menu">
                        <nav class="site-header navbar navbar-expand-md navbar-dark" id="banner">
                            <!-- Toggler/collapsibe Button -->
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                             <span class="navbar-toggler-icon"></span>
                          </button>

                            <!-- Navbar links -->
                            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                                <ul class="navbar-nav m-auto">
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::currentRouteName() == 'front.index' ? 'active' : '' }}" href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::currentRouteName() == 'front.about_us' ? 'active' : '' }}" href="{{route('front.about_us')}}">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('posts*') ? 'active' : '' }}" href="{{route('front.posts')}}">Guidelines</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('albums*') ? 'active' : '' }}" href="{{route('front.albums')}}">Albums</a>
                                    </li>                                    
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::currentRouteName() == 'front.contact' ? 'active' : '' }}" href="{{route('front.contact')}}">Contact</a>
                                    </li>
                                    
                                    <!--li class="nav-item">
										<a class="nav-link" href="javascript:;">Buy Now</a>
									</li-->
									@guest
									<li class="nav-item">
										<a class="nav-link {{ Route::currentRouteName() == 'login' ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
									</li>
									
									@else
										
										<li class="nav-item">
											<a class="nav-link {{ Route::currentRouteName() == 'account.folders' ? 'active' : '' }}" href="{{ route('account.folders') }}">My folder</a>
										</li>
										<li class="nav-item dropdown">
											<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
											{{ Auth::user()->name }} <span class="caret"></span>
											</a>

											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
												<a class="dropdown-item" href="{{ route('logout') }}"
												   onclick="event.preventDefault();
																 document.getElementById('logout-form').submit();">
													{{ __('Logout') }}
												</a>

												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
													@csrf
												</form>
											</div>
										</li>	
									@endguest
                                </ul>
                            </div>

                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>