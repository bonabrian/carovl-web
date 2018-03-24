<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
if ($carovl['logged_in'] == true) {
	$update_last_seen = updateLastSeen($carovl['user']['user_id']);
}
$page = '';
if ($carovl['logged_in'] == true && ! isset($_GET['page'])) {
	$page = 'home';
} elseif (isset($_GET['page'])) {
	$page = $_GET['page'];
}
if ((! isset($_GET['page']) && $carovl['logged_in'] == false) || (isset($_GET['page']) && $carovl['logged_in'] == false && $page == 'home')) {
	$page = 'welcome';
}
$came_from = false;
if ($page == 'timeline') {
	$came_from = true;
}
switch ($page) {
	case 'home':
		include('src/home.php');
		break;
	case 'notifications':
		include('src/notifications.php');
		break;
	case 'activity':
		include('src/activity.php');
		break;
	case 'welcome':
		include('src/welcome.php');
		break;
	case 'logout':
		include('src/logout.php');
		break;
	case '404':
		include('src/404.php');
		break;
	case 'user-activation':
		include('src/user-activation.php');
		break;
	case 'setting':
		include('src/setting.php');
		break;
	case 'search':
		include('src/search.php');
		break;
	case 'post':
		include('src/story.php');
		break;
	case 'timeline':
		include('src/timeline.php');
		break;
	case 'hashtag':
		include('src/hashtag.php');
		break;
	case 'new-article':
		include('src/articles/new-article.php');
		break;
	case 'edit-article':
		include('src/articles/edit-article.php');
		break;
	case 'article':
		include('src/articles/article.php');
		break;
	case 'new-event':
		include('src/events/new-event.php');
		break;
	case 'view-event':
		include('src/events/view-event.php');
		break;
	case 'edit-event':
		include('src/events/edit-event.php');
		break;
	case 'events':
		include('src/events/events.php');
		break;
	case 'events-going':
		include('src/events/events-going.php');
		break;
	case 'events-interested':
		include('src/events/events-interested.php');
		break;
	case 'events-invited':
		include('src/events/events-invited.php');
		break;
	case 'events-past':
		include('src/events/events-past.php');
		break;
	case 'groups':
		include('src/groups/my-groups.php');
		break;
	case 'create-group':
		include('src/groups/create-group.php');
		break;
	case 'group-setting':
		include('src/groups/group-setting.php');
		break;
	case 'admincp':
		include('src/admincp.php');
		break;
	case 'policy':
		include('src/policy.php');
		break;
	case 'about':
		include('src/about.php');
		break;
}
if (empty($carovl['content'])) {
	include('src/404.php');
}
if (empty($carovl['title'])) {
	$data['title'] = $carovl['config']['site_title'];
}
$data['search'] = '';
if (isset($_GET['page']) && $_GET['page'] == 'hashtag' && ! empty($_GET['hash'])) {
	$data['search'] = '#' . $_GET['hash'];
} elseif (isset($_GET['page']) && $_GET['page'] == 'timeline' && ! empty($_GET['u'])) {
	if ($carovl['logged_in'] == true) {
		if ($carovl['user']['username'] != $_GET['u']) {
			$data['search'] = $_GET['u'];
		}
	}
}
$data['url'] = '';
$actual_link = "http://$_SERVER[HTTP_HOST]";
$data['title'] = secureIt($carovl['title']);
$data['page'] = $carovl['page'];
$data['welcome_page'] = 0;
$data['welcome_url'] = seoLink('index.php?page=welcome');
if ($carovl['page'] == 'welcome') {
	$data['welcome_page'] = 1;
}
$url = '';
if (! empty($_POST['url'])) {
	$url = $_POST['url'];
}
$data['redirect'] = 0;
if ($carovl['redirect'] == 1) {
	$data['redirect'] = 1;
}
$data['url'] = seoLink('index.php' . $url);
echo $carovl['content'];
?>
<input type="hidden" id="redirect-data" value="<?php echo htmlspecialchars(json_encode($data)); ?>">