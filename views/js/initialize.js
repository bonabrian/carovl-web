function progressLoader(container_elem) {
	container_elem.each(function () {
		progress_icon_elem = $(this).find('i.icon-loader');
		default_icon = progress_icon_elem.attr('data-icon');
		hide_back = false;
		if (progress_icon_elem.hasClass('hidde') == true) {
			hide_back = true;
		}
		if ($(this).find('i.fa-circle-o-notch').length == 1) {
			progress_icon_elem.removeClass('fa-circle-o-notch').removeClass('fa-spin').addClass('fa-' + default_icon);
			if (hide_back == true) {
				progress_icon_elem.hide();
			}
		} else {
			progress_icon_elem.removeClass('fa-' + default_icon).addClass('fa-circle-o-notch fa-spin').show();
		}
		return true;
	});
}
$(document).ready(function () {
	$('input.form-control').blur(function () {
		if ($(this).val()) {
			$(this).addClass('used');
		} else {
			$(this).removeClass('used');
		}
	});
	$('input.form-control.date, input.form-control.time').blur(function () {
		$(this).addClass('used');
	});
	$('textarea.form-control').blur(function () {
		if ($(this).val()) {
			$(this).addClass('used');
		} else {
			$(this).removeClass('used');
		}
	});
});
function startLoad() {
	$('#redirect-bar').show().animate({
		width: 20 + 80 * Math.random() + "%"
	}, 500);
}
function finishLoad() {
	$('#redirect-bar').animate({
		width: "100%"
	}, 500).delay(300).fadeOut(300, function () {
		$(this).width("0");
	});
}