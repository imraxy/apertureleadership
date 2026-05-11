@extends('layouts.master')
@push('css')
<style>

</style>
@endpush
@section('content')
    <!-- BannerMain -->
    <section class="main-banner">
       <div class="banner-inn">
			<img src="{{asset('content/images/testimonials-bg.jpg')}}" class="img-responsive" title="contact" alt="contact image">
        </div>
		<div class="aperture-page-title">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="slide-title-box">
                            <div class="page-title-heading">
                                <h1 class="title"> Guidelines</h1>
                            </div>
                            <div class="slide-breadcrumb-wrapper">
                                <p class="contact-text">
                                    
                                </p>
                            </div>  
                        </div>
                    </div>
                </div> 
            </div>                    
        </div>
    </section>

    <!-- content related to page -->
	<section class="ex-aboutsection">
        <div class="container pt-80 pb-80">
            <div class="row">
        		
				<div class="col-md-3">
        		    <div class="journal-categories">
          
            		    <ul class="nav nav-tabs tabs-left sideways">
            		        @php $i = 0; @endphp
							@foreach($posts as $row_tab)
							@php $i++; @endphp
							<li>
            		            <a href="#m-tab-{{$i}}" class="nav-link @if($i==1) active @endif" data-toggle="tab">
                		            <div class="cat-list-block d-flex justify-content-between">
										<span class="cat-name"> {{$row_tab->title}} </span>
                		            </div>
            		            </a>
            		        </li>
							@endforeach
            		    </ul>
                    </div>
        		</div>
				
        		<div class="col-md-9">
        		    <div class="grid-blogs tab-content">
						@php $s = 0; @endphp
						@foreach($posts as $row_post)
						@php $s++; @endphp
        		        <div class="tab-pane @if($s==1) active @endif" id="m-tab-{{$s}}">
            		        <div class="row">
								<div class="col-md-12">
									<div class="journal-detail-block">
										<div class="jorrnal-detail-content">
											<div class="j-detail-title">
												<h3 class="title mb0 mt20">{{$row_post->title}}</h3>
											</div>
											<div class="j-detail-para">
												{!!$row_post->description!!}
											</div>
										</div>
									</div>
								</div>
							</div>
            		    </div>
            		    @endforeach
					</div>
        		</div>
        	</div>
        </div>
    </section>
	
@endsection

