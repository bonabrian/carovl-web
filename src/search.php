<?php 
$carovl['description'] = '';
$carovl['keywords'] = '';
$carovl['page'] = 'search';
$carovl['title'] = '';
if (isset($_GET['query']) && ! empty($_GET['query'])) {
	$carovl['title'] = $_GET['query'] . ' – ' . $carovl['config']['site_title'];
} else {
	$carovl['title'] = $carovl['lang']['search'] . ' – ' . $carovl['config']['site_title'];
}
$carovl['content'] = loadPage('search/content');
?>