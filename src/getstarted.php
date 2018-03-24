<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if (isUserComplete($carovl['user']['user_id']) === false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['user']['getstarted_image'] == 0) {
	$page = 'getstarted-avatar';
} elseif ($carovl['user']['getstarted_info'] == 0) {
	$page = 'getstarted-info';
} else {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'getstarted';
$carovl['title'] = 'Get Started – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('getstarted/' . $page);
?>