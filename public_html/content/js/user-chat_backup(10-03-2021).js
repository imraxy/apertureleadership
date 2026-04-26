	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

$(document).ready(function(){
	setInterval(function(){
		//updateUserList();	
		updateUnreadMessageCount();	
	}, 600);	
	setInterval(function(){
		showTypingStatus();
		updateUserChat();			
	}, 5000);
	$(".messages").animate({ 
		scrollTop: $(document).height() 
	}, "fast");
	$(document).on("click", '#profile-img', function(event) { 	
		$("#status-options").toggleClass("active");
	});
	$(document).on("click", '.expand-button', function(event) { 	
		$("#profile").toggleClass("expanded");
		$("#contacts").toggleClass("expanded");
	});	
	$(document).on("click", '#status-options ul li', function(event) { 	
		$("#profile-img").removeClass();
		$("#status-online").removeClass("active");
		$("#status-away").removeClass("active");
		$("#status-busy").removeClass("active");
		$("#status-offline").removeClass("active");
		$(this).addClass("active");
		if($("#status-online").hasClass("active")) {
			$("#profile-img").addClass("online");
		} else if ($("#status-away").hasClass("active")) {
			$("#profile-img").addClass("away");
		} else if ($("#status-busy").hasClass("active")) {
			$("#profile-img").addClass("busy");
		} else if ($("#status-offline").hasClass("active")) {
			$("#profile-img").addClass("offline");
		} else {
			$("#profile-img").removeClass();
		};
		$("#status-options").removeClass("active");
	});	
	$(document).on('click', '.contact', function(){		
		$('.contact').removeClass('active');
		$(this).addClass('active');
		var to_user_id = $(this).data('touserid');
		showUserChat(to_user_id);
		$(".chatMessage").attr('id', 'chatMessage'+to_user_id);
		$(".chatButton").attr('id', 'chatButton'+to_user_id);
	});	
	$(document).on("click", '.submit', function(event) { 
		var to_user_id = $(this).attr('id');
		to_user_id = to_user_id.replace(/chatButton/g, "");
		sendMessage(to_user_id);
	});
	
	$(".chatMessage").keydown(function (e) {
  
		if (e.keyCode == 13) {
			console.log("put function call here");
			e.preventDefault();
			var to_user_id = $(this).attr('id');
			to_user_id = to_user_id.replace(/chatMessage/g, "");
			sendMessage(to_user_id);
		}
	  
	});
	$(document).on('focus', '.message-input', function(){
		var is_type = 'yes';
		var _root = $('#root').attr('data-root');
		$.ajax({
			url: _root + '/chat-conversation/chat_action',
			method:"POST",
			data:{is_type:is_type, action:'update_typing_status'},
			success:function(){
			}
		});
	}); 
	$(document).on('blur', '.message-input', function(){
		var is_type = 'no';
		var _root = $('#root').attr('data-root');
		$.ajax({
			url: _root + '/chat-conversation/chat_action',
			method:"POST",
			data:{is_type:is_type, action:'update_typing_status'},
			success:function() {
			}
		});
	}); 		
}); 
function updateUserList() {
	var _root = $('#root').attr('data-root');
	$.ajax({
		url: _root + '/chat-conversation/chat_action',
		method:"POST",
		dataType: "json",
		data:{action:'update_user_list'},
		success:function(response){		
			var obj = response.profileHTML;
			Object.keys(obj).forEach(function(key) {
				// update user online/offline status
				if($("#"+obj[key].userid).length) {
					if(obj[key].online == 1 && !$("#status_"+obj[key].userid).hasClass('online')) {
						$("#status_"+obj[key].userid).addClass('online');
					} else if(obj[key].online == 0){
						$("#status_"+obj[key].userid).removeClass('online');
					}
				}				
			});			
		}
	});
}

function sendMessage(to_user_id) {
	message = $(".message-input input").val();
	$('.message-input input').val('');
	if($.trim(message) == '') {
		return false;
	}
	
	var _root = $('#root').attr('data-root');
	
	var to_folder = $("#to_folder").val();
	
	$.ajax({
		url: _root + '/chat-conversation/chat_action',
		method:"POST",
		data:{to_user_id:to_user_id, chat_message:message, action:'insert_chat', to_folder:to_folder},
		dataType: "json",
		success:function(resp) {
			$('#conversation').html(resp.conversation);				
			$(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
		}
	});	
}

function showUserChat(to_user_id){
	var _root = $('#root').attr('data-root');
	$.ajax({
		url: _root + '/chat-conversation/chat_action',
		method:"POST",
		data:{to_user_id:to_user_id, action:'show_chat'},
		dataType: "json",
		success:function(response){
			$('#userSection').html(response.userSection);
			$('#conversation').html(response.conversation);	
			$('#unread_'+to_user_id).html('');
		}
	});
}

function updateUserChat() {
	
	var to_user_id = $("#to_user_id").val();
	var to_folder_id = $("#to_folder").val();
	var _root = $('#root').attr('data-root');
	$.ajax({
		url: _root + '/chat-conversation/chat_conversation_list',
		method:"GET",
		data:{to_user_id:to_user_id, action:'update_user_chat', to_folder:to_folder_id},
		dataType: "json",
		success:function(response){				
			$('#conversation').html(response.conversation);			
		}
	});
}

function updateUnreadMessageCount() {
	$('li.contact').each(function(){
		var _root = $('#root').attr('data-root');
		if(!$(this).hasClass('active')) {
			var to_user_id = $(this).attr('data-touserid');
			$.ajax({
				url: _root + '/chat-conversation/chat_action',
				method:"POST",
				data:{to_user_id:to_user_id, action:'update_unread_message'},
				dataType: "json",
				success:function(response){		
					if(response.count) {
						$('#unread_'+to_user_id).html(response.count);	
					}					
				}
			});
		}
	});
}
function showTypingStatus() {
	$('li.contact.active').each(function(){
		var to_user_id = $(this).attr('data-touserid');
		var _root = $('#root').attr('data-root');
		$.ajax({
			url: _root + '/chat-conversation/chat_action',
			method:"POST",
			data:{to_user_id:to_user_id, action:'show_typing_status'},
			dataType: "json",
			success:function(response){				
				$('#isTyping_'+to_user_id).html(response.message);			
			}
		});
	});
}