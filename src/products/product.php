<?php 
if (empty($_GET['id'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if ($carovl['config']['products'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$_GET['id'] = getPostIdFromUrl($_GET['id']);
$product = productData($_GET['id']);
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['product'] = $product;
$carovl['page'] = 'product';
$carovl['title'] = $product['name'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('products/content');
?>