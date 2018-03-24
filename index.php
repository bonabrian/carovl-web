<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
if ($carovl['logged_in'] == true) {
	$update_last_seen = updateLastSeen($carovl['user']['user_id']);
} elseif (! empty($_SERVER['HTTP_HOST'])) {
	$server_scheme = @$_SERVER['HTTPS'];
	$page_url = ($server_scheme == 'on') ? 'https://' : 'http://';
	$http_url = $page_url . $_SERVER['HTTP_HOST'];
	$url = parse_url($carovl['config']['site_url']);
	if (! empty($url)) {
		if ($url['scheme'] == 'http') {
			if ($http_url != 'http://' . $url['host']) {
				header("Location: " . $carovl['config']['site_url']);
				exit();
			}
		} else {
			if ($http_url != 'https://' . $url['host']) {
				header("Location: " . $carovl['config']['site_url']);
				exit();
			}
		}
	}
}
if (! empty($_GET['ref']) && $carovl['logged_in'] == false && ! isset($_COOKIE['src'])) {
	$get_ip = getIpAddress();
	if (! isset($_SESSION['ref']) && ! empty($get_ip)) {
		$_GET['ref'] = secureIt($_GET['ref']);
		$ref_user_id = userIdFromUsername($_GET['ref']);
		$user = userData($ref_user_id);
		if (! empty($user)) {
			if (ipInRange($user['ip_address'], '/24') === false && $user['ip_address'] != $get_ip) {
				$_SESSION['ref'] = $user['username'];
			}
		}
	}
}
if (! isset($_COOKIE['src'])) {
	@setcookie('src', '1', time() + 31556926, '/');
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
if ($carovl['config']['maintenance_mode'] == 1) {
	if ($carovl['logged_in'] == false) {
		if ($page == 'admincp') {
			$page = 'welcome';
		} else {
			$page = 'maintenance';
		}
	} else {
		if (isAdmin() === false) {
			$page = 'maintenance';
		}
	}
}

switch ($page) {
	case 'maintenance':
		include('src/maintenance.php');
		break;
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
	case 'login':
		include('src/login.php');
		break;
	case 'register':
		include('src/register.php');
		break;
	case 'getstarted':
		include('src/getstarted.php');
		break;
	case 'logout':
		include('src/logout.php');
		break;
	case '404':
		include('src/404.php');
		break;
	case 'forgot-password':
		include('src/forgot-password.php');
		break;
	case 'reset-password':
		include('src/reset-password.php');
		break;
	case 'user-activation':
		include('src/user-activation.php');
		break;
	case 'activate':
		include('src/activate.php');
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
	case 'new-product':
		include('src/products/new-product.php');
		break;
	case 'edit-product':
		include('src/products/edit-product.php');
		break;
	case 'product':
		include('src/products/product.php');
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
	case 'developers':
		include('src/developers.php');
		break;
	case 'docs':
		include('src/docs.php');
		break;
}
if (empty($carovl['content'])) {
	include('src/404.php');
}
echo loadPage('container');
mysqli_close($sql_connect);
unset($carovl)
?>