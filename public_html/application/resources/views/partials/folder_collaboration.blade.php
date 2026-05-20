<div class="m-portlet collab-panel">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Collaboration</h3>
			</div>
		</div>
	</div>
	<div class="m-portlet__body">
		<p class="collab-muted">You can join many sessions at once. Each session has its own members, shared folders, and chat. Invite people to a session below, or start a new one.</p>

		@if(isset($receivedInvites) && $receivedInvites->count() > 0)
		<div class="alert alert-info">
			<strong>Pending invites for you</strong>
			<ul class="mb-0 mt-2 list-unstyled">
				@foreach($receivedInvites as $invite)
				<li class="mb-2">
					<strong>{{ $invite->session->name ?? 'Session' }}</strong> —
					{{ $invite->inviter->name ?? 'Someone' }} invited you.
					<a href="{{ route('account.collaboration.accept', $invite->id) }}" class="btn btn-sm btn-success ml-2">Accept</a>
					<button type="button" class="btn btn-sm btn-outline-secondary ml-1 collab-decline" data-invite-id="{{ $invite->id }}">Decline</button>
				</li>
				@endforeach
			</ul>
		</div>
		@endif

		<div class="mb-3">
			<label for="collab-session-select"><strong>Invite to session</strong></label>
			<div class="d-flex flex-wrap align-items-center">
				<select id="collab-session-select" class="form-control mr-2" style="max-width: 320px;">
					@if(isset($sessionBlocks) && count($sessionBlocks) > 0)
						@foreach($sessionBlocks as $block)
						<option value="{{ $block['session']->id }}">{{ $block['session']->name }}</option>
						@endforeach
					@else
						<option value="">New session (created on first invite)</option>
					@endif
				</select>
				<button type="button" class="btn btn-outline-primary btn-sm" id="collab-new-session">+ New session</button>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<label for="collab-user-search"><strong>Invite someone</strong></label>
				<input type="text" id="collab-user-search" class="form-control" placeholder="Search name or email (min 2 characters)" autocomplete="off">
				<div id="collab-search-results" class="list-group collab-search-results mt-2" style="display:none;"></div>
				<div id="collab-invite-message" class="small mt-2"></div>
			</div>
			<div class="col-md-6">
				@if(isset($sessionBlocks) && count($sessionBlocks) > 0)
					@foreach($sessionBlocks as $block)
					<div class="mb-4 border rounded p-3">
						<strong>{{ $block['session']->name }}</strong>
						<ul class="list-group mt-2">
							@foreach($block['members'] as $member)
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span>
									{{ $member->user->name ?? 'User' }}
									@if($member->user_id == Auth::id()) <em>(you)</em> @endif
									@if($member->role === 'owner') <span class="badge badge-primary">owner</span> @endif
								</span>
								@if($member->user_id != Auth::id())
								<button type="button" class="btn btn-sm btn-outline-danger collab-remove-member"
									data-user-id="{{ $member->user_id }}"
									data-session-id="{{ $block['session']->id }}">Remove</button>
								@endif
							</li>
							@endforeach
						</ul>
						<form method="POST" action="{{ route('account.collaboration.leave') }}" class="mt-2"
							onsubmit="return confirm('Leave this session?');">
							@csrf
							<input type="hidden" name="session_id" value="{{ $block['session']->id }}">
							<button type="submit" class="btn btn-sm btn-outline-secondary">Leave this session</button>
						</form>
						@if($block['sentInvites']->count() > 0)
						<strong class="d-block mt-2 small">Pending invites</strong>
						<ul class="list-group mt-1">
							@foreach($block['sentInvites'] as $invite)
							<li class="list-group-item d-flex justify-content-between align-items-center py-1">
								<span class="small">{{ $invite->invitee->name ?? $invite->invitee->email }}</span>
								<button type="button" class="btn btn-sm btn-outline-secondary collab-cancel-invite" data-invite-id="{{ $invite->id }}">Cancel</button>
							</li>
							@endforeach
						</ul>
						@endif
					</div>
					@endforeach
				@else
				<p class="collab-muted mt-2 mb-0">No sessions yet. Search for someone to invite — a session will be created automatically.</p>
				@endif
			</div>
		</div>
	</div>
</div>
