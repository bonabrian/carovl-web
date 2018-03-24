<?php 
if ($carovl['logged_in'] == false) {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
if (empty($_GET['group'])) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if ($carovl['config']['groups'] == 0) {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
$carovl['setting']['admin'] = false;
if (isset($_GET['group']) && ! empty($_GET['group'])) {
	if (isGroupExist($_GET['group']) === false) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	}
	$group_id = groupIdFromGroupname($_GET['group']);
	$carovl['setting']['admin'] = true;
	if (empty($group_id)) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	}
	$carovl['setting'] = groupData($group_id);
}
if (isGroupOwner($group_id) === false) {
	if (isAdmin() === false && isModerator() === false) {
		header("Location: " . $carovl['config']['site_url']);
		exit();
	}
}
$carovl['description'] = $carovl['config']['site_desc'];
$carovl['keywords'] = $carovl['config']['site_keywords'];
$carovl['page'] = 'group-setting';
$carovl['title'] = $carovl['lang']['group_setting'] . ' – ' . $carovl['config']['site_title'];
$carovl['content'] = loadPage('group-setting/content');
?>