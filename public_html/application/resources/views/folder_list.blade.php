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
		
		/* Elegant My Folder Styling */
		.folder-hero {
			background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
			padding: 60px 0 40px;
			position: relative;
			overflow: hidden;
		}
		
		.folder-hero::before {
			content: '';
			position: absolute;
			top: -50%;
			right: -20%;
			width: 600px;
			height: 600px;
			background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
			border-radius: 50%;
		}
		
		.folder-hero .page-title {
			text-align: center;
			color: #ffffff;
			font-size: 42px;
			font-weight: 600;
			margin-bottom: 12px;
			position: relative;
			z-index: 1;
		}
		
		.folder-hero .page-subtitle {
			text-align: center;
			color: #a0a0a8;
			font-size: 16px;
			position: relative;
			z-index: 1;
		}
		
		.folder-container {
			background: #0f0f12;
			padding: 40px 0;
			min-height: calc(100vh - 300px);
		}
		
		.folder-layout {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 30px;
			max-width: 1400px;
			margin: 0 auto;
			padding: 0 40px;
		}
		
		.folder-card {
			background: #151519;
			border-radius: 16px;
			border: 1px solid #25252a;
			overflow: hidden;
		}
		
		.folder-card-header {
			background: #1a1a1e;
			padding: 20px 24px;
			border-bottom: 1px solid #25252a;
		}
		
		.folder-card-header h3 {
			color: #ffffff;
			font-size: 20px;
			font-weight: 600;
			margin: 0;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		
		.folder-card-header h3 i {
			color: #d4a65a;
		}
		
		.folder-card-body {
			padding: 24px;
		}
		
		.folder-table {
			width: 100%;
			border-collapse: collapse;
		}
		
		.folder-table th {
			text-align: left;
			padding: 12px 16px;
			color: #a0a0a8;
			font-size: 12px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border-bottom: 1px solid #25252a;
		}
		
		.folder-table td {
			padding: 16px;
			color: #c0c0c8;
			border-bottom: 1px solid #25252a;
		}
		
		.folder-table tr:last-child td {
			border-bottom: none;
		}
		
		.empty-folder {
			text-align: center;
			padding: 60px 20px;
		}
		
		.empty-folder-icon {
			width: 80px;
			height: 80px;
			background: rgba(212, 166, 90, 0.1);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 24px;
			color: #d4a65a;
			font-size: 32px;
		}
		
		.empty-folder h3 {
			color: #ffffff;
			font-size: 24px;
			margin-bottom: 8px;
		}
		
		.empty-folder p {
			color: #a0a0a8;
			font-size: 14px;
			margin-bottom: 24px;
		}
		
		.btn-add-folder {
			display: inline-block;
			background: #d4a65a;
			color: #0a0a0c;
			padding: 14px 28px;
			border-radius: 10px;
			text-decoration: none;
			font-weight: 600;
			transition: all 0.3s ease;
		}
		
		.btn-add-folder:hover {
			background: #e4b66a;
			transform: translateY(-2px);
		}
		
		/* Chat styling */
		.chat-messages {
			max-height: 400px;
			overflow-y: auto;
			padding-right: 10px;
		}
		
		.chat-messages::-webkit-scrollbar {
			width: 6px;
		}
		
		.chat-messages::-webkit-scrollbar-track {
			background: #1a1a1e;
			border-radius: 3px;
		}
		
		.chat-messages::-webkit-scrollbar-thumb {
			background: #d4a65a;
			border-radius: 3px;
		}
		
		.chat-input-wrapper {
			display: flex;
			gap: 12px;
			margin-top: 16px;
			padding-top: 16px;
			border-top: 1px solid #25252a;
		}
		
		.chat-input {
			flex: 1;
			background: #1a1a1e;
			border: 1px solid #25252a;
			border-radius: 25px;
			padding: 12px 20px;
			color: #ffffff;
			font-size: 14px;
		}
		
		.chat-input:focus {
			outline: none;
			border-color: #d4a65a;
		}
		
		.chat-send-btn {
			background: #d4a65a;
			color: #0a0a0c;
			border: none;
			padding: 12px 24px;
			border-radius: 25px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
		}
		
		.chat-send-btn:hover {
			background: #e4b66a;
		}
		
		.alert-success {
			background: rgba(40, 167, 69, 0.1);
			border: 1px solid rgba(40, 167, 69, 0.3);
			color: #28a745;
			padding: 16px 20px;
			border-radius: 10px;
			margin-bottom: 24px;
		}
		
		@media (max-width: 992px) {
			.folder-layout {
				grid-template-columns: 1fr;
				padding: 0 20px;
			}
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
	
	<!-- Elegant Hero -->
	<section class="folder-hero">
		<div class="container">
			<h1 class="page-title">My Folder</h1>
			<p class="page-subtitle">Your saved images and collaborative workspace</p>
		</div>
	</section>
	
	<section class="folder-container">
		@if(session('success'))
		<div class="alert alert-success" style="max-width: 1400px; margin: 0 auto 24px; padding: 0 40px;">
			<strong>Success!</strong> {{session('success')}}
		</div>
		@endif
		
		<div class="folder-layout">
			<!-- User Images Card -->
			<div class="folder-card">
				<div class="folder-card-header">
					<h3><i class="fa fa-image"></i> User Images</h3>
				</div>
				<div class="folder-card-body">
					<table class="folder-table">
						<thead>
							<tr>
								<th>#</th>
								<th>User</th>
								<th>Images</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3">
									<div class="empty-folder">
										<div class="empty-folder-icon">
											<i class="fa fa-folder-open"></i>
										</div>
										<h3>Your folder is empty!</h3>
										<p>You have no items added to your folder yet.</p>
										<a href="{{route('front.albums')}}" class="btn-add-folder">
											<i class="fa fa-plus"></i> Add to Folder
										</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<!-- Chat Card -->
			<div class="folder-card">
				<div class="folder-card-header">
					<h3><i class="fa fa-comments"></i> Chat</h3>
				</div>
				<div class="folder-card-body">
					<div class="chat-messages" id="conversation">
						@foreach($chats as $chat)
							@include('_chat_conversation')
						@endforeach
					</div>
					
					<div class="chat-input-wrapper">
						<input type="hidden" value="1" id="to_user_id" />
						@if(isset($folder_detail))
							@php $to_folder_id = base64_encode($folder_detail->id.'&'.auth::user()->user_name); @endphp
							<input type="hidden" value="{{$to_folder_id}}" name="to_folder" id="to_folder" />
						@else
							<input type="hidden" value="" name="to_folder" id="to_folder" />
						@endif
						<input type="text" class="chat-input chatMessage 1" id="chatMessage1" placeholder="Type a message..." autofocus>
						<button class="chat-send-btn chatButton" id="chatButton1">Send</button>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection

@push('js')
<script src="{{asset('content/js/user-chat.js')}}"></script>
@endpush