<?php 
if ($carovl['config']['developers_page'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'developers';
$carovl['title'] = $carovl['lang']['developers'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('developers/content');
?>