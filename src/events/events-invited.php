<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['config']['events'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'events';
$carovl['active'] = 5;
$carovl['events'] = getInvitedEvents();
$carovl['title'] = $carovl['lang']['invited_events'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('events/invited');
?>