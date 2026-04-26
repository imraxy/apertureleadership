@extends('admin.layouts.app')

@section('subheader__title', 'Dashboard')
	
@section('content')
			
	<div class="m-portlet">
		<div class="m-portlet__body m-portlet__body--no-padding">
			<div class="row m-row--no-padding m-row--col-separator-xl">					
				<div class="col-md-12 col-lg-6 col-xl-3">							
					<div class="m-portlet__body">
						<div class="m-widget26">
							<div class="m-widget26__number">
								{{$totalVisitors}}
								<small>Visitors</small>
							</div>										
						</div>
					</div>							
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalAlbumsCategories}}
									<small>Albums's Categories</small>
								</div>										
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalAlbums}}
									<small>Albums</small>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalSliders}}
									<small>Sliders</small>
								</div>										
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="m-portlet">
		<div class="m-portlet__body m-portlet__body--no-padding">
			<div class="row m-row--no-padding m-row--col-separator-xl">					
				<div class="col-md-12 col-lg-6 col-xl-3">							
					<div class="m-portlet__body">
						<div class="m-widget26">
							<div class="m-widget26__number">
								{{$totalJournalCategories}}
								<small>Journal Categories</small>
							</div>										
						</div>
					</div>							
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalJurnalPosts}}
									<small>Jurnal Posts</small>
								</div>										
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalPackages}}
									<small>Packages</small>
								</div>										
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 col-lg-6 col-xl-3">
					<div class="m-portlet--full-height m-portlet--border-bottom-brand">
						<div class="m-portlet__body">
							<div class="m-widget26">
								<div class="m-widget26__number">
									{{$totalTestimonials}}
									<small>Testimonials</small>
								</div>										
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="row">
	
		<div class="col-xl-7">
			<!--begin:: Widgets/Latest Blog-->
			<div class="m-portlet m-portlet--full-height ">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Latest Post
							</h3>
						</div>
					</div>
				</div>
				
				<div class="m-portlet__body">

					<!--begin::Content-->
					<div class="tab-content">
						<div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">

							<!--begin::m-widget5-->
							<div class="m-widget5">
								@foreach($latests_blogs as $blog)
								<div class="m-widget5__item">
									<div class="m-widget5__content">
										{{--
										<div class="m-widget5__pic">
											<img class="m-widget7__img" src="{{asset('application/public/uploads/journals/'.$blog->featured_image)}}" alt="">
										</div>
										--}}
										<div class="m-widget5__section">
											<h4 class="m-widget5__title">
											{{$blog->title}}
											</h4>
											<span class="m-widget5__desc">
												{{-- \Str::limit($blog->description, 100) --}}
												{!! $blog->description !!}
											</span>
											{{--
											<div class="m-widget5__info">
												<span class="m-widget5__author">
													Category :
												</span>
												<span class="m-widget5__info-label">
													{{$blog->blogCategory->name ?? ''}}
												</span>
											</div>
											--}}
										</div>
									</div>
									<div class="m-widget5__content">
										<div class="m-widget5__stats1">
											<span class="m-widget5__number">&nbsp;</span><br>
											<span class="m-widget5__sales">&nbsp;</span>
										</div>
										<div class="m-widget5__stats2">
											<span class="m-widget5__number">&nbsp;</span><br>
											<span class="m-widget5__votes">&nbsp;</span>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							<!--end::m-widget5-->
						</div>
					</div>
					<!--end::Content-->
				</div>
			</div>

		<!--end:: Widgets/Latest Post-->
		</div>
		@if(count($album_sessions))
		<div class="col-xl-5">			
			<div class="m-portlet m-portlet--full-height ">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Latest Session
							</h3>
						</div>
					</div>
				</div>				
				<div class="m-portlet__body">
					<!--begin::Content-->
					<div class="tab-content">
						<div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">

							<!--begin::m-widget5-->
							<div class="m-widget5">
								@foreach($album_sessions as $row_session)
								<div class="m-widget5__item">
									<div class="m-widget5__content">
										<div class="m-widget5__pic">
											<img class="m-widget7__img" src="{{asset('application/public/uploads/albums/'.$row_session->id.'/'.$row_session->featured_image)}}" alt="">
										</div>
										<div class="m-widget5__section">
											<h4 class="m-widget5__title">
											{{$row_session->title}}
											</h4>
											<span class="m-widget5__desc">
												{{\Str::limit($row_session->description, 60)}}
											</span>
											<div class="m-widget5__info">
												<span class="m-widget5__author">
													Category :
												</span>
												<span class="m-widget5__info-label">
													{{$row_session->albumCategory->name ?? ''}}
												</span>
											</div>
										</div>
									</div>
									<div class="m-widget5__content">
										<div class="m-widget5__stats1">
											<span class="m-widget5__number">&nbsp;</span><br>
											<span class="m-widget5__sales">&nbsp;</span>
										</div>
										<div class="m-widget5__stats2">
											<span class="m-widget5__number">&nbsp;</span><br>
											<span class="m-widget5__votes">&nbsp;</span>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							<!--end::m-widget5-->
						</div>
					</div>
					<!--end::Content-->
				</div>
			</div>
		</div>
		@endif
	</div>

@endsection