@extends('layouts.app')
@push('css')
	<style>
	.m-messenger .m-messenger__form .m-messenger__form-controls .m-messenger__form-input {
		width: 100%;
		padding: 14px 20px;
		border-radius: 25px;
	}
	</style>
@endpush
@section('content')
<!--begin::Portlet-->
<div class="m-portlet">
	<div class="m-portlet__body">
		<div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">
			<div class="m-messenger__messages m-scrollable messages" id="conversation">
				@foreach($chats as $chat)
				<div class="m-messenger__wrapper">
					@php
						$messenger__message = 'm-messenger__message--out';
						if($chat->sender_user_id != Auth::user()->id) {
							$messenger__message = 'm-messenger__message--in';
						}
					@endphp	
					@if($chat->sender_user_id != Auth::user()->id )
						<div class="m-messenger__message m-messenger__message--in">
							<div class="m-messenger__message-pic">
								<img src="{{Profile::picture($chat->sender_user_id)}}" />
							</div>
							<div class="m-messenger__message-body">
								<div class="m-messenger__message-arrow"></div>
								<div class="m-messenger__message-content">
									<div class="m-messenger__message-username">
										{{Profile::getUserName($chat->sender_user_id)}}
									</div>
									<div class="m-messenger__message-text">
										{{$chat->data}}
									</div>
								</div>
							</div>
						</div>
					@else
						<div class="m-messenger__wrapper">
							<div class="m-messenger__message m-messenger__message--out">
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-text">
											{{$chat->data}}
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif
				</div>
				@endforeach
			</div>
			<div class="m-messenger__seperator"></div>
			<input type="hidden" value="1" id="to_user_id" />
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

@endsection
@push('js')
<script src="{{asset('content/js/user-chat.js')}}"></script>
@endpush