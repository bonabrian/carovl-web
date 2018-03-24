<?php 
$carovl['policy'] = getPolicy();

$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'about';
$carovl['title'] = $carovl['config']['site_name'] . ' – ' . $carovl['lang']['about_label'];
$carovl['content'] = loadPage('about/content');
?>