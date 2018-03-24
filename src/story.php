<?php 
$carovl['description'] = '';
$carovl['keywords'] = '';
$carovl['title'] = '';
$carovl['page'] = 'story';
$carovl['content'] = '';

$carovl['title'] .= ' – ' . $carovl['config']['site_name'];
if (isset($_GET['id']) && ! empty($_GET['id'])) {
	$placement = '';
	if ($carovl['logged_in'] == true) {
		if (isAdmin()) {
			$placement = 'admin';
		}
	}
	$id = getPostIdFromUrl($_GET['id']);
	$carovl['story'] = postData($id, $placement, 'not_limited');
	if (empty($carovl['story'])) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	} elseif (empty($carovl['story']['post_id'])) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	} elseif (isPostExist($carovl['story']['post_id']) === false) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	}
	$carovl['story']['page'] = 1;
	$carovl['content'] = loadPage('story-content/content');
	$carovl['description'] = secureIt(mb_substr($carovl['story']['original_text'], 0, 156, "utf-8"));
	$carovl['keywords'] = '';
} else {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (! empty($carovl['description'])) {
	$carovl['title'] = substr($carovl['story']['original_text'], 0, 50);
} else {
	$carovl['title'] = $carovl['lang']['post_label'];
}
?>