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
	if ($event && ! empty($event)) {
		$carovl['description'] = $carovl['config']['site_desc'];
		$carovl['keywords'] = $carovl['config']['site_keywords'];
		$carovl['page'] = 'events';
		$carovl['event'] = $event;
		$carovl['event']['going'] = countEventAction('going', $event['id']);
		$carovl['event']['interested'] = countEventAction('interested', $event['id']);
		$carovl['event']['invited'] = countEventAction('invited', $event['id']);
		//$carovl['events'] = getSuggestedEvents(array('limit' => 5));
		$carovl['title'] = $event['name'] . ' – ' . $carovl['config']['site_title'];
		$carovl['content'] = loadPage('events/content');
	}
}
?>