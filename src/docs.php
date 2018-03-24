<?php 
if ($carovl['config']['developers_page'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (empty($_GET['type']) || ! isset($_GET['type'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'docs';
$carovl['title'] = '';
$pages = array(
	'api'
);
if (! in_array($_GET['type'], $pages)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$type = secureIt($_GET['type']);
if ($type == 'api') {
	$carovl['title'] = $carovl['lang']['api'] . ' – ' . $carovl['config']['site_title'];
}

$carovl['content'] = loadPage("docs/$type");
?>