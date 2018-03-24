<?php 
if (empty($_GET['type']) || ! isset($_GET['type'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$pages = array(
	'privacy',
	'terms-of-service',
);
if (! in_array($_GET['type'], $pages)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['policy'] = getPolicy();

$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'policy';
$carovl['title'] = '';
$type = secureIt($_GET['type']);

if ($type == 'privacy') {
	$carovl['title'] = $carovl['lang']['privacy_policy'];
} elseif ($type == 'terms-of-service') {
	$carovl['title'] = $carovl['lang']['terms_of_service'];
}
$carovl['title'] = $carovl['config']['site_name'] . ' – ' . $carovl['title'];
$carovl['content'] = loadPage("policy/$type");
?>