@php
	$isMine = $chat->sender_user_id == Auth::id();
	$bubbleClass = $isMine ? 'folder-chat-bubble--out' : 'folder-chat-bubble--in';
@endphp
<div class="folder-chat-bubble {{ $bubbleClass }}">
	@if(!$isMine)
	<div class="folder-chat-bubble__avatar">
		@if($chat->user_type == 'admin')
			<img src="{{ asset('application/public/avatars/adminavatar.png') }}" alt="">
		@else
			<img src="{{ Profile::picture($chat->sender_user_id) }}" alt="">
		@endif
	</div>
	@endif
	<div class="folder-chat-bubble__body">
		@if(!$isMine)
		<span class="folder-chat-bubble__name">
			@if($chat->user_type == 'admin')
				Admin
			@else
				{{ Profile::getUserName($chat->sender_user_id) }}
			@endif
		</span>
		@endif
		<p class="folder-chat-bubble__text">{{ $chat->msg }}</p>
		@if(!empty($chat->created_at))
		<time class="folder-chat-bubble__time">{{ \Carbon\Carbon::parse($chat->created_at)->format('M j, g:i A') }}</time>
		@endif
	</div>
</div>
