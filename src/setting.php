<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['title'] = $carovl['lang']['setting'] . ' – ' . $carovl['config']['site_title'];
$carovl['page'] = 'setting';
$carovl['content'] = loadPage('setting/content');
?>