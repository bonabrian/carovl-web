<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'admin';
$carovl['title'] = $carovl['lang']['admin_area'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('admin/content');
?>