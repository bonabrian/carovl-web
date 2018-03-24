<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['config']['products'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (empty($_GET['id'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$product = $carovl['product'] = productData($_GET['id']);
if (empty($product)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$post_id = getPostIdFromProductId($_GET['id']);
if (empty($post_id)) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (isPostOwner($post_id, $carovl['user']['user_id']) === false) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'edit-product';
$carovl['title'] = $carovl['lang']['edit_product'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('products/edit-product');
?>