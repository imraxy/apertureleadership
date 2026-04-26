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
								@if($chat->user_type == "admin")
									<img src="{{asset('application/public/avatars/adminavatar.png')}}" />
								@else
									<img src="{{Profile::picture($chat->sender_user_id)}}" />
								@endif								
								</div>
								<div class="m-messenger__message-body">
									<div class="m-messenger__message-arrow"></div>
									<div class="m-messenger__message-content">
										<div class="m-messenger__message-username">
											@if($chat->user_type == "admin")
												Admin
											@else
												{{Profile::getUserName($chat->sender_user_id)}}
											@endif
											
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