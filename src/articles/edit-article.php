<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (empty($_GET['id'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (isArticleOwner($_GET['id']) === false) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$data = articleData($_GET['id']);
if (empty($data)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = '';
$carovl['keywords'] = '';
$carovl['page'] = 'edit-article';
$carovl['title'] = $carovl['lang']['edit_article'];
$carovl['article'] = $data;
$carovl['content'] = loadPage('articles/edit-article');
?>