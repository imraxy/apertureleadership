@extends('layouts.master')

@push('css')
	<link href="{{asset('content/css/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{asset('content/css/vendors/base/style.bundle.css')}}">
	<link href="{{asset('content/owlCarousal.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('content/owltheme.css')}}" rel="stylesheet" type="text/css" />
	<style>
		h3._color { color: #000; }
		.themeofowl .item { margin: 3px; }
		.themeofowl .item img { display: block; width: 100%; height: auto; }
		.collab-panel { margin-bottom: 1.5rem; }
		.collab-search-results { max-height: 200px; overflow-y: auto; }
		.collab-search-results .list-group-item { cursor: pointer; }
		.collab-muted { color: #666; font-size: 13px; }

		.folder-chat {
			display: flex;
			flex-direction: column;
			border: 1px solid #e8e8e8;
			border-radius: 10px;
			background: #f7f8fa;
			overflow: hidden;
		}
		.folder-chat__header {
			padding: 12px 16px;
			background: #fff;
			border-bottom: 1px solid #e8e8e8;
			font-weight: 600;
			font-size: 14px;
			color: #333;
		}
		.folder-chat__messages {
			min-height: 320px;
			max-height: 420px;
			overflow-y: auto;
			padding: 16px;
			display: flex;
			flex-direction: column;
			gap: 12px;
		}
		.folder-chat__empty {
			margin: auto;
			text-align: center;
			color: #888;
			font-size: 14px;
			padding: 40px 16px;
		}
		.folder-chat-bubble {
			display: flex;
			align-items: flex-end;
			gap: 10px;
			max-width: 88%;
		}
		.folder-chat-bubble--out {
			align-self: flex-end;
			flex-direction: row-reverse;
		}
		.folder-chat-bubble--in {
			align-self: flex-start;
		}
		.folder-chat-bubble__avatar img {
			width: 36px;
			height: 36px;
			border-radius: 50%;
			object-fit: cover;
		}
		.folder-chat-bubble__body {
			padding: 10px 14px;
			border-radius: 14px;
			box-shadow: 0 1px 2px rgba(0,0,0,0.06);
		}
		.folder-chat-bubble--in .folder-chat-bubble__body {
			background: #fff;
			border: 1px solid #e8e8e8;
		}
		.folder-chat-bubble--out .folder-chat-bubble__body {
			background: #3d5afe;
			color: #fff;
		}
		.folder-chat-bubble__name {
			display: block;
			font-size: 12px;
			font-weight: 600;
			color: #555;
			margin-bottom: 4px;
		}
		.folder-chat-bubble__text {
			margin: 0;
			font-size: 14px;
			line-height: 1.45;
			word-break: break-word;
		}
		.folder-chat-bubble__time {
			display: block;
			font-size: 11px;
			margin-top: 6px;
			opacity: 0.75;
		}
		.folder-chat-bubble--out .folder-chat-bubble__time {
			color: rgba(255,255,255,0.85);
		}
		.folder-chat__composer {
			display: flex;
			align-items: stretch;
			gap: 10px;
			padding: 12px 14px;
			background: #fff;
			border-top: 1px solid #e8e8e8;
		}
		.folder-chat__input {
			flex: 1;
			border: 1px solid #d0d0d0;
			border-radius: 8px;
			padding: 12px 14px;
			font-size: 14px;
			outline: none;
		}
		.folder-chat__input:focus {
			border-color: #3d5afe;
			box-shadow: 0 0 0 2px rgba(61, 90, 254, 0.15);
		}
		.folder-chat__input:disabled {
			background: #f0f0f0;
			cursor: not-allowed;
		}
		.folder-chat__send {
			flex-shrink: 0;
			min-width: 88px;
			padding: 12px 20px;
			font-size: 14px;
			font-weight: 600;
			border: none;
			border-radius: 8px;
			background: #3d5afe;
			color: #fff;
			cursor: pointer;
			transition: background 0.15s ease;
		}
		.folder-chat__send:hover:not(:disabled) {
			background: #2f4ae0;
		}
		.folder-chat__send:disabled {
			background: #b0b8c8;
			cursor: not-allowed;
		}
	</style>
@endpush

@push('js')
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script src="{{asset('content/owl.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
			active: function() { sessionStorage.fonts = true; }
		});
	</script>
@endpush

@section('content')

<div class="container-fluid">
	@if(session('success'))
	<div class="alert alert-success"><strong>Success!</strong> {{ session('success') }}</div>
	@endif
	@if(session('error'))
	<div class="alert alert-danger"><strong>Error:</strong> {{ session('error') }}</div>
	@endif

	@include('partials.folder_collaboration')

	<div class="row">
		<div class="col-md-6">
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">User Images</h3>
						</div>
					</div>
				</div>
				<div class="m-portlet__body">
					<div class="m-section">
						<div class="m-section__content">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>#</th>
											<th>User</th>
											<th>images</th>
										</tr>
									</thead>
									<tbody class="tbody">
										<tr class="emptyimage">
											<td colspan="5">
												<div class="panel-body" style="text-align: center; padding: 80px;">
													<h3>Your folder is empty!</h3>
													<span style="font-size: 12px;">You have no items added in the folder.</span>
													<p></p>
													<p><a href="{{ route('front.albums') }}" class="ex-btncontact">Add to folder</a></p>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">Chat</h3>
						</div>
					</div>
				</div>
				<div class="m-portlet__body">
					<div class="m-section">
						<div class="m-section__content">
							<div class="folder-chat" id="folderChat" data-chat-enabled="{{ !empty($chatEnabled) ? '1' : '0' }}">
								<div class="folder-chat__header">Session conversation</div>
								<div class="folder-chat__messages messages" id="conversation">
									@if($chats->count() > 0)
										@foreach($chats as $chat)
											@include('partials.folder_chat_message')
										@endforeach
									@else
										<div class="folder-chat__empty" id="folderChatEmpty">
											@if(!empty($chatEnabled))
												No messages yet. Say hello to your group.
											@else
												Chat opens when a collaborator joins your session.
											@endif
										</div>
									@endif
								</div>
								<input type="hidden" value="1" id="to_user_id" />
								@if(isset($folder_detail))
									@php $to_folder_id = base64_encode($folder_detail->id.'&'.auth::user()->user_name); @endphp
									<input type="hidden" value="{{ $to_folder_id }}" name="to_folder" id="to_folder" />
								@else
									<input type="hidden" value="" name="to_folder" id="to_folder" />
								@endif
								<div class="folder-chat__composer message-input">
									<input type="text"
										class="folder-chat__input chatMessage"
										id="chatMessage1"
										placeholder="{{ !empty($chatEnabled) ? 'Type a message…' : 'Chat disabled until a collaborator joins' }}"
										{{ empty($chatEnabled) ? 'disabled' : '' }}
										autocomplete="off">
									<button type="button"
										class="folder-chat__send"
										id="folderChatSend"
										{{ empty($chatEnabled) ? 'disabled' : '' }}>Send</button>
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
<script>
	window.folderCollaboration = {
		searchUrl: "{{ route('account.collaboration.search') }}",
		createSessionUrl: "{{ route('account.collaboration.session.create') }}",
		inviteUrl: "{{ route('account.collaboration.invite') }}",
		declineUrl: "{{ url('/account/folders/collaboration/invites') }}",
		cancelUrl: "{{ url('/account/folders/collaboration/invites') }}",
		removeUrl: "{{ url('/account/folders/collaboration/members') }}",
		leaveUrl: "{{ route('account.collaboration.leave') }}",
		csrf: "{{ csrf_token() }}"
	};
</script>
<script src="{{ asset('content/js/user-chat.js') }}"></script>
<script src="{{ asset('content/js/folder-collaboration.js') }}"></script>
@endpush
