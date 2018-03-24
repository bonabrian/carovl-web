<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['config']['events'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$event = eventData($_GET['id']);
	if ($event && ! empty($event) && isEventOwner($event['id'])) {
		$carovl['description'] = $carovl['config']['site_desc'];
		$carovl['keywords'] = $carovl['config']['site_keywords'];
		$carovl['page'] = 'events';
		$carovl['active'] = 7;
		$carovl['event'] = $event;
		$carovl['title'] = $carovl['lang']['edit_event'] . ' – ' . $carovl['config']['site_title'];
		$carovl['content'] = loadPage('events/edit-event');
	}
}
?>