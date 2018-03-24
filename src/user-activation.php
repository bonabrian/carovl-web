<?php 
if ($carovl['logged_in'] == true) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (empty($_SESSION['code_id']) || ! isset($_SESSION['code_id'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['user'] = userData($_SESSION['code_id']);
if (empty($carovl['user'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['title'] = $carovl['lang']['account_activation'] . ' – ' . $carovl['config']['site_title'];
$carovl['page'] = 'user-activation';
$carovl['content'] = loadPage('user-activation/content');
?>