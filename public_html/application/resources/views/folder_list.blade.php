@extends('layouts.master')

@push('css')
	<!--link rel="stylesheet" href="{{ asset('content/css/chat.css') }}"-->
	<!--begin::Global Theme Styles -->
	<link href="{{asset('content/css/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{asset('content/css/vendors/base/style.bundle.css')}}">
	<link href="{{asset('content/owlCarousal.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('content/owltheme.css')}}" rel="stylesheet" type="text/css" />
	
	<style>
		.m-messenger .m-messenger__form .m-messenger__form-controls .m-messenger__form-input {
			width: 100%;
			padding: 14px 20px;
			border-radius: 25px;
		}
		
		h3._color {
			color: #000;
		}
	 
 
		.themeofowl .item{
		  margin: 3px;
		}
		.themeofowl .item img{
		  display: block;
		  width: 100%;
		  height: auto;
		}
	</style>
@endpush

@push('js')
	<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script src="{{asset('content/owl.js')}}"></script>
 
 
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

	<!--end::Web font -->
	
	
@endpush
@section('content')
	
<div class="container-fluid">
	@if(session('success'))
	<div class="alert alert-success">
		<strong>Success!</strong> {{session('success')}}
	</div>
	@endif
	
	<div class="row">
							
		<div class="col-md-6">
			<!--begin::Portlet-->
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								User Images
							</h3>
						</div>
					</div>
				</div>
				
				<div class="m-portlet__body">
					<!--begin::Section-->
					<div class="m-section">
						<div class="m-section__content">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>#</th>
											<th>User</th>
											<th>images</th>
											<!-- <th>Description</th> -->
											<!-- <th>Actions</th> -->
										</tr>
									</thead>
									<tbody class="tbody">
									 
									 <tr class="emptyimage">
											<td colspan="5">
												<div class="panel-body" style="text-align: center; padding: 80px;">	 	 
													<h3>Your folder is empty!</h3>
													<span style="font-size: 12px;">You have no items added in the folder.</span>
													<p></p>
													<p><a href="{{route('front.albums')}}" class="ex-btncontact">Add to folder</a></p>
												</div>
											</td>	
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--end::Section-->
				</div>
			</div>
			<!--end::Portlet-->
		</div>
							
		<div class="col-md-6">
			<!--begin::Portlet-->
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Chat
							</h3>
						</div>
					</div>
				</div>
				
				<div class="m-portlet__body">
					<!--begin::Section-->
					<div class="m-section">											
						<div class="m-section__content">
							<div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">
								 
								<div class="m-messenger__messages m-scrollable messages" id="conversation">
								@foreach($chats as $chat)
									@include('_chat_conversation')
								@endforeach
								</div>
								<div class="m-messenger__seperator"></div>
								<input type="hidden" value="1" id="to_user_id" />
								@if(isset($folder_detail))
									@php $to_folder_id = base64_encode($folder_detail->id.'&'.auth::user()->user_name); @endphp
									<input type="hidden" value="{{$to_folder_id}}" name="to_folder" id="to_folder" />
								@else
									<input type="hidden" value="" name="to_folder" id="to_folder" />
								@endif
								<div class="m-messenger__form">
									<div class="m-messenger__form-controls message-input">
										<input type="text" class="m-messenger__form-input chatMessage 1" id="chatMessage1" placeholder="Type a message" autofocus> 
									</div>
									<div class="m-messenger__form-tools">
										<button class="btn btn-default submit chatButton" id="chatButton1" style="display:none;">Send</button>	
									</div>
								</div>
								 
							</div>
						</div>
					</div>
					<!--end::Section-->
				</div>
			</div>
			<!--end::Portlet-->
		</div>
	</div>
		
</div>

@endsection

@push('js')
<script src="{{asset('content/js/user-chat.js')}}"></script>
@endpush