@extends('admin.layouts.app')

@section('subheader__title', 'Dashboard')
@push('css')
	<style>
		
		$blue: #74b9ff;

		body {
		  background-image: url('https://images.unsplash.com/photo-1560568082-c15188aa6510?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=3300&q=80');
		  -webkit-font-smoothing: antialiased;
		  -moz-osx-font-smoothing: grayscale;
		  text-rendering: optimizeLegibility;
		}

		.container {
		  margin: 60px auto;
		  background: #fff;
		  padding: 0;
		  border-radius: 7px;
		}

		.profile-image {
		  width: 50px;
		  height: 50px;
		  border-radius: 40px;
		}

		.settings-tray {
		  background: #eee;
		  padding: 10px 15px;
		  border-radius: 7px;
		  
		  .no-gutters {
			padding: 0;
		  }
		  
		  &--right {
			float: right;
			
			i {
			  margin-top: 10px;
			  font-size: 25px;
			  color: grey;
			  margin-left: 14px;
			  transition: .3s;
			  
			  &:hover {
				color: $blue;
				cursor: pointer;
			  }
			}
		  }
		}

		.search-box {
		  background: #fafafa;
		  padding: 10px 13px;
		  
		  .input-wrapper {
			background: #fff;
			border-radius: 40px;
			
			i {
			  color: grey;
			  margin-left: 7px; 
			  vertical-align: middle;
			}
		  }
		}

		input {
		  border: none;
		  border-radius: 30px;
		  width: 80%;

		  &::placeholder {
			color: #e3e3e3;
			font-weight: 300;
			margin-left: 20px;
		  }

		  &:focus {
			outline: none;
		  }
		}

		.friend-drawer {
		  padding: 10px 15px;
		  display: flex;
		  vertical-align: baseline;
		  background: #fff;
		  transition: .3s ease;
		  
		  &--grey {
			background: #eee;
		  }
		  
		  .text {
			margin-left: 12px;
			width: 70%;
			
			h6 {
			  margin-top: 6px;
			  margin-bottom: 0;
			}
			
			p {
			  margin: 0;
			}
		  }
		  
		  .time {
			color: grey;
		  }
		  
		  &--onhover:hover {
			background: $blue;
			cursor: pointer;
			
			p,
			h6,
			.time {
			  color: #fff !important;
			}
		  }
		}

		hr {
		  margin: 5px auto;
		  width: 60%;
		}

		.chat-bubble {
		  padding: 10px 14px;
		  background: #eee;
		  margin: 10px 30px;
		  border-radius: 9px;
		  position: relative;
		  animation: fadeIn 1s ease-in;
		  
		  &:after {
			content: '';
			position: absolute;
			top: 50%;
			width: 0;
			height: 0;
			border: 20px solid transparent;
			border-bottom: 0;
			margin-top: -10px;
		  }
		  
		  &--left {
			 &:after {
			  left: 0;
			  border-right-color: #eee;
				border-left: 0;
			  margin-left: -20px;
			}
		  }
		  
		  &--right {
			&:after {
			  right: 0;
			  border-left-color: $blue;
				border-right: 0;
			  margin-right: -20px;
			}
		  }
		}

		@keyframes fadeIn {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
			}
		}


		.offset-md-9 {
		  .chat-bubble {
			background: $blue;
			color: #fff;
		  }
		}

		.chat-box-tray {
		  background: #eee;
		  display: flex;
		  align-items: baseline;
		  padding: 10px 15px;
		  align-items: center;
		  margin-top: 19px;
		  bottom: 0;
		  
		  input {
			margin: 0 10px;
			padding: 6px 2px;
		  }
		  
		  i {
			color: grey;
			font-size: 30px;
			vertical-align: middle;
			
			&:last-of-type {
			  margin-left: 25px;
			}
		  }
		}
		
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
	  <div class="row">
		
		<div class="col-md-4 border-right">
			@foreach($users as $user)
			<a href="{{route('admin.userFolders', ['user_id' => $user->id, 'user_name' => $user->user_name,'group_no' => base64_encode($group_no)])}}">
				<div class="friend-drawer friend-drawer--onhover">
					@if(!empty($user->avatar) && File::exists('application/public/uploads/users/'.$user->avatar))
					<img class="profile-image" src="{{asset('application/public/uploads/users/'.$user->avatar)}}" alt="">
					<div class="text">
					  <h6>{{$user->name}}</h6>
						  {{--<p class="text-muted">Hey, you're arrested!</p> --}}
					</div>
					{{--<span class="time text-muted small">13:21</span>--}}
					@else
					@php $user_name = $user->name; @endphp	
					<span style="width: 200px;">
						<div class="m-card-user m-card-user--sm">
							<div class="m-card-user__pic">
								<div class="m-card-user__no-photo m--bg-fill-success">
									<span>{{$user_name[0]}}</span>
								</div>
							</div>
							<div class="m-card-user__details">
								<span class="m-card-user__name">{{Str::ucfirst($user->user_name)}}</span>
									{{--<p class="text-muted">Hey, you're arrested!</p>
								<span class="time text-muted small">13:21</span>--}}
							</div>
						</div>
					</span>
					@endif
				</div>
			</a>	
			<hr>
			@endforeach
		</div>
		
		<div class="col-md-8">

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
										<div class="m-messenger__wrapper">
										@php
											$messenger__message = 'm-messenger__message--out';
											if($chat->sender_user_id != Auth::guard('admin')->user()->id) {
												$messenger__message = 'm-messenger__message--in';
											}
										@endphp	
										@if($chat->sender_user_id != Auth::guard('admin')->user()->id)
											<div class="m-messenger__message {{$messenger__message}}">
												@if($chat->sender_user_id != Auth::guard('admin')->user()->id)
												<div class="m-messenger__message-pic">
													<img src="{{Profile::picture($chat->sender_user_id)}}" alt="" />
												</div>
												@endif
												<div class="m-messenger__message-body">
													<div class="m-messenger__message-arrow"></div>
													<div class="m-messenger__message-content">
														<div class="m-messenger__message-username">
															{{Profile::getUserName($chat->sender_user_id)}}
														</div>
														<div class="m-messenger__message-text">
															{{$chat->msg}}
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
																{{$chat->msg}}
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
									
									<input type="hidden" id="access_code" value="{{base64_encode($group_no)}}">
									<div class="m-messenger__form">
										<div class="m-messenger__form-controls message-input">
											<input type="text" class="m-messenger__form-input chatMessage" id="chatMessage" placeholder="Type a message" autofocus> 
										</div>
										<div class="m-messenger__form-tools">
											<button class="btn btn-default submit chatButton" id="chatButton" style="display:none;">Send</button>	
										</div>
									</div>
								 
							</div>
						</div>	
					</div>	
				</div>	
			</div>	
		</div>
		
	  </div>
	</div>
</div>
@endsection
@push('js')
<script src="{{asset('content/js/chat.js')}}"></script>
@endpush