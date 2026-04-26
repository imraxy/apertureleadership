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
                                <h1 class="title"> The standard Lorem Ipsum passage</h1>
                            </div>
                            <div class="slide-breadcrumb-wrapper">
                                <span>
                                    <a title="Homepage" href="#"><i class="fa fa-home"></i>&nbsp;&nbsp;Home</a>
                                </span>
                                <span class="aperture-bread-sep">&nbsp; | &nbsp;</span>
                                <span>
                                    <a title="Journal" href="#">&nbsp;&nbsp;Journal</a>
                                </span>
                                <span class="aperture-bread-sep">&nbsp; | &nbsp;</span>
                                <span> The standard Lorem Ipsum passage</span>
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
        		<div class="col-md-12 col-sm-12">
        		    <div class="journal-detail-block">
        		        <div class="j-detail-thumbnail">
                            <img src="{{asset('application/public/uploads/journals/'.$post->featured_image)}}" alt="{{$post->title}}">
                        </div>
                        <div class="jorrnal-detail-content">
                            <div class="j-detail-title">
                                <h3 class="title mb0 mt20">{{$post->title}}</h3>
                            </div>
                            <div class="j-blog-date-coun j-detail-inline">
                                <span class=""><i class="fa fa-folder-o"></i>{{$post->blogCategory->name ?? ''}}</span>
                                <span class=""><i class="fa fa-calendar"></i>{{\Carbon\Carbon::parse($post->created_at)->format('M d,Y')}}</span>
                                <span class=""><i class="fa fa-eye"></i> {{$post->no_of_views ?? 0}}</span>
                            </div>
                            <div class="j-detail-para">
        						{!!$post->description!!}
                            </div>
                        </div>
                    </div>
                </div>
        	</div>
        </div>
    </section>
@endsection

@push('js')

@endpush
