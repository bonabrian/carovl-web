<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
$api_version = '1.0';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./api/core/functions.php');
require_once('assets/import/config.php');
require_once('assets/import/hybridauth/hybridauth/Hybrid/Auth.php');
require_once('assets/import/hybridauth/hybridauth/Hybrid/Endpoint.php');

$errors_data = array();
$success_data = array();

$_POST['username'] = 'bonabrian';
$_POST['password'] = '80n4r4hm4';
$_POST['user_id'] = 7;
$_POST['profile_id'] = 8;
$_POST['session'] = createSession();
$_GET['cookie'] = 'b369570f00c067219e65ae2a0c78a260f9addcd70b4669e83cd800d3f55d3f217c6119ed480308072979a3f14bae523dc5101c52120c535e9';

if (empty($_GET['type']) || ! isset($_GET['type'])) {
	$errors_data = array(
		'status' => '300',
		'response' => 'failed',
		'api_version' => $api_version,
		'errors' => array(
			'ID' => 'ERR_BAD_REQUEST',
			'error_message' => 'Bad request, no type specified.'
		)
	);
	header("Content-type: application/json");
	echo json_encode($errors_data, JSON_PRETTY_PRINT);
	exit();
}

$type = secureIt($_GET['type'], 0);
if ($type == 'login') {
	if (empty($_POST['username'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_CREDENTIALS',
				'error_message' => 'Please write your username.'
			)
		);
	} elseif (empty($_POST['password'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_CREDENTIALS',
				'error_message' => 'Please write your password.'
			)
		);
	} elseif (empty($_POST['session'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_SESSION',
				'error_message' => 'No session sent.'
			)
		);
	}
	if (empty($errors_data)) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user_id = userIdFromUsername($username);
		if (empty($user_id)) {
			$user_id = userIdFromEmail($username);
		}
		$session = secureIt($_POST['session']);
		$user_data = userData($user_id);
		if (empty($user_data)) {
			$errors_data = array(
				'status' => '300',
				'response' => 'failed',
				'api_version' => $api_version,
				'errors' => array(
					'ID' => 'ERR_EMPTY_USER_DATA',
					'error_message' => 'Username not exists.'
				)
			);
			header("Content-type: application/json");
			echo json_encode($errors_data, JSON_PRETTY_PRINT);
			exit();
		} else {
			$login_user = loginUser($username, $password);
			if (! $login_user) {
				$errors_data = array(
					'status' => '300',
					'response' => 'failed',
					'api_version' => $api_version,
					'errors' => array(
						'ID' => 'ERR_WRONG_CREDENTIALS',
						'error_message' => 'Incorrect username or password.'
					)
				);
				header("Content-type: application/json");
				echo json_encode($errors_data, JSON_PRETTY_PRINT);
				exit();
			} else {
				$time = time();
				$add_session = mysqli_query($sql_connect, "INSERT INTO " . T_SESSIONS . " (`user_id`, `session_id`, `platform`, `time`) VALUES ('{$user_id}', '{$session}', 'phone', '{$time}')");
				if ($add_session) {
					$u_session = '';
					if (! empty($_POST['timezone'])) {
						$timezone = secureIt($_POST['timezone']);
					} else {
						$timezone = 'UTC';
					}
					$add_timezone = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `timezone` = '{$timezone}' WHERE `user_id` = {$user_id}");
					if (! empty($_GET['cookie'])) {
						$u_id = userIdForLogin($username);
						$ip = secureIt(getIpAddress());
						$update_ip = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `ip_address` = '{$ip}' WHERE `user_id` = {$u_id}");
						$u_session = createLoginSession(userIdForLogin($username));
					}
					if (! empty($_POST['device_id'])) {
						$u_id = userIdForLogin($username);
						$device_id = secureIt($_POST['device_id']);
						$update_device_id = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `device_id` = '{$device_id}' WHERE `user_id` = {$u_id}");
					}
					$success_data = array(
						'status' => '200',
						'response' => 'success',
						'api_version' => $api_version,
						'user_id' => userIdFromUsername($username),
						'success' => array(
							'success_message' => $carovl['lang']['welcome_back']
						),
						'timezone' => $timezone,
						'cookie' => $u_session
					);
					header("Content-type: application/json");
					echo json_encode($success_data, JSON_PRETTY_PRINT);
				}
			}
		}
	} else {
		header("Content-type: application/json");
		echo json_encode($errors_data, JSON_PRETTY_PRINT);
		exit();
	}
} // end type == 'login'

if ($type == 'get_user_data') {
	if (empty($_POST['user_id'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_USER_ID',
				'error_message' => 'No user id sent.'
			)
		);
	} elseif (empty($_POST['profile_id'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_PROFILE_ID',
				'error_message' => 'No profile id sent.'
			)
		);
	} elseif (empty($_POST['session'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_SESSION',
				'error_message' => 'No session sent.'
			)
		);
	}
	if (empty($errors_data)) {
		$user_id = $_POST['user_id'];
		$session = secureIt($_POST['session']);
		$user_data = userData($user_id);
		langsFromDb($user_data['language']);
		if (empty($user_data)) {
			$errors_data = array(
				'status' => '300',
				'response' => 'failed',
				'api_version' => $api_version,
				'errors' => array(
					'ID' => 'ERR_EMPTY_USER_DATA',
					'error_message' => 'Username not exists.'
				)
			);
			header("Content-type: application/json");
			echo json_encode($errors_data, JSON_PRETTY_PRINT);
			exit();
		} elseif ($carovl['logged_in'] == false) {
			$errors_data = array(
				'status' => '300',
				'response' => 'failed',
				'api_version' => $api_version,
				'errors' => array(
					'ID' => 'ERR_WRONG_SESSION_ID',
					'error_message' => 'Wrong session id.'
				)
			);
			header("Content-type: application/json");
			echo json_encode($errors_data, JSON_PRETTY_PRINT);
			exit();
		} else {
			$profile_id = secureIt($_POST['profile_id']);
			$profile_data = userData($profile_id);
			if (empty($profile_data)) {
				$errors_data = array(
					'status' => '300',
					'response' => 'failed',
					'api_version' => $api_version,
					'errors' => array(
						'ID' => 'ERR_EMPTY_USER_PROFILE_DATA',
						'error_message' => 'User profile is not exists.'
					)
				);
				header("Content-type: application/json");
				echo json_encode($errors_data, JSON_PRETTY_PRINT);
				exit();
			}
			foreach ($ignored as $key => $value) {
				unset($profile_data[$value]);
			}
			$profile_data['is_following'] = 0;
			$logged_user_id = $user_id;
			if (isFollowing($profile_id, $logged_user_id)) {
				$profile_data['is_following'] = 1;
			} else {
				if (isFollowRequested($profile_id, $logged_user_id)) {
					$profile_data['is_following'] = 2;
				} else {
					if ($profile_data['follow_privacy'] == 1) {
						if (isFollowing($logged_user_id, $profile_id)) {
							$profile_data['is_following'] = 0;
						}
					}
				}
			}
			$profile_data['lastseen'] = timeElapsedString($profile_data['lastseen']);
			$profile_data['is_blocked'] = isBlocked($profile_data['user_id']);
			$success_data = array(
				'status' => '200',
				'response' => 'success',
				'api_version' => $api_version,
				'user_data' => $profile_data
			);
			header("Content-type: application/json");
			echo json_encode($success_data, JSON_PRETTY_PRINT);
			exit();
		}
	} else {
		header("Content-type: application/json");
		echo json_encode($errors_data, JSON_PRETTY_PRINT);
		exit();
	}
} // end type == 'get_user_data'

if ($type == 'set_cookie') {
	if (empty($_GET['cookie'])) {
		$errors_data = array(
			'status' => '300',
			'response' => 'failed',
			'api_version' => $api_version,
			'errors' => array(
				'ID' => 'ERR_EMPTY_COOKIE',
				'error_message' => 'No cookie was provided.'
			)
		);
	}
	if (empty($errors_data)) {
		$cookie = secureIt($_GET['cookie']);
		$user_id = getUserIdFromSessionId($cookie);
		if (empty($user_id)) {
			$errors_data = array(
				'status' => '300',
				'response' => 'failed',
				'api_version' => $api_version,
				'errors' => array(
					'ID' => 'ERR_INVALID_COOKIE',
					'error_message' => 'Invalid or cookie is not exists.'
				)
			);
			header("Content-type: application/json");
			echo json_encode($errors_data, JSON_PRETTY_PRINT);
			exit();
		}
		$user_data = userData($user_id);
		if (empty($user_data)) {
			$errors_data = array(
				'status' => '300',
				'response' => 'failed',
				'api_version' => $api_version,
				'errors' => array(
					'ID' => 'ERR_EMPTY_USER_DATA',
					'error_message' => 'Username not exists.'
				)
			);
			header("Content-type: application/json");
			echo json_encode($errors_data, JSON_PRETTY_PRINT);
			exit();
		} else {
			$_SESSION['user_id'] = $cookie;
			setcookie("user_id", $cookie, time() + (10 * 356 * 24 * 60 * 60));
			header("Location: " . seoLink('index.php?page=get_news_feed'));
			exit();
		}
	} else {
		header("Content-type: application/json");
		echo json_encode($errors_data, JSON_PRETTY_PRINT);
		exit();
	}
} // end type == 'set_cookie'
mysqli_close($sql_connect);
unset($carovl);
?>