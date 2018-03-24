<?php 
if ($carovl['logged_in'] == true) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if ($carovl['config']['user_registration'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'register';
$carovl['title'] = $carovl['config']['site_title'];
$carovl['content'] = loadPage('welcome/register');
?>