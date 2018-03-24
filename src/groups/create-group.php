<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['config']['groups'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'create-group';
$carovl['title'] = $carovl['lang']['create_group'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('groups/create-group');
?>