<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if (! isset($_GET['hash']) || empty($_GET['hash'])) {
	redirectSmooth($carovl['config']['site_url']);
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = '';
$carovl['page'] = 'hashtag';
$carovl['title'] = '#' . $_GET['hash'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('hashtags/content');
?>