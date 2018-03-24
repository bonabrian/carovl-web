<?php 
if ($carovl['logged_in'] == true) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['title'] = $carovl['config']['site_title'];
$carovl['page'] = 'forgot-password';
$carovl['content'] = loadPage('welcome/forgot-password');
?>