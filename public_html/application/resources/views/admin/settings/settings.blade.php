@extends('admin.layouts.app')

@section('subheader__title', 'Setting and SEO')

@section('content')

		
	<div class="row">
		
		<div class="col-lg-12">
			
			<div class="m-portlet m-portlet--full-height m-portlet--tabs">
			
				<div class="m-portlet__head">
				
					<div class="m-portlet__head-tools">
					
						<ul class="nav nav-tabs m-tabs-line m-tabs-line--success" role="tablist">
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_7_1" role="tab"><i class="fa fa-cogs"></i> Setting</a>
							</li>
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link " data-toggle="tab" href="#m_tabs_7_2" role="tab"><i class="fa fa-chart-line"></i> SEO</a>
							</li>
							
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link " data-toggle="tab" href="#m_tabs_7_3" role="tab"><i class="fa fa-envelope"></i> Contact Info</a>
							</li>
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link " data-toggle="tab" href="#m_tabs_7_4" role="tab"><i class="fa fa-share-alt"></i> Social Media Acc.</a>
							</li>
						</ul>
					</div>
				</div>
				
				<div class="tab-content">
				
					<div class="tab-pane active" id="m_tabs_7_1" role="tabpanel">
						@include('admin.settings.includes.general_settings')														
					</div>
					
					<div class="tab-pane " id="m_tabs_7_2" role="tabpanel">
						@include('admin.settings.includes.seo')
					</div>
					
					<div class="tab-pane " id="m_tabs_7_3" role="tabpanel">
						@include('admin.settings.includes.contact_info')
					</div>
					
					<div class="tab-pane " id="m_tabs_7_4" role="tabpanel">
						@include('admin.settings.includes.social_media_acc')
					</div>
					
				</div>
					
				
			</div>
		</div>
			
	</div>

@endsection

@push('js')

    <script>
		
		//Tab active if validation faild
		function activeTab(tab) {
			$('.nav-tabs a[href="#' + tab + '"]').tab('show');
		};
		
		/* Social media acc tab active if validation faild */
		@if($errors->has('facebook') || $errors->has('twitter') || $errors->has('instagram') || $errors->has('youtube'))
			
			$(document).ready(function() {
				activeTab('m_tabs_7_4');
			});
			
		@endif
		
		/* Contact Info tab active if validation faild */
		@if($errors->has('webmaster_email') || $errors->has('address') || $errors->has('phone') || $errors->has('whatsapp') || $errors->has('skype'))
			
			$(document).ready(function() {
				activeTab('m_tabs_7_3');
			});
			
		@endif
		
		/* Contact Info tab active if validation faild */
		@if($errors->has('meta_keywords') || $errors->has('meta_description'))
			
			$(document).ready(function() {
				activeTab('m_tabs_7_2');
			});
			
		@endif
		
		/* Setting tab active if validation faild */
		@if($errors->has('site_title') || $errors->has('copyright') || $errors->has('disqus_username') || $errors->has('site_logo') || $errors->has('site_favicon'))
						
			$(document).ready(function() {
				activeTab('m_tabs_7_1');
			});
			
		@endif

        
    </script>
@endpush