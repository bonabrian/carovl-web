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
$carovl['active'] = 3;
$carovl['events'] = getGoingEvents();
$carovl['title'] = $carovl['lang']['going_events'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('events/going');
?>