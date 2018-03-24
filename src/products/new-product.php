<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if ($carovl['config']['products'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'new-product';
$carovl['title'] = $carovl['lang']['new_product'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('products/new-product');
?>