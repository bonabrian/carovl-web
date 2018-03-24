<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'new-article';
$carovl['title'] = '@' . $carovl['user']['username'] . ' – ' . $carovl['lang']['new_article'];
$carovl['content'] = loadPage('articles/new-article');
?>