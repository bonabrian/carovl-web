<?php 
if (empty($_GET['id'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$_GET['id'] = getPostIdFromUrl($_GET['id']);
$article = articleData($_GET['id']);
$id = secureIt($_GET['id']);
if (empty($article)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$query = mysqli_query($sql_connect, "UPDATE " . T_ARTICLES . " SET `views` = `views` + 1 WHERE `id` = '{$id}'");
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $article['tags'];
$carovl['page'] = 'article';
$carovl['article'] = $article;
$carovl['author'] = $article['author'];
$carovl['title'] = $article['article_title'];
$carovl['content'] = loadPage('articles/content');
?>