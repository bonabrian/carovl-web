<?php
if ($carovl['logged_in'] == false) {
	if ($carovl['config']['profile_privacy'] == 0) {
		header("Location: " . seoLink('index.php?page=welcome'));
		exit();
	}
}
if (isset($_GET['u'])) {
	$check_user = isUsernameExist($_GET['u'], 1);
	if (in_array(true, $check_user)) {
		if ($check_user['type'] == 'user') {
			$id = $user_id = userIdFromUsername($_GET['u']);
			$carovl['profile'] = userData($user_id);
			$type = 'timeline';
			$about = $carovl['profile']['about'];
			if (! empty($carovl['profile']['first_name'])) {
				if (! empty($carovl['profile']['last_name'])) {
					$name = $carovl['profile']['first_name'] . ' ' . $carovl['profile']['last_name']. ' (@' . $carovl['profile']['username'] . ')';
				} else {
					$name = $carovl['profile']['first_name'] . ' (@' . $carovl['profile']['username'] . ')';
				}
			} else {
				$name = '@' . $carovl['profile']['username'];
			}
		} elseif ($check_user['type'] == 'group') {
			$id = $group_id = groupIdFromGroupname($_GET['u']);
			$carovl['group_profile'] = groupData($group_id);
			$type = 'groups';
			$about = $carovl['group_profile']['about'];
			$name = $carovl['group_profile']['name'];
		}
	} else {
		header("Location: " . seoLink('index.php?page=404'));
		exit();
	}
} else {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if ($carovl['config']['groups'] == 0 && $type == 'group') {
	header("Location: " . $carovl['config']['site_url']);
	exit();
}
if (! empty($_GET['ref'])) {
	if ($_GET['ref'] == 'se') {
		$register_recent = registerRecentSearch($id, $type);
	}
}
$con2 = 1;
if ($type == 'timeline' && $carovl['logged_in'] == true) {
	$is_blocked = $carovl['is_blocked'] = isBlocked($user_id);
	if (isset($_GET['block_user']) && ! empty($_GET['block_user'])) {
		if ($_GET['block_user'] == 'block' && $is_blocked === false && isAdmin($user_id) === false) {
			$block = registerBlock($user_id);
			if ($block) {
				header("Location: " . $carovl['config']['site_url']);
				exit();
			}
		} elseif ($_GET['block_user'] == 'unblock' && $is_blocked == true) {
			$unblock = removeBlock($user_id);
			if ($unblock) {
				header("Location: " . $carovl['profile']['url']);
				exit();
			}
		}
	}
	if ($is_blocked) {
		$con2 = 0;
		if (! isset($came_from)) {
			header("Location: " . $carovl['config']['site_url']);
			exit();
		} else {
			redirectSmooth(seoLink('index.php?page=404'));
		}
	}
}
$can_ = 0;
if ($carovl['logged_in'] == true && $carovl['config']['profile_visit'] == 1 && $type == 'timeline' && $con2 == 1) {
	if ($carovl['profile']['follow_privacy'] == 1) {
		if (isFollowing($carovl['profile']['user_id'], $carovl['user']['user_id']) === true) {
			if ($carovl['profile']['user_id'] != $carovl['user']['user_id'] && $carovl['user']['visit_privacy'] == 0) {
				$can_ = 1;
				if ($carovl['profile']['visit_privacy'] == 0 && $can_ == 1) {
					$notification_data_array = array(
						'recipient_id' => $carovl['profile']['user_id'],
						'type' => 'visited_profile',
						'url' => 'index.php?page=timeline&u=' . $carovl['user']['username']
					);
					registerNotification($notification_data_array);
				}
			}
		}
	} else {
		if ($carovl['profile']['user_id'] != $carovl['user']['user_id'] && $carovl['user']['visit_privacy'] == 0) {
			$can_ = 1;
			if ($carovl['profile']['visit_privacy'] == 0 && $can_ == 1) {
				$notification_data_array = array(
					'recipient_id' => $carovl['profile']['user_id'],
					'type' => 'visited_profile',
					'url' => 'index.php?page=timeline&u=' . $carovl['user']['username']
				);
				registerNotification($notification_data_array);
			}
		}
	}
}
$carovl['title'] = $name;
$carovl['description'] = $about;
$carovl['keywords'] = '';
$carovl['page'] = $type;
$carovl['content'] = loadPage("{$type}/content");
?>