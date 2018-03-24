<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
$api_version = 'v1';

$types = array(
	'users',
	'posts',
	'search'
);
$result = array();
$limit = 20;
if (isset($_GET['limit']) && ! empty($_GET['limit'])) {
	$limit = secureIt($_GET['limit']);
}
if (! is_numeric($limit)) {
	$limit = 20;
}
if ($limit > 100) {
	$limit = 100;
}
if (! in_array($_GET['type'], $types)) {
	$error_data = array(
		'status' => 'Failed!',
		'version' => $api_version,
		'errors' => array(
			'error_code' => 'ERR_R26',
			'error_text' => 'Bad request.'
		)
	);
	header("Content-type: application/json");
	echo json_encode($error_data, JSON_PRETTY_PRINT);
	exit();
}
if ($_GET['type'] == 'users') {
	$user = userData(userIdFromUsername($_GET['username']));
	if (empty($user)) {
		$error_data = array(
			'status' => 'Failed!',
			'version' => $api_version,
			'errors' => array(
				'error_code' => 'ERR_U00',
				'error' => 'Username not found.',
			)
		);
		header("Content-type: application/json");
		echo json_encode($error_data, JSON_PRETTY_PRINT);
		exit();
	}
	$data = array(
		'id' => $user['user_id'],
		'username' => $user['username'],
		'first_name' => $user['first_name'],
		'last_name' => $user['last_name'],
		'about' => $user['about'],
		'gender' => $user['gender'],
		'birthday' => $user['birthday'],
		'website' => $user['website'],
		'facebook' => $user['facebook'],
		'twitter' => $user['twitter'],
		'google' => $user['google'],
		'avatar' => $user['avatar'],
		'cover' => $user['cover'],
		'verified' => $user['verified'],
		'url' => $user['url']
	);
	header("Content-type: application/json");
	echo json_encode(array(
		'status' => 'Success!',
		'api_version' => $api_version,
		'data' => $data
	), JSON_PRETTY_PRINT);
	exit();
} elseif ($_GET['type'] == 'posts') {
	$publisher_id = userIdFromUsername($_GET['username']);
	if (empty($publisher_id)) {
		$error_data = array(
			'status' => 'Failed!',
			'version' => $api_version,
			'errors' => array(
				'error_code' => 'ERR_U00',
				'error' => 'Username not found.',
			)
		);
		header("Content-type: application/json");
		echo json_encode($error_data, JSON_PRETTY_PRINT);
		exit();
	}
	$posts = getPosts(array(
		'limit' => $limit,
		'publisher_id' => $publisher_id
	));
	if (empty($posts)) {
		$error_data = array(
			'status' => 'Failed!',
			'version' => $api_version,
			'errors' => array(
				'error_code' => 'ERR_P00',
				'error' => 'User doesn\'t have any posts.',
			)
		);
		header("Content-type: application/json");
		echo json_encode($error_data, JSON_PRETTY_PRINT);
		exit();
	}
	header("Content-type: application/json");
	foreach ($posts as $post) {
		$data = array(
			'post_id' => $post['post_id'],
			'post_data' => array(
				'post_id' => $post['post_id'],
				'post_text' => $post['post_text'],
				'post_file' => $post['post_file'],
				'post_map' => $post['post_map'],
				'post_time' => $post['time'],
				'post_likes' => countPostLikes($post['post_id'])
			),
			'publisher_data' => array(
				'id' => $post['publisher']['user_id'],
				'username' => $post['publisher']['username'],
				'first_name' => $post['publisher']['first_name'],
				'last_name' => $post['publisher']['last_name'],
				'about' => $post['publisher']['about'],
				'gender' => $post['publisher']['gender'],
				'birthday' => $post['publisher']['birthday'],
				'website' => $post['publisher']['website'],
				'facebook' => $post['publisher']['facebook'],
				'twitter' => $post['publisher']['twitter'],
				'google' => $post['publisher']['google'],
				'avatar' => $post['publisher']['avatar'],
				'cover' => $post['publisher']['cover'],
				'verified' => $post['publisher']['verified'],
				'url' => $post['publisher']['url']
			)
		);
		array_push($result, $data);
	}
	echo json_encode(array(
		'status' => 'Success!',
		'api_version' => $api_version,
		'data' => $result
	), JSON_PRETTY_PRINT);
	exit();
} elseif ($_GET['type'] == 'search') {
	# code...
}
?>