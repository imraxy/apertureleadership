
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function imageclick(e) {
	var link = $(e).attr('imagelink');
	window.open(link);
}

function appRoot() {
	var root = $('body#root').data('root') || $('body#root').attr('data-root') || '';
	return String(root).replace(/\/$/, '');
}

function folderChatEnabled() {
	var $chat = $('#folderChat');
	if (!$chat.length) {
		return false;
	}
	var v = $chat.data('chat-enabled');
	return v === 1 || v === '1' || v === true;
}

function scrollFolderChatToBottom() {
	var el = document.getElementById('conversation');
	if (el) {
		el.scrollTop = el.scrollHeight;
	}
}

function sendMessage() {
	var $input = $('#chatMessage1');
	var message = $input.val();

	if ($.trim(message) === '') {
		return false;
	}

	if (!folderChatEnabled()) {
		alert('Chat is disabled until a collaborator joins your session.');
		return false;
	}

	var $sendBtn = $('#folderChatSend');
	$sendBtn.prop('disabled', true).text('Sending…');

	$.ajax({
		url: appRoot() + '/chat-conversation/chat_action',
		method: 'POST',
		data: {
			to_user_id: $('#to_user_id').val(),
			chat_message: message,
			action: 'insert_chat',
			to_folder: $('#to_folder').val(),
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		dataType: 'json',
		success: function (resp) {
			if (resp.error) {
				alert(resp.error);
				return;
			}
			if (resp.conversation) {
				$input.val('');
				$('#conversation').html(resp.conversation);
				scrollFolderChatToBottom();
			}
		},
		error: function (xhr) {
			var msg = 'Could not send message.';
			if (xhr.status === 419) {
				msg = 'Your session expired. Please refresh the page and try again.';
			} else if (xhr.responseJSON && xhr.responseJSON.error) {
				msg = xhr.responseJSON.error;
			} else if (xhr.responseJSON && xhr.responseJSON.message) {
				msg = xhr.responseJSON.message;
			}
			alert(msg);
		},
		complete: function () {
			$sendBtn.prop('disabled', !folderChatEnabled()).text('Send');
		}
	});
}

function updateUserChat() {
	if (!folderChatEnabled()) {
		return;
	}

	$.ajax({
		url: appRoot() + '/chat-conversation/chat_conversation_list',
		method: 'GET',
		data: {
			to_user_id: $('#to_user_id').val(),
			action: 'update_user_chat',
			to_folder: $('#to_folder').val()
		},
		dataType: 'json',
		success: function (response) {
			if (response.conversation && $.trim(response.conversation) !== '') {
				$('#conversation').html(response.conversation);
			}
		}
	});
}

$(document).ready(function () {
	var root = appRoot();

	$.ajax({
		url: root + '/get_album_images',
		method: 'POST',
		data: {
			user_id: $('#to_user_id').val(),
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		success: function (datas) {
			if (datas !== '') {
				$('.tbody').html(datas);
				$('.themeofowl').each(function () {
					$(this).owlCarousel({
						autoPlay: 3000,
						items: 4,
						itemsDesktop: [1199, 3],
						itemsDesktopSmall: [979, 3]
					});
				});
				$('.emptyimage').hide();
			} else {
				$('.emptyimage').show();
			}
		}
	});

	if (folderChatEnabled()) {
		setInterval(updateUserChat, 5000);
	}

	scrollFolderChatToBottom();

	$('#folderChatSend').on('click', function (e) {
		e.preventDefault();
		sendMessage();
	});

	$('#chatMessage1').on('keydown', function (e) {
		if (e.keyCode === 13 && !e.shiftKey) {
			e.preventDefault();
			sendMessage();
		}
	});
});
