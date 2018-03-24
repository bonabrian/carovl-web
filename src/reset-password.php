<?php 
if ($carovl['logged_in'] == true) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (empty($_GET['code'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$file = 'reset-password';
$validate = isValidPasswordResetToken($_GET['code']);
if ($validate === false) {
	$file = 'invalid-markup';
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['title'] = $carovl['config']['site_title'];
$carovl['page'] = 'reset-password';
$carovl['content'] = loadPage('welcome/' . $file);
?>