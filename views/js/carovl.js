current_notification_number = 0;
current_messages_number = 0;
current_follow_request_number = 0;
current_width = $(window).width();
document_title = document.title;

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  var hash = $('.main_session').val();
	$.ajaxSetup({
		data: {
			hash: hash
		},
		cache: false
	});
	var url = document.location.toString();
	if (url.match('#')) {
		var val_hash = url.split('#')[1];
	}
	intervalUpdates = setTimeout(realTimeUpdates, 6000);
	setTimeout(updateLastSeen, 40000);
	setTimeout(isLogged, 10000);
	$('.dropdown-menu.post-options, .dropdown-menu.requests_dropdown').click(function (e) {
		e.stopPropagation();
	});
	$('#new-message-btn').click(function (e) {
		e.stopPropagation();
	});
	scrolled = 0;
	scrolled_hash = 0;
	scrolled_activities = 0;
	$(window).scroll(function () {
		if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
			if ($('#posts').length > 0) {
				if (scrolled == 0) {
					scrolled = 1;
					getMorePosts();
				}
			}
			if ($('#hashtag-posts').length > 0) {
				if (scrolled_hash == 0) {
					scrolled_hash = 1;
					getMoreHashtagPosts();
				}
			}
			if ($('#activities').length > 0) {
				if (scrolled_activities == 0) {
					scrolled_activities = 1;
					getMoreActivity();
				}
			}
		}
	});
});
function getMoreActivity() {
	var after_activity_id = $('#activities .list-activity:last').attr('data-activity-id');
	progressLoader($('#load-more-activities'));
	$.post(ajaxRequests() + '?f=activities&s=get_more_activities', {
		after_activity_id: after_activity_id
	}, function (data) {
		if (data.status == 200) {
			if (data.html.length == 0) {
				$('#load-more-activities').html(data.no_activities).fadeOut('slow', function () {
					$(this).remove();
				});
			} else {
				$('#activities').append(data.html);
			}
			progressLoader($('#load-more-activities'));
			scrolled_activities = 0;
		}
	});
}
function isLogged() {
	var page_url = $('.page_url').val();
	$.post(ajaxRequests() + '?f=session_status', {
		page_url: page_url
	}, function (data) {
		setTimeout(updateLastSeen, 30000);
		if (data.status == 200) {
			window.location.href = data.href;
		}
	});
}
function realTimeUpdates() {
	var check_posts = true;
	var hash_posts = true;
	if ($('.hashtag-count').length == 0) {
		hash_posts = false;
	}
	if ($('.posts-count').length == 0 || hash_posts == true) {
		check_posts = false;
	}
	if (typeof ($('#posts').attr('data-story-user')) == "string") {
		user_id = $('#posts').attr('data-story-user');
	} else {
		user_id = 0;
	}
	before_post_id = 0;
	if ($('.post-wrapper').length > 0) {
		var before_post_id = $('.post-wrapper > .post-user').attr('data-post-id');
	}
	var notifications_container = $('.notification-container');
	var messages_container = $('.notification-messages-container');
	var requests_container = $('.notification-requests-container');
	var ajax_request = {
		f: 'update_data',
		user_id: user_id,
		before_post_id: before_post_id,
		check_posts: check_posts,
		hash_posts: hash_posts
	};
	if (hash_posts == true) {
		ajax_request['hashtag_name'] = $('#hashtag-name').val();
	}
	$.get(ajaxRequests(), ajax_request, function (data) {
		clearTimeout(intervalUpdates);
		intervalUpdates = setTimeout(realTimeUpdates, 5000);
		if (hash_posts == true) {
			if (data.count_num > 0) {
				$('.posts-count').html(data.count);
			}
		} else {
			if (data.count_num > 0) {
				$('.posts-count').html(data.count);
			}
		}
		if (typeof (data.notifications) != "undefined" && data.notifications > 0) {
			notifications_container.find('.alert-notif').removeClass('hidden');
			notifications_container.find('.nav-link').addClass('unread');
			notifications_container.find('.alert-notif').text(data.notifications).show();
			if (current_width > 800 && data.pop == 200) {
				// notifyMe(data.icon, data.title, data.notification_text, data.url);
			}
			if (data.notifications != current_notification_number) {
				current_notification_number = data.notifications;
			}
		} else {
			notifications_container.find('.alert-notif').hide();
			notifications_container.find('.nav-link').removeClass('unread');
			current_notification_number = 0;
		}
		if (typeof (data.messages) != "undefined" && data.messages > 0) {
			messages_container.find('.alert-notif').removeClass('hidden');
			messages_container.find('.nav-link').addClass('unread');
			messages_container.find('.alert-notif').text(data.messages).show();
			if (data.messages != current_messages_number) {
				current_messages_number = data.messages;
			}
		} else {
			messages_container.find('.alert-notif').hide();
			messages_container.find('.nav-link').removeClass('unread');
			current_messages_number = 0;
		}
		if (typeof (data.follow_request) != "undefined" && data.follow_request > 0) {
			requests_container.find('alert-notif').removeClass('hidden');
			requests_container.find('.nav-link').addClass('unread');
			requests_container.find('.alert-notif').text(data.follow_request).show();
			if (data.follow_request != current_follow_request_number) {
				current_follow_request_number = data.follow_request;
			}
		} else {
			requests_container.find('.alert-notif').hide();
			requests_container.find('.nav-link').removeClass('unread');
			current_follow_request_number = 0;
		}

		if (typeof (data.messages) != "undefined" && data.messages > 0 || typeof (data.notifications) != "undefined" && data.notifications > 0 || typeof (data.follow_request) != "undefined" && data.follow_request > 0) {
			title = Number(data.notifications) + Number(data.messages) + Number(data.follow_request);
			document.title = '(' + title + ') ' + document_title;
		} else {
			document.title = document_title;
		}
	});
}
function updateLastSeen() {
	$.get(ajaxRequests(), {
		f: 'update_lastseen'
	}, function () {
		setTimeout(updateLastSeen, 40000);
	});
}
function scrollToTop() {
	verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
	element = $('html');
	offset = element.offset();
	offsetTop = offset.top;
	$('html, body').animate({
		scrollTop: offsetTop
	}, 300, 'linear');
}
function skipStep(type) {
	$.get(ajaxRequests(), {
		f: 'getstarted',
		s: 'skip_step',
		type: type
	}, function (data) {
		if (data.status == 200) {
			window.location.reload();
		}
	});
}
function openConversationImage(id, user_id) {
	$('#content').append('<div class="box-container"><div class="box-bg" onclick="closeBox();"></div><div class="loading-icon box-content"><i class="fa fa-circle-o-notch fa-spin"></i></div></div>');
	$.get(ajaxRequests(), {
		f: 'conversations',
		s: 'open_image',
		id: id,
		user_id: user_id
	}, function (data) {
		if (data.status == 200) {
			$('.box-container').html(data.html);
		}
	});
}
function openImageAlbum(image_id, type) {
	$('#content').append('<div class="box-container"><div class="box-bg" onclick="closeBox();"></div><div class="loading-icon box-content"><i class="fa fa-circle-o-notch fa-spin"></i></div></div>');
	$.get(ajaxRequests(), {
		f: 'open_album_image',
		image_id: image_id,
		type: type
	}, function (data) {
		if (data.status == 200) {
			$('.box-container').html(data.html);
		}
	});
}
function openImage(post_id) {
	$('#content').append('<div class="box-container"><div class="box-bg" onclick="closeBox();"></div><div class="loading-icon box-content"><i class="fa fa-circle-o-notch fa-spin"></i></div></div>');
	$.get(ajaxRequests(), {
		f: 'open_image',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			$('.box-container').html(data.html);
		}
	});
}
function closeBox() {
	$('.box-container').remove();
}
// Register Post Like
function registerLike(post_id) {
	var post = $('[id=post-' + post_id + ']');
	liked = '<i class="fa fa-heart has_liked"></i>';
	if (post.find("[id^=love-button]").parent().find('.has_liked').length > 0) {
		liked = '<i class="fa fa-heart-o"></i>';
	}
	post.find("[id^=love-button]").html(liked);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'like_post',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			post.find("[id^=notes]").text(data.notes);
		} else {
			post.find("[id^=notes]").text(data.notes);
		}
		// if (data.can_send == 1) {
		// 	sendEmailMessage();
		// }
	});
}
function getNewPosts() {
	if (typeof ($('#posts').attr('data-story-user')) == "string") {
		user_id = $('#posts').attr('data-story-user');
	} else {
		user_id = 0;
	}
	before_post_id = 0;
	if ($('.post-wrapper').length > 0) {
		var before_post_id = $('.post-wrapper > .post-user').attr('data-post-id');
	}
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'get_new_posts',
		before_post_id: before_post_id,
		user_id: user_id
	}, function (data) {
		if (data.length > 0) {
			scrollToTop();
			$('#posts').find('.posts-container').remove();
			$('#posts').prepend(data);
		}
		$('.posts-count').empty();
	});
}
function getMorePosts() {
	var more_posts = $('#get-more-posts');
	var after_post_id = $('.post-user:last').attr('data-post-id');
	var user_id = 0;
	var group_id = 0;
	var event_id = 0;
	if (after_post_id != null) {
		more_posts.show();
	}
	if (typeof ($('#posts').attr('data-story-user')) == "string") {
		user_id = $('#posts').attr('data-story-user');
	} else if (typeof ($('#posts').attr('data-story-group')) == "string") {
		group_id = $('#posts').attr('data-story-group');
	} else if (typeof ($('#posts').attr('data-story-event')) == "string") {
		event_id = $('#posts').attr('data-story-event');
	}
	progressLoader(more_posts);
	posts_count = 0;
	if ($('.post-user').length > 0) {
		posts_count = $('.post-user').length;
	}
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'get_more_posts',
		after_post_id: after_post_id,
		user_id: user_id,
		group_id: group_id,
		event_id: event_id,
		posts_count: posts_count
	}, function (data) {
		if (data.length == 0) {
			$.get(ajaxRequests(), {
				f: 'posts',
				s: 'no_more_posts'
			}, function (response) {
				$('#get-more-posts').html('<span>' + response.info + '</span>')
			});
		} else {
			$('#posts').append(data);
		}
		progressLoader(more_posts);
		scrolled = 0;
	});
}
function getMoreHashtagPosts() {
	var more_posts = $('#load-more-hashtags');
	var after_post_id = $('.post-user:last').attr('data-post-id');
	var hashtag_name = $('#hashtag-name').val();
	progressLoader($('#get-more-hashtag-posts'));
	$.post(ajaxRequests() + '?f=posts&s=get_more_hashtag_posts', {
		after_post_id: after_post_id,
		hashtag_name: hashtag_name
	}, function (data) {
		if (data.status == 200) {
			if (data.html.length == 0) {
				$.get(ajaxRequests(), {
					f: 'posts',
					s: 'no_more_posts'
				}, function (response) {
					$('#get-more-hashtag-posts').html('<span>' + response.info + '</span>');
				});
			} else {
				$('#hashtag-posts').append(data.html);
			}
			progressLoader($('#get-more-hashtag-posts'));
			scrolled_hash = 0;
		}
	});
}
function viewMoreReplies(comment_id) {
	main_wrapper = $('[id=comment-' + comment_id + ']');
	view_more_wrapper = main_wrapper.find('.view-more-replies');
}
// Open Requests Notifications
function openNotificationRequests() {
	requests_container = $('.notification-requests-container');
	requests_notification_list = $('#notification-requests');
	$.get(ajaxRequests(), {
		f: 'notifications',
		s: 'get_follow_requests'
	}, function (data) {
		if (data.status == 200) {
			if (data.html.length == 0) {
				requests_notification_list.html(data.no_requests);
			} else {
				requests_notification_list.html(data.html);
				realTimeUpdates();
			}
		}
	});
}
// Open Messages Notifications
function openNotificationMessages() {
	messages_container = $('.notification-messages-container');
	messages_notification_list = $('#notification-messages');
	$.get(ajaxRequests(), {
		f: 'notifications',
		s: 'get_messages'
	}, function (data) {
		if (data.status == 200) {
			if (data.html.length == 0) {
				messages_notification_list.html(data.no_message_icebreaker);
			} else {
				messages_notification_list.html(data.html);
				realTimeUpdates();
			}
		}
	});
}
function newMessage() {
	var user_id = $('#new-message-btn').attr('data-user-id');
	var modal_message = $('#new-message_' + user_id);
	modal_message.modal('show');
}
// Open Notifications
function openNotifications() {
	notifications_container = $('.notification-container');
	notification_list = $('#notifications');
	notifications_container.find('.alert-notif').addClass('hidden');
	$.get(ajaxRequests(), {
		f: 'notifications',
		s: 'get_notifications',
	}, function (data) {
		if (data.status == 200) {
			if (data.html.length == 0) {
				notification_list.html(data.no_notification);
			} else {
				notification_list.html(data.html);
				//realTimeUpdates();
				$('.see-all').html('<a href="' + data.href + '" data-redirect="?page=notifications">' + data.all_notification + '</a>');
			}
		}
	});
}
// Accept Follow Request
function acceptFollowRequest(user_id) {
	var main_container = $('.follow-request_' + user_id);
	var accept_button = main_container.find('#accept_' + user_id);
	progressLoader(accept_button);
	$.get(ajaxRequests(), {
		f: 'notifications',
		s: 'accept_follow_request',
		following_id: user_id
	}, function (data) {
		if (data.status == 200) {
			main_container.find('.btn-controls').html(data.html);
		}
	});
}
// Reject Follow Request
function rejectFollowRequest(user_id) {
	var main_container = $('.follow-request_' + user_id);
	var reject_button = main_container.find('#reject_' + user_id);
	progressLoader(reject_button);
	$.get(ajaxRequests(), {
		f: 'notifications',
		s: 'reject_follow_request',
		following_id: user_id
	}, function (data) {
		if (data.status == 200) {
			main_container.fadeOut(300, function () {
				$(this).remove();
			});
		}
	});
}
// Show Comments Container
function showComments(post_id) {
	$('#post-comments-' + post_id).toggleClass('hidden');
}
function replyComment(text, post_id, user_id) {
	post_wrapper = $('#post-' + post_id).find('#post-activity').find('.popover_activity_' + post_id);
	comment_input = post_wrapper.find('.comment-input');
	comment_list = post_wrapper.find('.comment_list');
	if (text == '') {
		return false;
	}
	comment_input.val('');
	$.post(ajaxRequests() + '?f=posts&s=register_comment', {
		post_id: post_id,
		text: text,
		user_id: user_id
	}, function (data) {
		if (data.status == 200) {
			post_wrapper.find('.comment_list').append(data.html);
			//post_wrapper.find('.comment:last-child').after(data.html);
			if ($('.load-more-comments').length == 0) {
				$('.x-scroll .x-scroll-container').scrollTop($('.x-scroll .x-scroll-container')[0].scrollHeight);
			}
			$('.x-scroll .x-scroll-container').scrollTop($('.x-scroll .x-scroll-container')[0].scrollHeight);
			$('#post-' + post_id).find('[id=notes]').html(data.notes);
			post_wrapper.find('.header_inner p').html(data.notes);
			post_wrapper.find('.roll_notes_comments').html(data.comments);
		}
	});
}
function registerComment(text, post_id, user_id, event, type) {
	if (event.keyCode == 13 && event.shiftKey == 0) {
		post_wrapper = $('#post-' + post_id).find('#post-activity').find('.popover_activity_' + post_id);
		comment_input = post_wrapper.find('.comment-input');
		comment_list = post_wrapper.find('.comment_list');
		if (text == '') {
			return false;
		}
		event.preventDefault();
		comment_input.val('');
		$.post(ajaxRequests() + '?f=posts&s=register_comment', {
			post_id: post_id,
			text: text,
			user_id: user_id
		}, function (data) {
			if (data.status == 200) {
				post_wrapper.find('.comment_list').append(data.html);
				//post_wrapper.find('.comment:last-child').after(data.html);
				if ($('.load-more-comments').length == 0) {
					$('.x-scroll .x-scroll-container').scrollTop($('.x-scroll .x-scroll-container')[0].scrollHeight);
				}
				$('.x-scroll .x-scroll-container').scrollTop($('.x-scroll .x-scroll-container')[0].scrollHeight);
				$('#post-' + post_id).find('[id=notes]').html(data.notes);
				post_wrapper.find('.header_inner p').html(data.notes);
				post_wrapper.find('.roll_notes_comments').html(data.comments);
			}
		});
	}
}
function registerCommentLike(comment_id) {
	var comment = $('[id=comment_' + comment_id + ']');
	comment_text = comment.find('p.note_added_text').text();
	progressLoader(comment.find('#like-comment'));
	$.post(ajaxRequests() + '?f=posts&s=register_comment_like', {
		comment_id: comment_id,
		comment_text: comment_text
	}, function (data) {
		if (data.status == 200) {
			comment.find('#like-comment').html('<i class="fa fa-heart liked icon-loader"></i>');
		} else {
			comment.find('#like-comment').html('<i class="fa fa-heart-o"></i>');
		}
	});
}
function loadAllComments(post_id) {
	post_wrapper = $('#post-' + post_id);
	load_more_wrapper = post_wrapper.find('.load-more-comments');
	progressLoader(load_more_wrapper);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'load_more_comments',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			post_wrapper.find('.comment-list').html(data.html);
			load_more_wrapper.fadeOut('slow');
		}
	});
}
function openBlockDialog(user_id) {
	var block_dialog = $('#user-timeline_' + user_id).find('#block-dialog');
	block_dialog.modal({
		show: true
	});
}
function openPostDelete(post_id) {
	var delete_dialog = $('#post-' + post_id).find('#delete-dialog');
	delete_dialog.modal({
		show: true
	});
}
function registerBlock(user_id) {
	var block_dialog = $('#user-timeline_' + user_id).find('#block-dialog');
	var block_button = block_dialog.find('#block-user');
	block_dialog.find('.cancel').attr('disabled', true);
	block_button.attr('disabled', true);
	$.get(ajaxRequests(), {
		f: 'timeline',
		s: 'block_user',
		user_id: user_id
	}, function (data) {
		if (data.status == 200) {
			block_dialog.modal('hide');
			setTimeout(function () {
				window.location.href = data.href;
			}, 300);
		}
	});
}
function deletePost(post_id) {
	var delete_dialog = $('#post-' + post_id).find('#delete-dialog');
	var delete_button = delete_dialog.find('#delete-post');
	delete_dialog.find('.cancel').attr('disabled', true);
	delete_button.attr('disabled', true);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'delete_post',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			delete_dialog.modal('hide');
			setTimeout(function() {
				$('#post-' + post_id).fadeOut(200, function () {
					$(this).remove();
				});
			}, 300);
		}
	});
}
function startRepositioner() {
	$('.user-cover-reposition-width').hide();
	$('.user-reposition-container').show();
	$('.not-editing').hide();
	$('.editing').show();
	$('.user-reposition-dragable-container').show();
	$('.profile-cover-changer').show();
	$('.screen-width').val($('.user-reposition-container').width());
	$('.user-reposition-container img').css('cursor', '-webkit-grab').draggable ({
		scroll: false,
		axis: "y",
		cursor: "-webkit-grab",
		drag: function (event, ui) {
			y1 = $('.user-cover-reposition-container').height();
			y2 = $('.user-reposition-container').find('img').height();
			if (ui.position.top >= 0) {
				ui.position.top = 0;
			} else {
				if (ui.position.top <= (y1 - y2)) {
					ui.position.top = y1 - y2;
				}
			}
		},
		stop: function (event, ui) {
			$('input.cover-position').val(ui.position.top);
		}
	});
}
function stopRepositioner() {
	$('.not-editing').show();
	$('.editing').hide();
	$('.user-cover-reposition-width').show();
	$('.user-reposition-container').hide();
	$('input.cover-position').val(0);
	$('.user-reposition-container img').draggable('destroy').css('cursor', 'default');
}
function submitRepositioner() {
	if ($('input.cover-position').length == 1) {
		posY = $('input.cover-position').val();
		$('form.cover-position-form').submit();
	}
}
// Conversations
function openMessageConversation(recipient_id) {
	$.get(ajaxRequests(), {
		f: 'conversations',
		s: 'is_conversation_on',
		recipient_id: recipient_id
	}, function (data) {
		if (data.conversation != 1) {
			return false;
		}
	});
	placement = 1;
	if ($('.conversation_popover').length == 1) {
		placement = 2;
	} else if ($('.conversation_popover').length == 2) {
		placement = 3;
	}
	$(document.body).attr('data-conversation-recipient-' + recipient_id, recipient_id);
	$('.conversation_popover').show();
	$.get(ajaxRequests(), {
		f: 'conversations',
		s: 'load_conversation',
		recipient_id: recipient_id,
		placement: placement
	}, function (data) {
		if (data.status == 200) {
			if ($('.conversation_popover').length == 1) {
				if ($('.messaging_conversation_container.messaging_open_conversations.open_message_' + recipient_id).length == 0) {
					$('.messaging_conversation_container.messaging_open_conversations:first-child').remove();
					$('.messaging_conversation_popover').addClass('has_open').append('<div class="messaging_conversation_container messaging_open_conversations open_message_' + recipient_id + '"></div>').fadeIn(500, function () {
						$(this).show();
					});
				}
				$('.messaging_conversation_container.messaging_open_conversations.open_message_' + recipient_id).html(data.html);
			} else {
				if ($('.messaging_conversation_container.messaging_open_conversations.open_message_' + recipient_id).length > 0) {
					$('.messaging_conversation_container.messaging_open_conversations.open_message_' + recipient_id).html(data.html);
				} else {
					$('.messaging_conversation_popover').addClass('has_open').append('<div class="messaging_conversation_container messaging_open_conversations open_message_' + recipient_id + '"></div>').fadeIn(500, function () {
						$(this).show();
					});
					$('.open_message_' + recipient_id).append(data.html);
				}
			}
			$('.conversation_popover').show();
			$.get(ajaxRequests(), {
				f: 'conversations',
				s: 'load_conversation_messages',
				recipient_id: recipient_id
			}, function (data) {
				if (data.messages.length > 0) {
					$('.messaging_conversation_popover.has_open').find('.messaging_conversation_container.messaging_open_conversations').find('.message_' + recipient_id).find('.message_list').html(data.messages);
				}
				$('.x-scroll.conversations').scrollTop($('.x-scroll.conversations')[0].scrollHeight);
			});
		}
	});
}
function showActions() {
	menu = $('.conversation_popover').find('.popover_conversation_action');
	menu.fadeToggle(200);
	var clicked = 0;
	menu = $('.conversation_popover').find('.popover_conversation_action');
	$('body').on('click', function () {
		if (clicked == 0) {
			menu.fadeOut('fast');
		} else {
			clicked = 0;
		}
	});
	$('body').on('click', '#more_actions', function (e) {
		clicked = 1;
		if (clicked == 1) {
			$('#more_actions').addClass('open');
			menu.show();
		}
	});
}
function savePost(post_id) {
	var post = $('#post-' + post_id);
	progressLoader(post.find('.save-post'));
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'save_post',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			post.find('.save-post').html('<i class="fa fa-bookmark icon-loader saved" data-icon="bookmark"></i> ' + data.text);
		} else if (data.status == 300) {
			post.find('.save-post').html('<i class="fa fa-bookmark-o icon-loader" data-icon="bookmark-o"></i> ' + data.text);
		}
	});
}
function reportPost(post_id) {
	var post = $('#post-' + post_id);
	progressLoader(post.find('.report-post'));
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'report_post',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			post.find('.report-post').html('<i class="fa fa-flag icon-loader reported" data-icon="flag"></i> ' + data.text);
		} else if (data.status == 300) {
			post.find('.report-post').html('<i class="fa fa-flag-o icon-loader" data-icon="flag-o"></i> ' + data.text);
		}
	});
}
function getNewHashtagPosts() {
	before_post_id = 0;
	if ($('.post-wrapper').length > 0) {
		var before_post_id = $('.post-wrapper > .post-user').attr('data-post-id');
	}
	var hashtag_name = $('#hashtag-name').val();
	if (! hashtag_name) {
		return false;
	}
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'get_new_hashtag_posts',
		before_post_id: before_post_id,
		hashtag_name: hashtag_name
	}, function (data) {
		if (data.length > 0) {
			$('#hashtag-posts').find('.posts-container').remove();
			$('#hashtag-posts').prepend(data);
		}
		$('.posts-count').empty();
	});
}
$('body').on('mouseenter', '.user-preview', function () {
	var popover = $(this);
	var type = popover.attr('data-type');
	var id = popover.attr('data-id');
	var placement = popover.attr('data-placement') || 'none';
	var placement_code = 'preview not-profile';
	if (placement == 'profile') {
		placement_code = 'preview';
	} else if (placement == 'search') {
		placement_code = 'preview mt-3';
	}
	var over_time = setTimeout(function () {
		var offset = popover.offset();
		var posY = (offset.top - $(window).scrollTop()) + popover.height();
		var posX = offset.left - $(window).scrollLeft();
		var available = $(window).width() - posX;
		if ($(window).width() > 800) {
			if (available < 400) {
				var right = available - popover.width();
				if (posY > 0) {
					$('body').append('<div class="' + placement_code + ' right" style="position: fixed; top: ' + posY + 'px; right: ' + right + 'px;"><div class="loading-preview"><div class="fa fa-circle-o-notch fa-spin"></div></div></div>');
				}
			} else {
				if (posY > 0) {
					$('body').append('<div class="' + placement_code + '" style="position: fixed; top: ' + posY + 'px; left: ' + posX + 'px;"><div class="loading-preview"><div class="fa fa-circle-o-notch fa-spin"></div></div></div>');
				}
			}
		}
		$.get(ajaxRequests(), {
			f: 'user_popover',
			id: id,
			type: type
		}, function (data) {
			if (data.status == 200) {
				$('.preview').html(data.html);
			}
		});
	}, 1000);
	popover.data('timeout', over_time);
});
$('body').on('mouseleave', '.user-preview', function (e) {
	var to = e.toElement || e.relatedTarget;
	if (! $(to).is('.preview')) {
		clearTimeout($(this).data('timeout'));
		$('.preview').fadeOut('fast');
	}
});
$('body').on('mouseleave', '.preview', function () {
	$('.preview').fadeOut('fast');
});
// Open Edit Box
function openPostEdit(post_id) {
	var edit_box = $('#post-' + post_id).find('#edit-dialog');
	edit_box.modal({
		show: true
	});
}
function editPost(post_id) {
	var post = $('#post-' + post_id);
	var edit_box = post.find('#edit-dialog');
	var edit_textarea = post.find('.edit-text-' + post_id + ' textarea');
	var text = edit_textarea.val();
	var post_text = post.find('.post-text-content p');
	progressLoader(post.find('#edit-post'));
	$.post(ajaxRequests() + '?f=posts&s=edit_post', {
		post_id: post_id,
		text: text
	}, function (data) {
		if (data.status == 200) {
			post.find('.post-text-content').removeClass('no_text');
			post.find('.post-text-content').addClass('has_text');
			post_text.html(data.html);
			edit_box.modal('hide');
		}
		progressLoader(post.find('#edit-post'));
		// if (data.can_send == 1) {
		// 	sendEmailMessage();
		// }
	});
}
function openPostActivity(post_id) {
	var activity_post = $('#post-' + post_id).find('#post-activity');
	activity_post.modal({
		show: true
	});
	$('#post-activity').ready(function () {
		var activity_post_id = activity_post.find('.popover_activity_' + post_id);
		activity_post_id.find('.header_inner p').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		activity_post_id.find('.main_container .loading_comments').html('<div class="mt-3 text-center" font-size: 32px; color: #8f8f8f;"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
		$.get(ajaxRequests(), {
			f: 'get_post_data',
			post_id: post_id
		}, function (data) {
			if (data.status == 200) {
				activity_post_id.find('.header_inner p').html(data.notes);
				activity_post_id.find('.roll_note_list').html(data.user_likes);
				activity_post_id.find('.roll_notes_likes').html(data.likes);
				activity_post_id.find('.roll_notes_comments').html(data.comments);
				activity_post_id.find('.loading_comments').remove();
				activity_post_id.find('.note_list').find('.comment_list').html(data.user_comments);
				$('.x-scroll .x-scroll-container').scrollTop($('.x-scroll .x-scroll-container')[0].scrollHeight);
			}
		});
	})
}
function loadMoreComments(post_id) {
	main_wrapper = $('#post-' + post_id).find('#post-activity');
	view_more_wrapper = main_wrapper.find('.popover_activity_' + post_id).find('.load-more-comments');
	progressLoader(view_more_wrapper);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'load_all_comments',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			main_wrapper.find('.popover_activity_' + post_id).find('.note_list').find('.comment_list').html(data.html);
			view_more_wrapper.remove();
		}
	});
}
function deleteComment(comment_id, post_id) {
	main_wrapper = $('#post-' + post_id).find('#post-activity');
	activity_post_id = main_wrapper.find('.popover_activity_' + post_id);
	var comment = $('[id=comment_' + comment_id + ']');
	delete_button = comment.find('.delete-comment');
	progressLoader(delete_button);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'delete_comment',
		comment_id: comment_id,
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			activity_post_id.find('.header_inner p').html(data.notes);
			$('#post-' + post_id).find('[id=notes]').html(data.notes);
			activity_post_id.find('.roll_notes_comments').html(data.comments);
			main_wrapper.find('.popover_activity_' + post_id).find('#comment_' + comment_id).fadeOut(300, function () {
				$(this).remove();
			});
		}
	});
}
// Add Emoticon to Publisher Box
function addEmoticon(code, input) {
	input_tag = $(input);
	input_val = input_tag.val(); 
	if (typeof (input_tag.attr('placeholder')) != "undefined") {
		input_placeholder = input_tag.attr('placeholder');
		if (input_placeholder == input_val) {
			input_tag.val('');
			input_val = input_tag.val();
		}
	}
	if (input_val.length == 0) {
		input_tag.val(code + ' ');
	} else {
		input_tag.val(input_val + ' ' + code);
	}
	input_tag.keyup();
}
function deleteJoinedMember(user_id, group_id) {
	progressLoader($('#member_' + user_id).find('button'));
	$.get(ajaxRequests(), {
		f: 'group_setting',
		s: 'delete_member',
		user_id: user_id,
		group_id: group_id
	}, function (data) {
		if (data.status == 200) {
			$('#member_' + user_id).fadeOut(300, function () {
				$(this).remove();
			});
		}
	});
}
function acceptJoinRequest(user_id, group_id) {
	progressLoader($('#request_' + user_id).find('.accept'));
	$.get(ajaxRequests(), {
		f: 'group_setting',
		s: 'accept_join_request',
		user_id: user_id,
		group_id: group_id
	}, function (data) {
		if (data.status == 200) {
			$('#request_' + user_id).fadeOut(300, function () {
				$(this).remove();
			});
		}
	});
}
function rejectJoinRequest(user_id, group_id) {
	progressLoader($('#request_' + user_id).find('.reject'));
	$.get(ajaxRequests(), {
		f: 'group_setting',
		s: 'reject_join_request',
		user_id: user_id,
		group_id: group_id
	}, function (data) {
		if (data.status == 200) {
			$('#request_' + user_id).fadeOut(300, function () {
				$(this).remove();
			});
		}
	});
}
function searchPosts(query) {
	var type = '';
	if ($('#timeline').attr('data-page') == 'group') {
		type = 'group';
	} else if ($('#timeline').attr('data-page') == 'user') {
		type = 'user';
	} else {
		return false;
	}
	var id = $('#timeline').attr('data-id');
	if (id == null || id == "undefined") {
		return false;
	}
	if (query == '') {
		if (type == 'group') {
			$.get(ajaxRequests(), {
				f: 'groups',
				s: 'load_group_posts',
				group_id: id
			}, function (data) {
				$('#load-posts').html(data);
			});
		} else if (type == 'user') {
			$.get(ajaxRequests(), {
				f: 'timeline',
				s: 'load_timeline_posts',
				user_id: id
			}, function (data) {
				$('#load-posts').html(data);
			});
		}
	} else {
		$.get(ajaxRequests(), {
			f: 'posts',
			s: 'search_posts',
			id: id,
			query: query,
			type: type
		}, function (data) {
			if (data.status == 200) {
				$('#posts').html(data.html);
			}
		});
	}
}
function openShareButtons(post_id) {
	post_wrapper = $('#post-' + post_id);
	post_wrapper.find('.card.share').slideToggle(200);
}
function registerShare(post_id) {
	var post_wrapper = $('#post-' + post_id);
	$.get(ajaxRequests(), {
		f: 'posts',
		s: 'register_share',
		post_id: post_id
	}, function (data) {
		if (data.status == 200) {
			post_wrapper.find('#share_' + post_id).removeClass('btn-white').addClass('btn-carovl is_shared pointer');
		} else {
			post_wrapper.find('#share_' + post_id).removeClass('btn-carovl is_shared pointer').addClass('btn-white');
		}
		if (data.can_send == 1) {
			// sendEmailMessage();
		}
	})
}
var setDelay = (function () {
	var timer = 0;
	return function (callback, ms) {
		clearTimeout(timer);
		timer = setTimeout(callback, ms);
	};
})();
function registerVideoViews(post_id) {
	if (post_id && typeof(Number(post_id)) == 'number' && post_id > 0) {
		setDelay(function () {
			$.ajax({
				url: ajaxRequests(),
				type: 'GET',
				dataType: 'json',
				data: {
					f: 'posts',
					s: 'view_video',
					post_id: post_id
				}
			}).done(function (data) {
				if (data.status == 200) {
					$('span[data-video-id=' + post_id + ']').text(data.views);
					$('video[data-video-id=' + post_id + ']').removeAttr('onplay');
				}
			});
		}, 3000);
	}
}
function markAsSold(post_id, product_id) {
	var post = $('#post-' + post_id);
	progressLoader(post.find('#mark-as-sold'));
	$.get(ajaxRequests(), {
		f: 'products',
		s: 'mark_as_sold',
		post_id: post_id,
		product_id: product_id
	}, function (data) {
		if (data.status == 200) {
			post.find('#product-status').html('<span class="product-sold" id="product-status">' + data.text + '</span>');
			post.find('#mark-as-sold').fadeOut(100);
		}
	});
}