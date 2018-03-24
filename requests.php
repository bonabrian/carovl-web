<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
use Aws\S3\S3Client;

$f = '';
$s = '';
if (isset($_GET['f'])) {
	$f = secureIt($_GET['f'], 0);
}
if (isset($_GET['s'])) {
	$s = secureIt($_GET['s'], 0);
}
$hash_id = '';
if (! empty($_POST['hash_id'])) {
	$hash_id = $_POST['hash_id'];
} elseif (! empty($_GET['hash_id'])) {
	$hash_id = $_GET['hash_id'];
} elseif (! empty($_POST['hash'])) {
	$hash_id = $_POST['hash'];
} elseif (! empty($_GET['hash'])) {
	$hash_id = $_GET['hash'];
}
$data = array();

if ($f == 'session_status') {
	if ($carovl['logged_in'] == false) {
		$page_url = $_POST['page_url'];
		$data = array(
			'status' => 200,
			'href' => seoLink('index.php?page=login&redirect_to=' . $page_url . '&authentication=true')
		);
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'session_status'
if ($f == 'get_more_latest_posts') {
	$html = '';
	if (! empty($_GET['after_post_id'])) {
		$post_data = array(
			'limit' => 5,
			'after_post_id' => secureIt($_GET['after_post_id'])
		);
		$get_latest_posts = getLatestPosts($post_data);
		foreach ($get_latest_posts as $carovl['latest_post']) {
			echo loadPage('welcome/latest-post');
		}
	}
	exit();
} // end f == 'get_more_latest_posts'
if ($f == 'update_lastseen') {
	if (updateLastSeen($carovl['user']['user_id']) === true) {
		$data = array(
			'status' => 200
		);
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'update_lastseen'
if ($f == 'update_data') {
	if (checkMainSession($hash_id) === true) {
		$query = mysqli_query($sql_connect, "UPDATE " . T_SESSIONS . " SET `time` = " . time() . " WHERE `platform` = 'web' AND `user_id` = " . $carovl['user']['user_id']);
		$data['pop'] = 0;
		$data['status'] = 200;
		$data['notifications'] = countNotifications(array(
			'unread' => true
		));
		$data['html'] = '';
		$notifications = getNotifications(array(
			'type2' => 'popunder',
			'unread' => true,
			'limit' => 1
		));
		foreach ($notifications as $carovl['notification']) {
			$data['html'] = loadPage('header-notifications/notifications/content');
			$data['icon'] = $carovl['notification']['notifier']['avatar'];
			$data['title'] = $carovl['notification']['notifier']['username'];
			$data['notification_text'] = $carovl['notification']['type_text'];
			$data['url'] = $carovl['notification']['url'];
			$data['pop'] = 200;
			if ($carovl['notification']['seen'] == 0) {
				$query = mysqli_query($sql_connect, "UPDATE " . T_NOTIFICATIONS . " SET `seen_pop` = " . time() . " WHERE `id` = " . $carovl['notification']['id']);
			}
		}
		$data['messages'] = countMessages(array(
			'new' => true
		), 'interval');
		$data['follow_request'] = countFollowRequests();
		$data['count_num'] = 0;
		if ($_GET['check_posts'] == 'true') {
			if (! empty($_GET['before_post_id']) && isset($_GET['user_id'])) {
				$html = '';
				$post_data = array(
					'before_post_id' => $_GET['before_post_id'],
					'publisher_id' => $_GET['user_id'],
					'limit' => 20
				);
				$posts = getPosts($post_data);
				$count = count($posts);
				$data['count'] = str_replace('{count}', $count, $carovl['lang']['view_more_posts']);
				$data['count_num'] = $count;
			}
		} elseif ($_GET['hash_posts'] == 'true') {
			if (! empty($_GET['before_post_id']) && isset($_GET['user_id'])) {
				$html = '';
				$posts = getHashtagPosts($_GET['hashtag_name'], 0, 20, $_GET['before_post_id']);
				$count = count($posts);
				$data['count'] = str_replace('{count}', $count, $carovl['lang']['view_more_posts']);
				$data['count_num'] = $count;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	}
}
if ($f == 'show_login_modal') {
	if ($carovl['logged_in'] == false) {
		$data = array(
			'status' => 200,
			'html' => loadPage('modals/login')
		);
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'show_login_modal'
// Post Login Data
if ($f == 'login') {
	$data_ = array();
	$phone = 0;
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$username = secureIt($_POST['username']);
		$password = secureIt($_POST['password']);
		$result = loginUser($username, $password);
		if ($result === false) {
			$errors[] = $error_icon . $carovl['lang']['incorrect_username_or_password'];
		} elseif (isUserInactive($_POST['username'] === true)) {
			$errors[] = $error_icon . $carovl['lang']['account_disabled_contact_admin'];
		} elseif (isUserActive($_POST['username']) === false) {
			$_SESSION['code_id'] = userIdForLogin($username);
			$data_ = array(
				'status' => 400,
				'location' => seoLink('index.php?page=user-activation')
			);
			$phone = 1;
		}
		if (empty($errors) && $phone == 0) {
			$user_id = userIdForLogin($username);
			$ip = secureIt(getIpAddress());
			$update = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `ip_address` = '{$ip}' WHERE `user_id` = '{$user_id}'");
			$session = createLoginSession(userIdForLogin($username));
			$_SESSION['user_id'] = $session;
			setcookie(
				"user_id",
				$session,
				time() + (10 * 365 * 24 * 60 * 60)
			);
			$data = array(
				'status' => 200
			);
			if (! empty($_POST['last_url'])) {
				$data['location'] = $_POST['last_url'];
			} else {
				$data['location'] = $carovl['config']['site_url'];
			}
		}
	}
	header("Content-type: application/json");
	if (! empty($errors)) {
		echo json_encode(array('errors' => $errors));
	} elseif (! empty($data_)) {
		echo json_encode($data_);
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'login'
if ($f == 'user_activation') {
	if (isset($_SESSION['code_id'])) {
		$email = 0;
		$phone = 0;
		$success = '';
		$user_id = $_SESSION['code_id'];
		$user = userData($user_id);
		if (empty($user) || empty($user_id) || empty($_POST['email']) && empty($_POST['phone_number'])) {
			$errors[] = $error_icon . $carovl['lang']['failed_to_send_activation'];
		}
		if (! empty($_POST['email'])) {
			if (isEmailExist($_POST['email']) === true && $user['email'] != $_POST['email']) {
				$errors[] = $error_icon . $carovl['lang']['email_already_exist'];
			}
			if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errors[] = $error_icon . $carovl['lang']['invalid_email'];
			}
			if (empty($errors)) {
				$email = 1;
				$phone = 0;
				$success = $carovl['lang']['email_sent'];
			}
		} elseif (! empty($_POST['phone_number'])) {
			if (! preg_match('/^\+?\d+$/', $_POST['phone_number'])) {
				$errors[] = $error_icon . $carovl['lang']['wrong_phone_number'];
			}
			if (isPhoneExist($_POST['phone_number']) === true) {
				if ($user['phone_number'] != $_POST['phone_number']) {
					$errors[] = $error_icon . $carovl['lang']['phone_number_already_used'];
				}
			}
			if (empty($errors)) {
				$email = 0;
				$phone = 1;
				$success = $carovl['lang']['activation_code_sent'];
			}
		}
		if (empty($errors)) {
			if ($email == 1 && $phone == 0) {
				$carovl['user'] = $_POST;
				$carovl['user']['username'] = $user['username'];
				$body = loadPage('emails/activate');
				$send_message_data = array(
					'from_email' => $carovl['config']['site_email'],
					'from_name' => $carovl['config']['site_name'],
					'to_email' => $_POST['email'],
					'to_name' => $user['username'],
					'subject' => $carovl['lang']['account_activation'],
					'charSet' => 'utf-8',
					'message_body' => $body,
					'is_html' => true
				);
				$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `email` = '" . secureIt($_POST['email']) . "' WHERE `user_id` = {$user_id}");
				if ($query) {
					if (sendEmailMessage($send_message_data)) {
						$data = array(
							'status' => 200,
							'success' => $success
						);
					} else {
						$errors[] = $error_icon . $carovl['lang']['failed_to_send_email'];
					}
				}
			} elseif ($email == 0 && $phone == 1) {
				$random_activation = secureIt(rand(11111, 99999));
				$message = $carovl['lang']['your_confirmation_code_is'] . $random_activation;
				$user_id = $_SESSION['code_id'];
				$phone_number = secureIt($_POST['phone_number']);
				$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `phone_number` = '{$phone_number}' WHERE `user_id` = {$user_id}");
				$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `user_id` = {$user_id}");
				if ($query) {
					if (sendSmsMessage($_POST['phone_number'], $message) === true) {
						$data = array(
							'status' => 200,
							'success' => $success
						);
					} else {
						$errors[] = $error_icon . $carovl['lang']['error_while_sending_sms'];
					}
				}
			}
		}
	}
	header("Content-type: application/json");
	if (! empty($errors)) {
		echo json_encode(array(
			'errors' => $errors
		));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'user_activation'
if ($f == 'confirm_code') {
	if (isset($_POST['activation_code']) && isset($_POST['user_id'])) {
		$activation_code = $_POST['activation_code'];
		$user_id = $_POST['user_id'];
		if (empty($activation_code)) {
			$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
		}
		if (empty($user_id)) {
			$errors[] = $error_icon . $carovl['lang']['error_while_activating'];
		}
		if (confirmCode($user_id, $activation_code) === false) {
			$errors[] = $error_icon . $carovl['lang']['invalid_activation_code'];
		}
		if (empty($errors)) {
			$session = createLoginSession($user_id);
			$data = array(
				'status' => 200
			);
			$_SESSION['user_id'] = $session;
			setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
			if (! empty($_POST['last_url'])) {
				$data['location'] = $_POST['last_url'];
			} else {
				$data['location'] = $carovl['config']['site_url'];
			}
		}
	}
	header("Content-type: application/json");
	if (! empty($errors)) {
		echo json_encode(array(
			'errors' => $errors
		));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'confirm_code'
if ($f == 'resend_code') {
	if (isset($_SESSION['code_id'])) {
		$user = userData($_SESSION['code_id']);
		if (empty($user) || empty($_SESSION['code_id']) || empty($user['phone_number'])) {
			$errors[] = $error_icon . $carovl['lang']['failed_to_send_activation'];
		}
		if (empty($errors)) {
			$random_activation = secureIt(rand(11111, 99999));
			$message = $carovl['lang']['your_confirmation_code_is'] . $random_activation;
			$user_id = $user['user_id'];
			$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `user_id` = {$user_id}");
			if ($query) {
				if (sendSmsMessage($user['phone_number'], $message) === true) {
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['sms_has_been_sent']
					);
				} else {
					$errors[] = $error_icon . $carovl['lang']['error_while_sending_sms'];
				}
			}
		}
	}
	header("Content-type: application/json");
	if (! empty($errors)) {
		echo json_encode(array(
			'errors' => $errors
		));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'resend_code'
// Post Register Data
if ($f == 'register') {
	if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
		$errors[] = $error_icon . $carovl['lang']['please_fill_all_the_fields'];
	} else {
		$is_exist = isUsernameExist($_POST['username'], 1);
		if (in_array(true, $is_exist)) {
			$errors[] = $error_icon . $carovl['lang']['username_already_exist'];
		}
		if (empty($_POST['phone_number']) && $carovl['config']['sms_or_email'] == 'sms') {
			$errors[] = $error_icon . $carovl['lang']['wrong_phone_number'];
		}
		if (in_array($_POST['username'], $carovl['site_pages'])) {
			$errors[] = $error_icon . $carovl['lang']['cannot_use_this_username'];
		}
		if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 32) {
			$errors[] = $error_icon . $carovl['lang']['username_characters_length'];
		}
		if (! preg_match('/^[\w]+$/', $_POST['username'])) {
			$errors[] = $error_icon . $carovl['lang']['invalid_username_characters'];
		}
		if (! empty($_POST['phone_number'])) {
			if (! preg_match('/^\+?\d+$/', $_POST['phone_number'])) {
				$errors[] = $error_icon . $carovl['lang']['wrong_phone_number'];
			} else {
				if (isPhoneExist($_POST['phone_number']) === true) {
					$errors[] = $error_icon . $carovl['lang']['phone_number_already_used'];
				}
			}
		}
		if (isEmailExist($_POST['email']) === true) {
			$errors[] = $error_icon . $carovl['lang']['email_already_exist'];
		}
		if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = $error_icon . $carovl['lang']['invalid_email'];
		}
		if (strlen($_POST['password']) < 6) {
			$errors[] = $error_icon . $carovl['lang']['password_too_short'];
		}
		if ($_POST['password'] != $_POST['confirm_password']) {
			$errors[] = $error_icon . $carovl['lang']['password_mismatch'];
		}
		if ($config['reCaptcha'] == 1) {
			if (! isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
				$errors[] = $error_icon . $carovl['lang']['reCaptcha_error'];
			}
		}
	}
	if (empty($errors)) {
		$activate = ($carovl['config']['email_validation'] == '1') ? '0' : '1';
		$re_data = array(
			'username' => secureIt($_POST['username'], 0),
			'email' => secureIt($_POST['email'], 0),
			'password' => secureIt($_POST['password'], 0),
			'email_code' => secureIt(md5($_POST['username']), 0),
			'src' => 'site',
			'avatar' => getDefaultAvatar($_POST['username']),
			'lastseen' => time(),
			'active' => secureIt($activate)
		);
		if (! empty($_POST['phone_number'])) {
			$re_data['phone_number'] = secureIt($_POST['phone_number']);
		}
		$register = registerUser($re_data);
		if ($register === true) {
			if ($activate == 1) {
				$data = array(
					'status' => 200,
					'success' => $carovl['lang']['successfully_joined_label']
				);
				$login = loginUser($_POST['username'], $_POST['password']);
				if ($login === true) {
					$session = createLoginSession(userIdFromUsername($_POST['username']));
					$_SESSION['user_id'] = $session;
					setcookie(
						"user_id",
						$session,
						time() + (10 * 365 * 24 * 60 * 60)
					);
				}
				$data['location'] = seoLink('index.php?page=getstarted');
			} elseif ($carovl['config']['sms_or_email'] == 'mail') {
				$carovl['user'] = $_POST;
				$body = loadPage('emails/activate');
				$send_message_data = array(
					'from_email' => $carovl['config']['site_email'],
					'from_name' => $carovl['config']['site_name'],
					'to_email' => $_POST['email'],
					'to_name' => $_POST['username'],
					'subject' => $carovl['lang']['account_activation'],
					'charSet' => 'utf-8',
					'message_body' => $body,
					'is_html' => true
				);
				$send = sendEmailMessage($send_message_data);
				if ($send === true) {
					$data['success'] = $carovl['lang']['successfully_joined_verify_label'];
				} else {
					$errors[] = $error_icon . $carovl['lang']['failed_to_send_email'];
				}
			} elseif ($carovl['config']['sms_or_email'] == 'sms' && ! empty($_POST['phone_number'])) {
				$random_activation = secureIt(rand(11111, 99999));
				$message = $carovl['lang']['your_confirmation_code_is'] . $random_activation;
				$user_id = userIdFromUsername($_POST['username']);
				$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `user_id` = {$user_id}");
				if ($query) {
					if (sendSmsMessage($_POST['phone_number'], $message) === true) {
						$data = array(
							'status' => 300,
							'location' => seoLink('index.php?page=confirm-sms?code=' . secureIt(md5($_POST['username']), 0))
						);
					} else {
						$errors[] = $error_icon . $carovl['lang']['failed_to_send_sms_code'];
					}
				}
			}
		}
	}
	header("Content-type: application/json");
	if (isset($errors)) {
		echo json_encode(array(
			'errors' => $errors
		));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'register'
if ($f == 'getstarted') {
	if ($s == 'update_avatar') {
		if (isset($_FILES['avatar']['name'])) {
			if (uploadImage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['user_id']) === true) {
				$img = userData($_POST['user_id']);
				$data = array(
					'status' => 200,
					'image' => $img['avatar'],
					'image_org' => $img['avatar_org'],
					'nice' => $carovl['lang']['looks_nice'],
					'cool' => $carovl['lang']['cool']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_avatar'
	if ($s == 'update_info') {
		if (isset($_POST['user_id'])) {
			$user = userData($_POST['user_id']);
			if (! empty($user['user_id'])) {
				$age_data = '00-00-0000';
				if (! empty($_POST['year']) || ! empty($_POST['day']) || ! empty($_POST['month'])) {
					if (empty($_POST['year']) || empty($_POST['day']) || empty($_POST['month'])) {
						$errors[] = $error_icon . $carovl['lang']['please_choose_correct_date'];
					} else {
						$age_data = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
					}
				}
				$update_data = array(
					'first_name' => secureIt(ucfirst($_POST['first_name'])),
					'last_name' => secureIt(ucfirst($_POST['last_name'])),
					'birthday' => $age_data,
					'getstarted_info' => 1,
					'getstarted' => 1
				);
				if (updateUserData($_POST['user_id'], $update_data)) {
					$data = array(
						'status' => 200,
						'location' => $carovl['config']['site_url']
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_info'
	if ($s == 'skip_step') {
		if (! empty($_GET['type'])) {
			$types = array(
				'getstarted_image',
				'getstarted_info',
				'getstarted_follow'
			);
			if (in_array($_GET['type'], $types)) {
				$register_skip = updateUserData($carovl['user']['user_id'], array($_GET['type'] => 1));
				if ($register_skip === true) {
					$data = array(
						'status' => 200
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'skip_step'
} // end f == 'getstarted'
if ($f == 'forgot_password') {
	if (empty($_POST['email'])) {
		$errors[] = $error_icon . $carovl['lang']['please_fill_all_the_fields'];
	} else {
		if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = $error_icon . $carovl['lang']['invalid_email'];
		} elseif (isEmailExist($_POST['email']) === false) {
			$errors[] = $error_icon . $carovl['lang']['email_not_found'];
		}
	}
	if (empty($errors)) {
		$user = userData(userIdFromEmail($_POST['email']));
		$subject = $config['site_name'] . ' - ' . $carovl['lang']['password_reset_request'];
		$user['link'] = isLink('index.php?page=reset-password&code=' . $user['user_id'] . '_' . $user['password']);
		$carovl['recover'] = $user;
		$body = loadPage('emails/recover');
		$send_message_data = array(
			'from_email' => $carovl['config']['site_email'],
			'from_name' => $carovl['config']['site_name'],
			'to_email' => $_POST['email'],
			'to_name' => getUsernameFromEmail($_POST['email'], $user['user_id']),
			'subject' => $subject,
			'charSet' => 'utf-8',
			'message_body' => $body,
			'is_html' => true
		);
		$send = sendEmailMessage($send_message_data);
		if ($send) {
			$data = array(
				'status' => 200,
				'success' => $success_icon . $carovl['lang']['email_sent']
			);
		} else {
			$errors[] = $error_icon . $carovl['lang']['failed_to_send_email'];
		}
	}
	header("Content-type: application/json");
	if (isset($errors)) {
		echo json_encode(array('errors' => $errors));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'forgot_password'
if ($f == 'reset_password') {
	if (isset($_POST['id'])) {
		if (isValidPasswordResetToken($_POST['id']) === false) {
			$errors[] = $error_icon . $carovl['lang']['invalid_token'];
		} elseif (empty($_POST['id'])) {
			$errors[] = $error_icon . $carovl['lang']['processing_error'];
		} elseif (empty($_POST['password'])) {
			$errors[] = $error_icon . $carovl['lang']['please_fill_all_the_fields'];
		} elseif (strlen($_POST['password']) < 5) {
			$errors[] = $error_icon . $carovl['lang']['password_too_short'];
		}
		if (empty($errors)) {
			$user_id = explode('_', $_POST['id']);
			$password = secureIt($_POST['password']);
			if (resetPassword($user_id[0], $password) === true) {
				$_SESSION['user_id'] = createLoginSession($user_id[0]);
			}
			$data = array(
				'status' => 200,
				'success' => $carovl['lang']['welcome_back'],
				'location' => $carovl['config']['site_url']
			);
		}
	}
	header("Content-type: application/json");
	if (isset($errors)) {
		echo json_encode(array('errors' => $errors));
	} else {
		echo json_encode($data);
	}
	exit();
} // end f == 'reset_password'
if ($f == 'home') {
	if ($s == 'load_posts') {
		echo loadPage('home/load-posts');
		exit();
	} // end s == 'load_posts'
} // end f == 'home'
if ($f == 'posts') {
	if ($s == 'fetch_url') {
		if (isset($_POST['url'])) {
			$get_url = $_POST['url'];
			include_once('assets/import/simple_html_dom.inc.php');
			$get_content = file_get_html($get_url);
			foreach ($get_content->find('title') as $element) {
				@$page_title = $element->plaintext;
			}
			if (empty($page_title)) {
				$page_title = '';
			}
			@$page_body = $get_content->find("meta[name='description']", 0)->content;
			$page_body = mb_substr($page_body, 0, 250, "utf-8");
			if ($page_body === false) {
				$page_body = '';
			}
			if (empty($page_body)) {
				@$page_body = $get_content->find("meta[property='og:description']", 0)->content;
				$page_body = mb_substr($page_body, 0, 250, "utf-8");
				if ($page_body === false) {
					$page_body = '';
				}
			}
			$image_url = array();
			@$page_image = $get_content->find("meta[property='og:image']", 0)->content;
			if (! empty($page_image)) {
				if (preg_match('/[\w\-]+\.(jpg|png|gif|jpeg)/', $page_image)) {
					$image_url[] = $page_image;
				}
			} else {
				foreach ($get_content->find('img') as $element) {
					if (! preg_match('/blank.(.*)/i', $element->src)) {
						if (preg_match('/[\w\-]+\.(jpg|png|gif|jpeg)/', $element->src)) {
							$image_url[] = $element->src;
						}
					}
				}
			}
			$output = array(
				'title' => $page_title,
				'images' => $image_url,
				'content' => $page_body,
				'url' => $_POST['url']
			);
			echo json_encode($output);
			exit();
		}
	}
	if ($s == 'new_post') {
		$media = '';
		$media_filename = '';
		$media_name = '';
		$html = '';
		$group_id = 0;
		$event_id = 0;
		$image_array = array();

		if (checkSession($hash_id) === false) {
			return false;
			die();
		}
		if (isset($_POST['group_id']) && ! empty($_POST['group_id']) && is_numeric($_POST['group_id']) && $_POST['group_id'] > 0) {
			$group_id = secureIt($_POST['group_id']);
			$group = groupData($group_id);
			if (! empty($group['id'])) {
				if ($group['privacy'] == 0) {
					$_POST['post_privacy'] = 0;
				} elseif ($group['privacy'] == 1) {
					$_POST['post_privacy'] = 2;
				}
			}
		} elseif (isset($_POST['event_id']) && ! empty($_POST['event_id'])) {
			$event_id = secureIt($_POST['event_id']);
		}
		// Files
		if (isset($_FILES['post_file']['name'])) {
			$file_info = array(
				'file' => $_FILES['post_file']['tmp_name'],
				'name' => $_FILES['post_file']['name'],
				'size' => $_FILES['post_file']['size'],
				'type' => $_FILES['post_file']['type']
			);
			$media = shareFile($file_info);
			if (! empty($media)) {
				$media_filename = $media['filename'];
				$media_name = $media['name'];
			}
		}

		// Videos
		if (isset($_FILES['post_video']['name'])) {
			$file_info = array(
				'file' => $_FILES['post_video']['tmp_name'],
				'name' => $_FILES['post_video']['name'],
				'size' => $_FILES['post_video']['size'],
				'type' => $_FILES['post_video']['type'],
				'types' => 'mp4,mv4,webm,flv,mov,mpeg'
			);
			$media = shareFile($file_info);
			if (! empty($media)) {
				$media_filename = $media['filename'];
				$media_name = $media['name'];
			}
		}

		// Audios
		if (isset($_FILES['post_music']['name'])) {
			$file_info = array(
				'file' => $_FILES['post_music']['tmp_name'],
				'name' => $_FILES['post_music']['name'],
				'size' => $_FILES['post_music']['size'],
				'type' => $_FILES['post_music']['type'],
				'types' => 'mp3,wav'
			);
			$media = shareFile($file_info);
			if (! empty($media)) {
				$media_filename = $media['filename'];
				$media_name = $media['name'];
			}
		}

		$multi = 0;
		if (isset($_FILES['post_photos']['name']) && empty($media_filename) && empty($_POST['album_name'])) {
			if (count($_FILES['post_photos']['name']) == 1) {
				$file_info = array(
					'file' => $_FILES['post_photos']['tmp_name'][0],
					'name' => $_FILES['post_photos']['name'][0],
					'size' => $_FILES['post_photos']['size'][0],
					'type' => $_FILES['post_photos']['type'][0]
				);
				$media = shareFile($file_info);
				if (! empty($media)) {
					$media_filename = $media['filename'];
					$media_name = $media['name'];
				}
			} else {
				$multi = 1;
			}
		}
		if (empty($_POST['post_privacy'])) {
			$_POST['post_privacy'] = 0;
		}
		$post_privacy = 0;
		$privacy_array = array('0', '1', '2', '3');
		if (isset($_POST['post_privacy'])) {
			if (in_array($_POST['post_privacy'], $privacy_array)) {
				$post_privacy = $_POST['post_privacy'];
			}
		}
		$import_url_image = '';
		$url_link = '';
		$url_content = '';
		$url_title = '';
		if (! empty($_POST['url_link']) && ! empty($_POST['url_title'])) {
			$url_link = $_POST['url_link'];
			$url_title = $_POST['url_title'];
			if (! empty($_POST['url_content'])) {
				$url_content = $_POST['url_content'];
			}
			if (! empty($_POST['url_image'])) {
				$import_url_image = @importImageFromUrl($_POST['url_image']);
			}
		}
		$post_text = '';
		$post_map = '';
		if (! empty($_POST['post_text']) && ! ctype_space($_POST['post_text'])) {
			$post_text = $_POST['post_text'];
		}
		if (! empty($_POST['post_map'])) {
			$post_map = $_POST['post_map'];
		}
		$album_name = '';
		if (! empty($_POST['album_name'])) {
			$album_name = $_POST['album_name'];
		}
		if (! isset($_FILES['post_photos']['name'])) {
			$album_name = '';
		}
		if (isset($_FILES['post_photos']['name'])) {
			$allowed = array(
				'jpg',
				'gif',
				'jpeg',
				'png'
			);
			for ($i=0; $i < count($_FILES['post_photos']['name']); $i++) { 
				$new_string = pathinfo($_FILES['post_photos']['name'][$i]);
				if (! in_array(strtolower($new_string['extension']), $allowed)) {
					$errors[] = $error_icon . $carovl['lang']['invalid_extension'];
				}
			}
		}
		if (empty($errors)) {
			$post_data = array(
				'user_id' => secureIt($carovl['user']['user_id']),
				'group_id' => secureIt($group_id),
				'event_id' => secureIt($event_id),
				'post_text' => secureIt($post_text),
				'post_file' => secureIt($media_filename, 0),
				'post_file_name' => secureIt($media_name),
				'post_map' => secureIt($post_map),
				'post_privacy' => secureIt($post_privacy),
				'post_link_title' => secureIt($url_title),
				'post_link' => secureIt($url_link),
				'post_link_image' => secureIt($import_url_image, 0),
				'post_link_content' => secureIt($url_content),
				'album_name' => secureIt($album_name),
				'multi_image' => secureIt($multi),
				'time' => time()
			);
			$id = registerPost($post_data);
			if ($id) {
				if (isset($_FILES['post_photos']['name'])) {
					if (count($_FILES['post_photos']['name']) > 0) {
						for ($i=0; $i < count($_FILES['post_photos']['name']); $i++) { 
							$file_info = array(
								'file' => $_FILES['post_photos']['tmp_name'][$i],
								'name' => $_FILES['post_photos']['name'][$i],
								'size' => $_FILES['post_photos']['size'][$i],
								'type' => $_FILES['post_photos']['type'][$i],
								'types' => 'jpg,png,jpeg,gif'
							);
							$media = shareFile($file_info, 1);
							if (! empty($media)) {
								$media_album = registerAlbumMedia($id, $media['filename']);
							}
						}
					}
				}
				$carovl['story'] = postData($id);
				$html .= loadPage('story/content');
				$data = array(
					'status' => 200,
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'new_post'
	if ($s == 'edit_post') {
		if (! empty($_POST['post_id']) && ! empty($_POST['text'])) {
			$update_post = updatePost(array(
				'post_id' => $_POST['post_id'],
				'text' => $_POST['text'],
				'edited' => time()
			));
			if (! empty($update_post)) {
				$data = array(
					'status' => 200,
					'html' => $update_post
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'edit_post'
	if ($s == 'delete_post' && checkMainSession($hash_id) === true) {
		if (! empty($_GET['post_id'])) {
			if (deletePost($_GET['post_id']) === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_post'
	if ($s == 'like_post') {
		if (! empty($_GET['post_id']) && checkMainSession($hash_id) === true) {
			if (registerLike($_GET['post_id']) == 'unliked') {
				$text = '';
				$respons = countPostNotes($_GET['post_id']);
				if ($respons == 1) {
					$text = $respons . ' ' . $carovl['lang']['note'];
				} elseif ($respons == 0) {
					$text = '';
				} else {
					$text = $respons . ' ' . $carovl['lang']['notes'];
				}
				$data = array(
					'status' => 300,
					'notes' => $text
				);
			} else {
				$text = '';
				$respons = countPostNotes($_GET['post_id']);
				if ($respons == 1) {
					$text = $respons . ' ' . $carovl['lang']['note'];
				} elseif ($respons == 0) {
					$text = '';
				} else {
					$text = $respons . ' ' . $carovl['lang']['notes'];
				}
				$data = array(
					'status' => 200,
					'notes' => $text
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'like_post'
	if ($s == 'register_comment') {
		if (! empty($_POST['post_id']) && isset($_POST['text']) && checkMainSession($hash_id) === true) {
			$html = '';
			$text_comment = '';
			if (! empty($_POST['text']) && ! ctype_space($_POST['text'])) {
				$text_comment = $_POST['text'];
			}
			$comment_data = array(
				'user_id' => secureIt($carovl['user']['user_id']),
				'post_id' => secureIt($_POST['post_id']),
				'text' => secureIt($text_comment),
				'time' => time()
			);
			$register_comment = registerPostComment($comment_data);
			$carovl['comment'] = commentData($register_comment);
			$carovl['story'] = postData($_POST['post_id']);
			$respons = countPostNotes($_POST['post_id']);
			$comments = countPostComments($_POST['post_id']);
			if ($respons == 1) {
				$notes = $respons . ' ' . $carovl['lang']['note'];
			} elseif ($respons == 0) {
				$notes = '';
			} else {
				$notes = $respons . ' ' . $carovl['lang']['notes'];
			}
			if ($comments == 1) {
				$comment = $comments . ' ' . $carovl['lang']['comment'];
			} elseif ($comments == 0) {
				$comment = '';
			} else {
				$comment = $comments . ' ' . $carovl['lang']['comments'];
			}
			if (! empty($carovl['comment'])) {
				$html = loadPage('popover/post-activity/user-comments');
				$data = array(
					'status' => 200,
					'html' => $html,
					'notes' => $notes,
					'comments' => $comment
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'register_comment'
	if ($s == 'load_more_comments') {
		if (! empty($_GET['post_id'])) {
			$html = '';
			$carovl['story'] = postData($_GET['post_id']);
			foreach (getPostComments($_GET['post_id'], countPostComments($_GET['post_id'])) as $carovl['comment']) {
				$html .= loadPage('comments/content');
			}
			$data = array(
				'status' => 200,
				'html' => $html
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'load_more_comments'
	if ($s == 'load_all_comments') {
		if (! empty($_GET['post_id'])) {
			$html = '';
			$carovl['story'] = postData($_GET['post_id']);
			foreach (getPostComments($_GET['post_id'], countPostComments($_GET['post_id'])) as $carovl['comment']) {
				$html .= loadPage('popover/post-activity/user-comments');
			}
			$data = array(
				'status' => 200,
				'html' => $html
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'load_all_comments'
	if ($s == 'get_more_posts') {
		$html = '';
		if (! empty($_GET['after_post_id'])) {
			$group_id = 0;
			$user_id = 0;
			$event_id = 0;
			if (! empty($_GET['group_id']) && $_GET['group_id'] > 0) {
				$group_id = secureIt($_GET['group_id']);
			}
			if (! empty($_GET['user_id']) && $_GET['user_id'] > 0) {
				$user_id = secureIt($_GET['user_id']);
			}
			if (! empty($_GET['event_id']) && $_GET['event_id'] > 0) {
				$event_id = secureIt($_GET['event_id']);
			}
			$post_data = array(
				'limit' => 5,
				'publisher_id' => $user_id,
				'group_id' => $group_id,
				'event_id' => $event_id,
				'after_post_id' => secureIt($_GET['after_post_id'])
			);
			if ($carovl['logged_in'] == true) {
				foreach (getPosts($post_data) as $carovl['story']) {
					echo loadPage('story/content');
				}
				$get_posts = getPosts($post_data);
				if (! empty($_GET['posts_count']) && ! empty($get_posts)) {
					if ($_GET['posts_count'] > 9 && $_GET['posts_count'] < 15) {
						echo getAds('post_first', false);
					} elseif ($_GET['posts_count'] > 20 && $_GET['posts_count'] < 28) {
						echo getAds('post_second', false);
					} elseif ($_GET['posts_count'] > 29) {
						echo getAds('post_third', false);
					}
				}
			}
		}
		exit();
	} // end s == 'get_more_posts'
	if ($s == 'no_more_posts') {
		$data = array(
			'info' => $carovl['lang']['no_more_posts']
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'no_more_posts'
	if ($s == 'get_new_posts') {
		if (! empty($_GET['before_post_id']) && isset($_GET['user_id'])) {
			$html = '';
			$post_data = array(
				'before_post_id' => $_GET['before_post_id'],
				'publisher_id' => $_GET['user_id'],
				'limit' => 20
			);
			$posts = getPosts($post_data);
			foreach ($posts as $carovl['story']) {
				echo loadPage('story/content');
			}
		}
		exit();
	} // end s == 'get_new_posts'
	if ($s == 'register_comment_like') {
		if (! empty($_POST['comment_id']) && checkMainSession($hash_id) === true) {
			if (registerCommentLike($_POST['comment_id'], $_POST['comment_text']) == 'unliked') {
				$text = '';
				$comment_likes = countCommentLikes($_POST['comment_id']);
				if ($comment_likes == 0) {
					$text = '';
				} elseif ($comment_likes > 0) {
					$text = $comment_likes;
				}
				$data = array(
					'status' => 300,
					'likes' => $text
				);
			} else {
				$text = '';
				$comment_likes = countCommentLikes($_POST['comment_id']);
				if ($comment_likes == 0) {
					$text = '';
				} elseif ($comment_likes > 0) {
					$text = $comment_likes;
				}
				$data = array(
					'status' => 200,
					'likes' => $text
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'register_comment_like'
	if ($s == 'save_post') {
		if (! empty($_GET['post_id']) && checkMainSession($hash_id) === true) {
			$post_data = array(
				'post_id' => $_GET['post_id']
			);
			if (savePost($post_data) == 'unsaved') {
				$data = array(
					'status' => 300,
					'text' => $carovl['lang']['save_post']
				);
			} else {
				$data = array(
					'status' => 200,
					'text' => $carovl['lang']['unsave_post']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'save_post'
	if ($s == 'report_post') {
		if (! empty($_GET['post_id'])) {
			$post_data = array(
				'post_id' => $_GET['post_id']
			);
			if (reportPost($post_data) == 'unreport') {
				$data = array(
					'status' => 300,
					'text' => $carovl['lang']['report_post']
				);
			} else {
				$data = array(
					'status' => 200,
					'text' => $carovl['lang']['unreport_post']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'report_post'
	if ($s == 'get_new_hashtag_posts') {
		$html = '';
		if (! empty($_GET['before_post_id']) && ! empty($_GET['hashtag_name'])) {
			$posts = getHashtagPosts($_GET['hashtag_name'], 0, 20, $_GET['before_post_id']);
			foreach ($posts as $carovl['story']) {
				echo loadPage('story/content');
			}
		}
		exit();
	} // end s == 'get_new_hashtag_posts'
	if ($s == 'get_more_hashtag_posts') {
		$html = '';
		if (isset($_POST['after_post_id'])) {
			$after_post_id = secureIt($_POST['after_post_id']);
			foreach (getHashtagPosts($_POST['hashtag_name'], $after_post_id, 20) as $carovl['story']) {
				$html .= loadPage('story/content');
			}
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_hashtag_posts'
	if ($s == 'delete_comment') {
		if (! empty($_GET['comment_id']) && checkMainSession($hash_id) === true) {
			$delete_comment = deletePostComment($_GET['comment_id']);
			$post_id = secureIt($_GET['post_id']);
			$respons = countPostNotes($_GET['post_id']);
			$comments = countPostComments($_GET['post_id']);
			if ($respons == 1) {
				$notes = $respons . ' ' . $carovl['lang']['note'];
			} elseif ($respons == 0) {
				$notes = '';
			} else {
				$notes = $respons . ' ' . $carovl['lang']['notes'];
			}
			if ($comments == 1) {
				$comment = $comments . ' ' . $carovl['lang']['comment'];
			} elseif ($comments == 0) {
				$comment = '';
			} else {
				$comment = $comments . ' ' . $carovl['lang']['comments'];
			}
			if ($delete_comment === true) {
				$data = array(
					'status' => 200,
					'notes' => $notes,
					'comments' => $comment
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_comment'
	if ($s == 'search_posts') {
		$html = '';
		if (! empty($_GET['query'])) {
			$search_data = searchPosts($_GET['id'], $_GET['query'], 20, $_GET['type']);
			if (count($search_data) == 0) {
				$html = loadPage('story/query-no-stories');
			} else {
				foreach ($search_data as $carovl['story']) {
					$html .= loadPage('story/content');
				}
			}
			$data = array(
				'status' => 200,
				'html' => $html
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'search_for_posts'
	if ($s == 'register_share') {
		if (! empty($_GET['post_id']) && checkMainSession($hash_id) === true) {
			if (registerShare($_GET['post_id']) == 'unshare') {
				$data = array(
					'status' => 300,
					//'shares' => countShares($_GET['post_id'])
				);
			} else {
				$data = array(
					'status' => 200,
					//'shares' => countShares($_GET['post_id'])
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'register_share'
	if ($s == 'view_video' && isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
		$post_id = secureIt($_GET['post_id']);
		if (viewVideo($post_id)) {
			$views = countVideoViews($post_id);
			$data['status'] = 200;
			$data['views'] = strtolower($views['views']);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	}
} // end f == 'posts'
if ($f == 'get_post_data') {
	if (! empty($_GET['post_id']) && checkMainSession($hash_id) === true) {
		$data = array(
			'status' => 200
		);
		$notes = countPostNotes($_GET['post_id']);
		$likes = countPostLikes($_GET['post_id']);
		$comments = countPostComments($_GET['post_id']);
		if ($notes == 1) {
			$data['notes'] = $notes . ' ' . $carovl['lang']['note'];
		} elseif ($notes == 0) {
			$data['notes'] = '';
		} else {
			$data['notes'] = $notes . ' ' . $carovl['lang']['notes'];
		}
		if ($likes == 1) {
			$data['likes'] = $likes . ' ' . $carovl['lang']['like'];
		} elseif ($likes == 0) {
			$data['likes'] = '';
		} else {
			$data['likes'] = $likes . ' ' . strtolower($carovl['lang']['likes']);
		}
		if ($comments == 1) {
			$data['comments'] = $comments . ' ' . $carovl['lang']['comment'];
		} elseif ($comments == 0) {
			$data['comments'] = '';
		} else {
			$data['comments'] = $comments . ' ' . $carovl['lang']['comments'];
		}
		$carovl['story'] = postData($_GET['post_id']);
		$user_likes = getPostLikes($_GET['post_id']);
		$data['user_likes'] = '';
		foreach ($user_likes as $carovl['like']) {
			$data['user_likes'] .= loadPage('popover/post-activity/user-likes');
		}
		$user_comments = getPostComments($_GET['post_id']);
		$data['user_comments'] = '';
		if (count($user_comments) > 0) {
			foreach ($user_comments as $carovl['comment']) {
				$data['user_comments'] .= loadPage('popover/post-activity/user-comments');
			}	
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'get_post_data'
// Open Lightbox Image
if ($f == 'open_image') {
	$html = '';
	if (! empty($_GET['post_id'])) {
		$carovl['story'] = postData($_GET['post_id']);
		if (! empty($carovl['story'])) {
			$html = loadPage('lightbox/content');
		}
	}
	$data = array(
		'status' => 200,
		'html' => $html
	);
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'open_image'
if ($f == 'open_album_image') {
	$html = '';
	if (! empty($_GET['image_id'])) {
		$data_image = array(
			'id' => $_GET['image_id']
		);
		if ($_GET['type'] == 'album') {
			# code...
		} else {
			$carovl['image'] = productImageData($data_image);
			if (! empty($carovl['image'])) {
				$html = loadPage('lightbox/product-image');
			}
		}
	}
	$data = array(
		'status' => 200,
		'html' => $html
	);
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'open_album_image'
if ($f == 'mention') {
	$html_data = array();
	$data_final = array();
	$following = getFollowingSuggestions(5, $_GET['term']);
	header("Content-type: application/json");
	echo json_encode(array(
		$following
	));
	exit();
} // end f == 'mention'

// ------ NOTIFICATIONS ------
// Get Messages
if ($f == 'notifications') {
	if ($s == 'get_messages') {
		if (checkMainSession($hash_id) === true) {
			$data = array(
				'status' => 200,
				'html' => ''
			);
			$messages = getMessagesUser($carovl['user']['user_id'], '', 4);
			if (count($messages) > 0) {
				foreach ($messages as $carovl['message']) {
					$data['html'] .= loadPage('header-notifications/messages/content');
				}
			} else {
				$data['no_message_icebreaker'] = loadPage('header-notifications/messages/no-messages');
			}
			$data['see_all'] = $carovl['lang']['see_all_messages'];
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_messages'
	if ($s == 'get_notifications') {
		$data = array(
			'status' => 200,
			'html' => ''
		);
		$notifications = getNotifications();
		if (count($notifications) > 0) {
			foreach ($notifications as $carovl['notification']) {
				$data['html'] .= loadPage('header-notifications/notifications/content');
				$data['all_notification'] = $carovl['lang']['see_all_notifications'];
				$data['href'] = seoLink('index.php?page=notifications');
				if ($carovl['notification']['seen'] == 0) {
					$query = mysqli_query($sql_connect, "UPDATE " . T_NOTIFICATIONS . " SET `seen` = " . time() . " WHERE `id` = " . $carovl['notification']['id']);
				}
			}
		} else {
			$data['no_notification'] = loadPage('header-notifications/notifications/no-notifications');
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_notifications'
	if ($s == 'get_follow_requests') {
		$data = array(
			'status' => 200,
			'html' => ''
		);
		$requests = getFollowRequests();
		if (count($requests) > 0) {
			foreach ($requests as $carovl['request']) {
				$data['html'] .= loadPage('header-notifications/follow-requests/content');
			}
		} else {
			$data['no_requests'] = loadPage('header-notifications/follow-requests/no-requests');
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_follow_requests'
	if ($s == 'accept_follow_request') {
		if (isset($_GET['following_id'])) {
			if (acceptFollowRequest($_GET['following_id'], $carovl['user']['user_id'])) {
				$data = array(
					'status' => 200,
					'html' => getFollowButton($_GET['following_id'])
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'accept_follow_request'
	if ($s == 'reject_follow_request') {
		if (isset($_GET['following_id'])) {
			if (rejectFollowRequest($_GET['following_id'], $carovl['user']['user_id'])) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'reject_follow_request'
} // end f == 'notifications'
if ($f == 'settings') {
	if ($s == 'update_user_account') {
		if (isset($_POST) && checkSession($hash_id) === true) {
			if (empty($_POST['username']) || empty($_POST['email'])) {
				$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
			} else {
				$user = userData($_POST['user_id']);
				$age_data = '0000-00-00';
				if (! empty($user['user_id'])) {
					if ($_POST['email'] != $user['email']) {
						if (isEmailExist($_POST['email'])) {
							$errors[] = $error_icon . $carovl['lang']['email_already_exist'];
						}
					}
					if (! empty($_POST['phone_number'])) {
						if ($_POST['phone_number'] != $user['phone_number']) {
							if (isPhoneExist($_POST['phone_number'])) {
								$errors[] = $error_icon . $carovl['lang']['phone_number_already_used'];
							}
						}
					}
					if ($_POST['username'] != $user['username']) {
						$is_exist = isUsernameExist($_POST['username'], 0);
						if (in_array(true, $is_exist)) {
							$errors[] = $error_icon . $carovl['lang']['username_already_exist'];
						}
					}
					if (in_array($_POST['username'], $carovl['site_pages'])) {
						$errors[] = $error_icon . $carovl['lang']['cannot_use_this_username'];
					}
					if (! empty($_POST['website'])) {
						if (! filter_var($_POST['website'], FILTER_VALIDATE_URL)) {
							$errors[] = $error_icon . $carovl['lang']['invalid_website'];
						}
					}
					if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
						$errors[] = $error_icon . $carovl['lang']['invalid_email'];
					}
					if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 32) {
						$errors[] = $error_icon . $carovl['lang']['username_characters_length'];
					}
					if (! preg_match('/^[\w]+$/', $_POST['username'])) {
						$errors[] = $error_icon . $carovl['lang']['invalid_username_characters'];
					}
					if (! empty($_POST['year']) || ! empty($_POST['day']) || ! empty($_POST['month'])) {
						if (empty($_POST['year']) || empty($_POST['day']) || empty($_POST['month'])) {
							$errors[] = $error_icon . $carovl['lang']['please_choose_correct_date'];
						} else {
							$age_data = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
						}
					}
					$active = $user['active'];
					if (! empty($_POST['active'])) {
						if ($_POST['active'] == 'active') {
							$active = 1;
						} else {
							$active = 2;
						}
						if ($active == $user['active']) {
							$active = $user['active'];
						}
					}
					$type = $user['admin'];
					if (! empty($_POST['type']) && isAdmin()) {
						if ($_POST['type'] == 'admin') {
							$type = 1;
						} elseif ($_POST['type'] == 'user') {
							$type = 0;
						} elseif ($_POST['type'] == 'moderator') {
							$type = 2;
						}
					}
					$gender = 'male';
					$gender_ar = array(
						'male',
						'female'
					);
					if (! empty($_POST['gender'])) {
						if (in_array($_POST['gender'], $gender_ar)) {
							$gender = $_POST['gender'];
						}
					}
					if (empty($errors)) {
						$update_data = array(
							'username' => $_POST['username'],
							'first_name' => ucfirst($_POST['first_name']),
							'last_name' => ucfirst($_POST['last_name']),
							'about' => $_POST['bio'],
							'email' => $_POST['email'],
							'address' => $_POST['address'],
							'phone_number' => $_POST['phone_number'],
							'website' => $_POST['website'],
							'birthday' => $age_data,
							'gender' => $gender,
							'active' => $active,
							'admin' => $type
						);
						if (! empty($_POST['verification'])) {
							if ($_POST['verification'] == 'verified') {
								$verification = 1;
							} else {
								$verification = 0;
							}
							if ($verification == $user['verified']) {
								$verification = $user['verified'];
							}
							$update_data['verified'] = $verification;
						}
						if (updateUserData($_POST['user_id'], $update_data)) {
							$data = array(
								'status' => 200,
								'success' => $success_icon . $carovl['lang']['setting_successfully_updated'],
								'username_link' => seoLink('index.php?page=timeline&u=' . secureIt($_POST['username'])),
								'username' => $_POST['username']
							);
						}
					}
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array(
				'errors' => $errors
			));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'update_account_setting'
	if ($s == 'update_user_password') {
		if (isset($_POST['user_id']) && checkSession($hash_id) === true) {
			$user = userData($_POST['user_id']);
			if (! empty($user['user_id'])) {
				if ($_POST['user_id'] != $carovl['user']['user_id']) {
					$_POST['current_password'] = 1;
				}
				if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
					$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
				} else {
					if ($_POST['user_id'] == $carovl['user']['user_id']) {
						if (md5($_POST['current_password']) != $user['password']) {
							$errors[] = $error_icon . $carovl['lang']['current_password_mismatch'];
						}
						if (strlen($_POST['new_password']) < 6) {
							$errors[] = $error_icon . $carovl['lang']['password_too_short'];
						}
						if ($_POST['new_password'] != $_POST['confirm_password']) {
							$errors[] = $error_icon . $carovl['lang']['password_mismatch'];
						}
						if (empty($errors)) {
							$update_data = array(
								'password' => md5($_POST['new_password'])
							);
							if (updateUserData($_POST['user_id'], $update_data)) {
								$user_id = secureIt($_POST['user_id']);
								$session_id = (! empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
								$session_id = secureIt($session_id);
								$query = mysqli_query($sql_connect, "DELETE FROM " . T_SESSIONS . " WHERE `user_id` = '{$user_id}' AND `session_id` <> '{$session_id}'");
								$data = array(
									'status' => 200,
									'success' => $success_icon . $carovl['lang']['setting_successfully_updated']
								);
							}
						}
					}
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array(
				'errors' => $errors
			));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'update_user_password'
	if ($s == 'update_user_privacy') {
		if (isset($_POST['user_id']) && checkSession($hash_id) == true) {
			$message_privacy = 0;
			$follow_privacy = 0;
			$lastseen_privacy = 0;
			$activities_privacy = 0;
			$visit_privacy = 0;
			$array = array(
				'0',
				'1'
			);
			if (! empty($_POST['follow_privacy'])) {
				if (in_array($_POST['follow_privacy'], $array)) {
					$follow_privacy = $_POST['follow_privacy'];
				}
			}
			if (! empty($_POST['activities_privacy'])) {
				if (in_array($_POST['activities_privacy'], $array)) {
					$activities_privacy = $_POST['activities_privacy'];
				}
			}
			if (! empty($_POST['lastseen_privacy'])) {
				if (in_array($_POST['lastseen_privacy'], $array)) {
					$lastseen_privacy = $_POST['lastseen_privacy'];
				}
			}
			if (! empty($_POST['message_privacy'])) {
				if (in_array($_POST['message_privacy'], $array)) {
					$message_privacy = $_POST['message_privacy'];
				}
			}
			if (! empty($_POST['visit_privacy'])) {
				if (in_array($_POST['visit_privacy'], $array)) {
					$visit_privacy = $_POST['visit_privacy'];
				}
			}
			$update_data = array(
				'message_privacy' => $message_privacy,
				'follow_privacy' => $follow_privacy,
				'show_lastseen' => $lastseen_privacy,
				'show_activities_privacy' => $activities_privacy,
				'visit_privacy' => $visit_privacy
			);
			if (updateUserData($_POST['user_id'], $update_data)) {
				$data = array(
					'status' => 200,
					'success' => $success_icon . $carovl['lang']['setting_successfully_updated']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_user_privacy'
} // end f == 'settings'
if ($f == 'reposition_cover') {
	if (isset($_POST['pos'])) {
		if ($_POST['cover_image'] != $carovl['user_default_cover']) {
			$from_top = abs($_POST['pos']);
			$cover_image = $_POST['cover_image'];
			$full_url_image = getMedia($_POST['cover_image']);
			$default_image = $_POST['real_image'];
			$image_type = $_POST['image_type'];
			$default_cover_width = 1138;
			$default_cover_height = 320;
			require_once('assets/import/thumbncrop.inc.php');
			$tb = new ThumbAndCrop();
			$tb->openImg($default_image);
			$newHeight = $tb->getRightHeight($default_cover_width);
			$tb->creaThumb($default_cover_width, $newHeight);
			$tb->setThumbAsOriginal();
			$tb->cropThumb($default_cover_width, 320, 0, $from_top);
			$tb->saveThumb($cover_image);
			$tb->resetOriginal();
			$tb->closeImg();
		}
		if (empty($full_url_image)) {
			$full_url_image = getMedia($carovl['user_default_cover']);
		}
		$data = array(
			'status' => 200,
			'url' => $full_url_image
		);
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'reposition_cover'
if ($f == 'update_cover_picture') {
	if (isset($_FILES['cover']['name'])) {
		if (uploadImage($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['user_id']) === true) {
			$img = userData($_POST['user_id']);
			$color = getDominantColor($img['cover']);
			$data = array(
				'status' => 200,
				'img' => $img['cover'],
				'cover_org' => $img['cover_org'],
				'cover_full' => getMedia($img['cover_full']),
				'background' => $color['background'],
				'color' => $color['color']
			);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'update_cover_picture'
if ($f == 'update_avatar_picture') {
	if (isset($_FILES['avatar']['tmp_name'])) {
		if (uploadImage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['user_id']) === true) {
			$img = userData($_POST['user_id']);
			$data = array(
				'status' => 200,
				'img' => $img['avatar'],
				'img_org' => $img['avatar_org']
			);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'update_avatar_picture'
if ($f == 'open_cover_image') {
	if (! empty($_POST['image'])) {
		$user_cover = getUserProfileImage(secureIt($_POST['image'], 0));
		if (! empty($user_cover)) {
			$data = array(
				'status' => 200,
				'post_id' => $user_cover
			);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'open_cover_image'
if ($f == 'open_avatar_image') {
	if (! empty($_POST['image'])) {
		$user_avatar = getUserProfileImage(secureIt($_POST['image'], 0));
		if (! empty($user_avatar)) {
			$data = array(
				'status' => 200,
				'post_id' => $user_avatar
			);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'open_avatar_image'
if ($f == 'timeline') {
	if ($s == 'load_timeline_posts') {
		if (! empty($_GET['user_id'])) {
			$carovl['page'] = 'timeline';
			$carovl['profile'] = userData($_GET['user_id']);
			echo loadPage('timeline/load-posts');
			exit();
		}
	} // end s == 'load_timeline_posts'
	if ($s == 'block_user') {
		if (! empty($_GET['user_id'])) {
			$user_id = secureIt($_GET['user_id']);
			$block = registerBlock($user_id);
			if ($block === true) {
				$data = array(
					'status' => 200,
					'href' => $carovl['config']['site_url']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'block_user'
} // end f == 'timeline'
if ($f == 'follow_user') {
	if (isset($_GET['id']) && checkMainSession($hash_id) === true) {
		if (isFollowing($_GET['id'], $carovl['user']['user_id']) === true || isFollowRequested($_GET['id'], $carovl['user']['user_id']) === true) {
			if (deleteFollow($_GET['id'], $carovl['user']['user_id'])) {
				$data = array(
					'status' => 200,
					'can_send' => 0
				);
			}
		} else {
			if (registerFollow($_GET['id'], $carovl['user']['user_id'])) {
				$data = array(
					'status' => 200,
					'can_send' => 0
				);
			}
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'follow_user'
if ($f == 'articles') {
	if ($s == 'new_article' && checkSession($hash_id) === true) {
		if (empty($_POST['article_title'])) {
			$errors[] = $error_icon . $carovl['lang']['you_must_fill_out_article_title'];
		} elseif (empty($_POST['article_content'])) {
			$errors[] = $error_icon . $carovl['lang']['you_must_fill_out_article_content'];
		} elseif (empty($_POST['article_tags'])) {
			$errors[] = $error_icon . $carovl['lang']['please_fill_tags'];
		} elseif (empty($_FILES['article_thumbnail']['name'])) {
			$errors[] = $error_icon . $carovl['lang']['please_upload_thumbnail_image'];
		}
		$article_source = '';
		if (! empty($_POST['article_source'])) {
			if (! filter_var($_POST['article_source'], FILTER_VALIDATE_URL)) {
				$errors[] = $error_icon . $carovl['lang']['invalid_source_url'];
			} else {
				$article_source = secureIt($_POST['article_source']);
			}
		}
		if (empty($errors)) {
			$article_data = array(
				'user_id' => secureIt($carovl['user']['user_id']),
				'article_title' => secureIt($_POST['article_title']),
				'article_content' => secureIt($_POST['article_content'], 0, false),
				'article_source' => $article_source,
				'article_tags' => secureIt($_POST['article_tags']),
				'draft' => '0',
				'time' => time()
			);
			$register_article = registerArticle($article_data);
			if ($register_article && is_numeric($register_article)) {
				if (! empty($_FILES['article_thumbnail']['tmp_name'])) {
					$file_info = array(
						'file' => $_FILES['article_thumbnail']['tmp_name'],
						'name' => $_FILES['article_thumbnail']['name'],
						'size' => $_FILES['article_thumbnail']['size'],
						'type' => $_FILES['article_thumbnail']['type'],
						'types' => 'jpg,png,bmp,gif',
						'crop' => array('width' => 320, 'height' => 200)
					);
					$media = shareFile($file_info);
					$media_filename = $media['filename'];
					updateArticle($register_article, array('article_thumbnail' => $media_filename));
				}
				$tags = '';
				$all_tags = explode(',', $_POST['article_tags']);
				foreach ($all_tags as $key => $tag) {
					$tags .= "#$tag";
				}
				$post_data = array(
					'user_id' => secureIt($carovl['user']['user_id']),
					'article_id' => secureIt($register_article),
					'post_text' => $tags,
					'time' => time()
				);
				$id = registerPost($post_data);
				if ($id) {
					$data = array(
						'status' => 200,
						'href' => seoLink('index.php?page=article&id=' . $register_article . '_' . slugPost($_POST['article_title'])),
						'errors' => $success_icon . $carovl['lang']['article_successfully_published']
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array(
				'errors' => $errors
			));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'new_article'
	if ($s == 'edit_article') {
		if (checkSession($hash_id) === true) {
			if (empty($_POST['article_title'])) {
				$errors[] = $error_icon . $carovl['lang']['you_must_fill_out_article_title'];
			} elseif (empty($_POST['article_content'])) {
				$errors[] = $error_icon . $carovl['lang']['you_must_fill_out_article_content'];
			} elseif (empty($_POST['article_tags'])) {
				$errors[] = $error_icon . $carovl['lang']['please_fill_tags'];
			}
			$article_source = '';
			if (! empty($_POST['article_source'])) {
				if (! filter_var($_POST['article_source'], FILTER_VALIDATE_URL)) {
					$errors[] = $error_icon . $carovl['lang']['invalid_source_url'];
				} else {
					$article_source = secureIt($_POST['article_source']);
				}
			}
			if (empty($errors)) {
				$article_content = $_POST['article_content'];
				$article_content = trim($article_content);
				$article_content = mysqli_real_escape_string($sql_connect, $article_content);
				$article_content = str_replace('\r\n', "", $article_content);
				$article_content = str_replace('\n\r', "", $article_content);
				$article_content = str_replace('\r', "", $article_content);
				$article_content = str_replace('\n', "", $article_content);
				$article_content = stripslashes($article_content);
				$article_data = array(
					'user_id' => secureIt($carovl['user']['user_id']),
					'article_title' => secureIt($_POST['article_title']),
					'article_content' => $article_content,
					'article_source' => $article_source,
					'article_tags' => $_POST['article_tags']
				);
				if (updateArticle($_GET['id'], $article_data)) {
					if (isset($_FILES['article_thumbnail'])) {
						$fileinfo = array(
							'file' => $_FILES['article_thumbnail']['tmp_name'],
							'name' => $_FILES['article_thumbnail']['name'],
							'size' => $_FILES['article_thumbnail']['size'],
							'type' => $_FILES['article_thumbnail']['type'],
							'types' => 'jpg,png,bmp,gif',
							'crop' => array('width' => 320, 'height' => 200)
						);
						$media = shareFile($fileinfo);
						$media_filename = $media['filename'];
						$image = array();
						$image['article_thumbnail'] = $media_filename;
						updateArticle($_GET['id'], $image);
					}
					$data = array(
						'status' => 200,
						'href' => seoLink('index.php?page=article&id=' . $_GET['id'] . '_' . slugPost($_POST['article_title'])),
						'errors' => $success_icon . $carovl['lang']['article_successfully_edited']
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array(
				'errors' => $errors
			));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'edit_article'
} // end f == 'articles'
// Conversations
if ($f == 'conversations') {
	if ($s == 'is_conversation_on') {
		if (! empty($_GET['recipient_id'])) {
			$data = array(
				'conversation' => $carovl['config']['chat_system']
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'is_conversation_on'
	if ($s == 'load_conversation') {
		if (! empty($_GET['recipient_id']) && is_numeric($_GET['recipient_id']) && $_GET['recipient_id'] > 0 && ! empty($_GET['placement'])) {
			if (isBlocked($_GET['recipient_id'])) {
				$data = array(
					'status' => 400
				);
			} else {
				$recipient_id = secureIt($_GET['recipient_id']);
				$recipient = userData($recipient_id);
				if (isset($recipient['user_id'])) {
					$carovl['conversation']['recipient'] = $recipient;
					$data = array(
						'status' => 200,
						'html' => loadPage('conversations/popover-conversation')
					);
					$_SESSION['conversation_id'] = secureIt($recipient['user_id']);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'load_conversation'
	if ($s == 'close') {
		if (isset($_SESSION['conversation_id'])) {
			unset($_SESSION['conversation_id']);
			registerTyping($_GET['id'], 0);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'close'
	if ($s == 'remove_typing') {
		if (! empty($_GET['recipient_id'])) {
			$is_typing = registerTyping($_GET['recipient_id'], 0);
			if ($is_typing == true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'remove_typing'
	if ($s == 'load_conversation_messages') {
		if (! empty($_GET['recipient_id']) && is_numeric($_GET['recipient_id']) && $_GET['recipient_id'] > 0 && checkMainSession($hash_id) === true) {
			$recipient_id = secureIt($_GET['recipient_id']);
			$html = '';
			$messages = getMessages(array('user_id' => $recipient_id));
			foreach ($messages as $carovl['conversation_message']) {
				$html .= loadPage('conversations/messages-list');
			}
			$data = array(
				'status' => 200,
				'messages' => $html
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'load_conversation_messages'
	if ($s == 'send_message') {
		if (! empty($_POST['user_id']) && checkMainSession($hash_id) === true) {
			$html = '';
			$media = '';
			$mediafilename = '';
			$medianame = '';
			if (isset($_FILES['message_file']['name'])) {
				$fileinfo = array(
					'file' => $_FILES['message_file']['tmp_name'],
					'name' => $_FILES['message_file']['name'],
					'size' => $_FILES['message_file']['size'],
					'type' => $_FILES['message_file']['type']
				);
				$media = shareFile($fileinfo);
				$mediafilename = $media['filename'];
				$medianame = $media['name'];
			}
			$message_text = '';
			if (! empty($_POST['text_input'])) {
				$message_text = $_POST['text_input'];
			}
			$messages = registerMessage(array(
				'from_id' => secureIt($carovl['user']['user_id']),
				'to_id' => secureIt($_POST['user_id']),
				'text' => secureIt($message_text),
				'media' => secureIt($mediafilename),
				'media_file_name' => secureIt($medianame),
				'time' => time()
			));
			if ($messages > 0) {
				$messages = getMessages(array(
					'message_id' => $messages,
					'user_id' => $_POST['user_id']
				));
				foreach ($messages as $carovl['conversation_message']) {
					$html .= loadPage('conversations/messages-list');
				}
				$file = false;
				if (isset($_FILES['message_file']['name'])) {
					$file = true;
				}
				$data = array(
					'status' => 200,
					'file' => $file,
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'send_message'
	if ($s == 'get_conversation') {
		if (checkMainSession($hash_id) === true) {
			if (! empty($_GET['user_id']) && isset($_GET['message_id'])) {
				$user_id = secureIt($_GET['user_id']);
				if (! empty($user_id)) {
					$user_id = $_GET['user_id'];
					$messages = getMessages(array(
						'after_message_id' => $_GET['message_id'],
						'user_id' => $user_id
					));
					if (count($messages) > 0) {
						$html = '';
						foreach ($messages as $carovl['conversation_message']) {
							$html .= loadPage('conversations/messages-list');
						}
						$data = array(
							'status' => 200,
							'messages' => $html
						);
					}
				}
			}
			$data['is_typing'] = 0;
			if (! empty($_GET['user_id']) && $carovl['config']['message_typing'] == 1) {
				$is_typing = isTyping($_GET['user_id']);
				if ($is_typing === true) {
					$img = userData($_GET['user_id']);
					$data['is_typing'] = 200;
					$data['img'] = $img['avatar'];
					$data['typing'] = $carovl['lang']['is_typing'];
				} else {
					$data['is_typing'] = 0;
				}
			}
			$data['can_seen'] = 0;
			if (! empty($_GET['last_id']) && $carovl['config']['message_seen'] == 1) {
				$message_id = secureIt($_GET['last_id']);
				if (! empty($message_id) || is_numeric($message_id) || $message_id > 0) {
					$seen = seenMessage($message_id);
					if ($seen > 0) {
						$data['can_seen'] = 1;
						$data['time'] = $seen['time'];
						$data['seen'] = $seen['seen'];
					}
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_conversation'
	if ($s == 'is_typing') {
		if (! empty($_GET['recipient_id'])) {
			$is_typing = registerTyping($_GET['recipient_id'], 1);
			if ($is_typing === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'is_typing'
	if ($s == 'open_image') {
		$html = '';
		if (! empty($_GET['id']) && ! empty($_GET['user_id'])) {
			if (checkMainSession($hash_id) === true) {
				$messages = getMessages(array(
					'message_id' => secureIt($_GET['id']),
					'user_id' => secureIt($_GET['user_id'])
				));
				if (! empty($messages)) {
					foreach ($messages as $carovl['message']) {
						$html .= loadPage('lightbox/conversation-image');
					}
				}
			}
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'open_image'
	if ($s == 'delete_conversation') {
		if (isset($_GET['user_id']) && checkMainSession($hash_id) === true) {
			$user_id = secureIt($_GET['user_id']);
			if (! empty($user_id) || is_numeric($user_id) || $user_id > 0) {
				if (deleteConversation($user_id) === true) {
					$data = array(
						'status' => 200,
						'success' => $carovl['lang']['conversation_deleted']
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_conversation'
} // end f == 'conversations'
if ($f == 'search') {
	$data = array(
		'status' => 200,
		'html' => ''
	);
	if ($s == 'general') {
		$data['href'] = seoLink('index.php?page=search');
	}
	if ($s == 'keyword' && isset($_GET['query'])) {
		$results = getSearch($_GET['query']);
		if (count($results) == 0) {
			$carovl['query'] = $_GET['query'];
			$data['no_result'] = loadPage('header/no-result');
		} else {
			foreach ($results as $carovl['result']) {
				$data['html'] .= loadPage('header/search');
			}
		}
	} // end s == 'keyword'
	if ($s == 'hash' && isset($_GET['query'])) {
		foreach (getHashSearch($_GET['query']) as $carovl['result']) {
			$data['html'] .= loadPage('header/hashtag-result');
		}
	} // end s == 'hash'
	if ($s == 'recent' && $carovl['logged_in'] == true) {
		foreach (getRecentSearchs() as $carovl['result']) {
			$data['html'] .= loadPage('header/search');
		}
	} // end s == 'recent'
	if ($s == 'clear_recent' && $carovl['logged_in'] == true) {
		$clear = clearRecentSearches();
		if ($clear === true) {
			$data = array(
				'response' => 200
			);
		}
	} // end s == 'clear_recent'
	if ($s == 'recipients' && $carovl['logged_in'] == true) {
		if (count(getUsersForConversation($carovl['user']['user_id'], $_GET['query'])) == 0) {
			if ($carovl['user']['username'] == $_GET['query']) {
				$data['lol'] = $carovl['lang']['lol_what'];
			} else {
				$data['lol'] = $carovl['lang']['lol_eh'];
			}
		} else {
			foreach (getUsersForConversation($carovl['user']['user_id'], $_GET['query'], 4) as $carovl['recipient']) {
				$data['html'] .= loadPage('conversations/recipient-list');
			}
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'search'
if ($f == 'user_popover') {
	$html = '';
	$array_types = array(
		'user',
		'group'
	);
	if (! empty($_GET['id']) && ! empty($_GET['type']) && in_array($_GET['type'], $array_types)) {
		if ($_GET['type'] == 'user') {
			$carovl['popover'] = userData($_GET['id']);
			if (! empty($carovl['popover'])) {
				$html = loadPage('popover/user');
			}
		} elseif ($_GET['type'] == 'group') {
			$carovl['popover'] = groupData($_GET['id']);
			if (! empty($carovl['popover'])) {
				$html = loadPage('popover/group');
			}
		}
	}
	$data = array(
		'status' => 200,
		'html' => $html
	);
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end f == 'user_popover'
if ($f == 'user') {
	if ($s == 'delete_account') {
		if (isset($_POST['current_password'])) {
			if (md5($_POST['current_password']) != $carovl['user']['password']) {
				$errors[] = $error_icon . $carovl['lang']['current_password_mismatch'];
			}
			if (empty($errors)) {
				if (deleteUser($carovl['user']['user_id']) === true) {
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['account_removed'],
						'location' => seoLink('index.php?page=logout')
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'delete_account'
} // end f == 'user'
if ($f == 'events') {
	if ($s == 'new_event') {
		if (checkSession($hash_id) === true) {
			if (empty($_POST['event_name']) || empty($_POST['event_location']) || empty($_POST['event_description'])) {
				$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
			} else {
				$startTimeObj = new Datetime($_POST['start_date'] . " " . $_POST['start_time']);
				$endTimeObj = new Datetime($_POST['end_date'] . " " . $_POST['end_time']);
				$start_time = $startTimeObj->getTimeStamp();
				$end_time = $endTimeObj->getTimeStamp();
				if ($start_time < time()) {
					$errors[] = $error_icon . $carovl['lang']['please_check_your_start_time'];
				}
				if ($end_time < time() || $end_time < $start_time) {
					$errors[] = $error_icon . $carovl['lang']['please_check_your_end_time'];
				}
			}
			if (empty($errors)) {
				$event_data = array(
					'name' => secureIt($_POST['event_name']),
					'location' => secureIt($_POST['event_location']),
					'description' => secureIt($_POST['event_description']),
					'start_date' => secureIt($_POST['start_date']),
					'end_date' => secureIt($_POST['end_date']),
					'start_time' => secureIt($_POST['start_time']),
					'end_time' => secureIt($_POST['end_time']),
					'user_id' => $carovl['user']['user_id']
				);
				$register_event = registerEvent($event_data);
				if ($register_event && is_numeric($register_event)) {
					if (! empty($_FILES['event_thumbnail']['tmp_name'])) {
						$tmp_name = $_FILES['event_thumbnail']['tmp_name'];
						$filename = $_FILES['event_thumbnail']['name'];
						$filetype = $_FILES['event_thumbnail']['type'];
						$filesize = $_FILES['event_thumbnail']['size'];
						uploadImage($tmp_name, $filename, 'cover', $filetype, $register_event, 'event');
					}
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['event_successfully_published'],
						'href' => seoLink('index.php?page=view-event&id=' . $register_event)
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'new_event'
	if ($s == 'edit_event') {
		if (checkSession($hash_id) === true) {
			if (empty($_POST['event_name']) || empty($_POST['event_location']) || empty($_POST['event_description'])) {
				$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
			} else {
				$startTimeObj = new Datetime($_POST['start_date'] . " " . $_POST['start_time']);
				$endTimeObj = new Datetime($_POST['end_date'] . " " . $_POST['end_time']);
				$start_time = $startTimeObj->getTimeStamp();
				$end_time = $endTimeObj->getTimeStamp();
				if ($start_time < time()) {
					$errors[] = $error_icon . $carovl['lang']['please_check_your_start_time'];
				}
				if ($end_time < time() || $end_time < $start_time) {
					$errors[] = $error_icon . $carovl['lang']['please_check_your_end_time'];
				}
			}
			if (empty($errors) && isset($_GET['id']) && is_numeric($_GET['id'])) {
				$update_data = array(
					'name' => secureIt($_POST['event_name']),
					'location' => secureIt($_POST['event_location']),
					'description' => secureIt($_POST['event_description']),
					'start_date' => secureIt($_POST['start_date']),
					'end_date' => secureIt($_POST['end_date']),
					'start_time' => secureIt($_POST['start_time']),
					'end_time' => secureIt($_POST['end_time'])
				);
				$update_event = updateEvent($_GET['id'], $update_data);
				if ($update_event) {
					if (! empty($_FILES['event_thumbnail']['tmp_name'])) {
						$tmp_name = $_FILES['event_thumbnail']['tmp_name'];
						$filename = $_FILES['event_thumbnail']['name'];
						$filetype = $_FILES['event_thumbnail']['type'];
						$filesize = $_FILES['event_thumbnail']['size'];
						uploadImage($tmp_name, $filename, 'cover', $filetype, $_GET['id'], 'event');
					}
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['event_successfully_updated'],
						'href' => seoLink('index.php?page=view-event&id=' . $_GET['id'])
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'edit_event'
	if ($s == 'delete_event') {
		$data = array(
			'status' => 300
		);
		if (isset($_GET['event_id']) && is_numeric($_GET['event_id']) && $_GET['event_id'] > 0) {
			if (deleteEvent($_GET['event_id'])) {
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_event'
	if ($s == 'load_event_posts') {
		if (! empty($_GET['event_id'])) {
			$carovl['page'] = 'events';
			$carovl['event'] = eventData($_GET['event_id']);
			echo loadPage('events/load-posts');
			exit();
		}
	} // end s == 'load_event_posts'
	if ($s == 'search_followers') {
		$html = '';
		$data = array(
			'status' => 300,
			'html' => $carovl['lang']['no_result']
		);
		$filter = (isset($_GET['filter'])) ? secureIt($_GET['filter']) : false;
		if ($filter) {
			$users = searchFollowers($carovl['user']['user_id'], $filter, 10, $_GET['event_id']);
			if (count($users) > 0) {
				foreach ($users as $carovl['invite']) {
					$html .= loadPage('events/includes/user-list');
				}
				$data = array(
					'status' => 200,
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'search_followers'
	if ($s == 'invite_user') {
		$data = array(
			'status' => 300
		);
		if (! empty($_GET['user_id']) && ! empty($_GET['event_id'])) {
			$invite_user = registerEventInvite($_GET['user_id'], $_GET['event_id']);
			if ($invite_user === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'invite_user'
	if ($s == 'event_going') {
		if (! empty($_GET['event_id']) && checkMainSession($hash_id) === true) {
			if (eventGoingExist($_GET['event_id']) === true) {
				if (unsetEventGoingUsers($_GET['event_id'])) {
					$data = array(
						'status' => 200,
						'html' => getGoingButton($_GET['event_id'])
					);
				}
			} else {
				if (addEventGoingUsers($_GET['event_id'])) {
					$data = array(
						'status' => 200,
						'html' => getGoingButton($_GET['event_id'])
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'event_going'
	if ($s == 'event_interested') {
		if (! empty($_GET['event_id']) && checkMainSession($hash_id) === true) {
			if (eventInterestedExist($_GET['event_id']) === true) {
				if (unsetEventInterestedUsers($_GET['event_id'])) {
					$data = array(
						'status' => 200,
						'html' => getInterestedButton($_GET['event_id'])
					);
				}
			} else {
				if (addEventInterestedUsers($_GET['event_id'])) {
					$data = array(
						'status' => 200,
						'html' => getInterestedButton($_GET['event_id'])
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'event_interested'
	if ($s == 'update_event_cover') {
		if (isset($_FILES['cover']['name']) && ! empty($_POST['event_id']) && isEventOwner($_POST['event_id'])) {
			if (uploadImage($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['event_id'], 'event')) {
				$img = eventData($_POST['event_id']);
				$color = getDominantColor($img['cover']);
				$data = array(
					'status' => 200,
					'img' => $img['cover'],
					'background' => $color['background'],
					'color' => $color['color']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_cover'
} // end f == 'events'
if ($f == 'groups') {
	if ($s == 'create_group') {
		if (checkSession($hash_id) === true) {
			if (empty($_POST['group_name']) || empty($_POST['group_title'])) {
				$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
			} else {
				$is_exist = isUsernameExist($_POST['group_name'], 0);
				if (in_array(true, $is_exist)) {
					$errors[] = $error_icon . $carovl['lang']['group_name_already_exist'];
				}
				if (in_array($_POST['group_name'], $carovl['site_pages'])) {
					$errors[] = $error_icon . $carovl['lang']['cannot_use_this_group_name'];
				}
				if (strlen($_POST['group_name']) < 5 || strlen($_POST['group_name']) > 32) {
					$errors[] = $error_icon . $carovl['lang']['group_name_characters_length'];
				}
				if (! preg_match('/^[\w]+$/', $_POST['group_name'])) {
					$errors[] = $error_icon . $carovl['lang']['invalid_group_name_characters'];
				}
			}
			if (empty($errors)) {
				$re_data = array(
					'user_id' => secureIt($carovl['user']['user_id']),
					'group_name' => secureIt($_POST['group_name']),
					'group_title' => secureIt($_POST['group_title']),
					'about' => secureIt($_POST['about']),
					'active' => '1'
				);
				$register_group = registerGroup($re_data);
				if ($register_group) {
					$data = array(
						'status' => 200,
						'success' => $carovl['lang']['group_successfully_created'],
						'href' => seoLink('index.php?page=timeline&u=' . secureIt($_POST['group_name']))
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'create_group'
	if ($s == 'join_group') {
		if (isset($_GET['group_id']) && checkMainSession($hash_id) === true) {
			if (isGroupJoined($_GET['group_id']) === true || isJoinRequested($_GET['group_id'], $carovl['user']['user_id']) === true) {
				if (leaveGroup($_GET['group_id'], $carovl['user']['user_id'])) {
					$data = array(
						'status' => 200
					);
				}
			} else {
				if (registerGroupJoin($_GET['group_id'], $carovl['user']['user_id'])) {
					$data = array(
						'status' => 200
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'join_group'
	if ($s == 'load_group_posts') {
		$carovl['group_profile'] = groupData($_GET['group_id']);
		echo loadPage('groups/load-posts');
		exit();
	} // end s == 'load_group_posts'
	if ($s == 'update_group_cover') {
		if (isset($_FILES['cover']['name']) && ! empty($_POST['group_id'])) {
			if (uploadImage($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['group_id'], 'group')) {
				$img = groupData($_POST['group_id']);
				$color = getDominantColor($img['cover']);
				$data = array(
					'status' => 200,
					'img' => $img['cover'],
					'background' => $color['background'],
					'color' => $color['color']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_group_cover'
	if ($s == 'update_group_avatar') {
		if (isset($_FILES['avatar']['tmp_name']) && ! empty($_POST['group_id'])) {
			if (uploadImage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['group_id'], 'group')) {
				$img = groupData($_POST['group_id']);
				$data = array(
					'status' => 200,
					'img' => $img['avatar']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_group_avatar'
	if ($s == 'delete_group') {
		if (isset($_GET['group_id']) && checkMainSession($hash_id) === true) {
			if (deleteGroup($_GET['group_id']) === true) {
				$data = array(
					'status' => 200,
					'href' => $carovl['config']['site_url']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_group'
} // end f == 'groups'
if ($f == 'group_setting') {
	if ($s == 'update_general_setting') {
		if (! empty($_POST['group_id']) && checkSession($hash_id) === true) {
			$group = groupData($_POST['group_id']);
			if (empty($_POST['group_name']) || empty($_POST['group_title'])) {
				$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
			} else {
				if ($_POST['group_name'] != $group['group_name']) {
					$is_exist = isUsernameExist($_POST['group_name'], 0);
					if (in_array(true, $is_exist)) {
						$errors[] = $error_icon . $carovl['lang']['group_name_already_exist'];
					}
				}
				if (in_array($_POST['group_name'], $carovl['site_pages'])) {
					$errors[] = $error_icon . $carovl['lang']['cannot_use_this_group_name'];
				}
				if (strlen($_POST['group_name']) < 5 || strlen($_POST['group_name']) > 32) {
					$errors[] = $error_icon . $carovl['lang']['group_name_characters_length'];
				}
				if (! preg_match('/^[\w]+$/', $_POST['group_name'])) {
					$errors[] = $error_icon . $carovl['lang']['invalid_group_name_characters'];
				}
			}
			if (empty($errors)) {
				$update_data = array(
					'group_name' => $_POST['group_name'],
					'group_title' => $_POST['group_title'],
					'about' => $_POST['about']
				);
				if (updateGroupData($_POST['group_id'], $update_data)) {
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['setting_successfully_updated'],
						'group_name_url' => seoLink('index.php?page=timeline&u=' . secureIt($_POST['group_name'])),
						'group_name' => $_POST['group_name']
					);
				}
			}
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'update_general_setting'
	if ($s == 'update_group_privacy') {
		if (! empty($_POST['group_id']) && checkSession($hash_id) === true) {
			$privacy = 0;
			$join_privacy = 0;
			$arr = array(0, 1);
			if (! empty($_POST['privacy'])) {
				if (in_array($_POST['privacy'], $arr)) {
					$privacy = $_POST['privacy'];
				}
			}
			if (! empty($_POST['join_privacy'])) {
				if (in_array($_POST['join_privacy'], $arr)) {
					$join_privacy = $_POST['join_privacy'];
				}
			}
			if (empty($errors)) {
				$update_data = array(
					'privacy' => $privacy,
					'join_privacy' => $join_privacy
				);
				if (updateGroupData($_POST['group_id'], $update_data)) {
					$data = array(
						'status' => 200,
						'success' => $success_icon . $carovl['lang']['setting_successfully_updated']
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_group_privacy'
	if ($s == 'delete_member') {
		if (! empty($_GET['user_id']) && ! empty($_GET['group_id'])) {
			if (leaveGroup($_GET['group_id'], $_GET['user_id']) === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_member'
	if ($s == 'accept_join_request') {
		if (! empty($_GET['user_id']) && ! empty($_GET['group_id'])) {
			if (acceptJoinRequest($_GET['user_id'], $_GET['group_id']) === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'accept_join_request'
	if ($s == 'reject_join_request') {
		if (! empty($_GET['user_id']) && ! empty($_GET['group_id'])) {
			if (rejectJoinRequest($_GET['user_id'], $_GET['group_id']) === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'reject_join_request'
} // end f == 'group_setting'
if ($f == 'admin_setting') {
	if ($s == 'filter_users') {
		$html = '';
		$after_user_id = (isset($_GET['after_user_id']) && is_numeric($_GET['after_user_id']) && $_GET['after_user_id'] > 0) ? $_GET['after_user_id'] : 0;
		foreach (getAllUsers(10, 'manage_users', $_POST, $after_user_id) as $carovl['user_list']) {
			$html .= loadPage('admin/users/user-list');
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'filter_users'
	if ($s == 'delete_user' && isset($_GET['user_id']) && checkSession($hash_id) === true) {
		if (deleteUser($_GET['user_id']) === true) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_user'
	if ($s == 'get_more_posts') {
		$html = '';
		$post_data = array(
			'limit' => 10,
			'after_post_id' => secureIt($_GET['after_post_id'])
		);
		foreach (getAllPosts($post_data) as $carovl['story']) {
			$html .= loadPage('admin/posts/post-list');
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_posts'
	if ($s == 'delete_post' && checkSession($hash_id) === true) {
		if (! empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
			if (deletePost($_POST['post_id']) === true) {
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_post'
	if ($s == 'get_more_articles') {
		$html = '';
		$articles = array(
			'limit' => 10,
			'after_article_id' => secureIt($_GET['after_article_id'])
		);
		foreach (getAllArticles($articles) as $carovl['article']) {
			$html .= loadPage('admin/articles/article-list');
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_articles'
	if ($s == 'get_more_events') {
		$html = '';
		$events = array(
			'limit' => 10,
			'after_event_id' => secureIt($_GET['after_event_id'])
		);
		foreach (getAllEvents($events) as $carovl['event']) {
			$html .= loadPage('admin/events/event-list');
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_events'
	if ($s == 'delete_article' && checkSession($hash_id) === true) {
		if (isset($_GET['article_id']) && is_numeric($_GET['article_id']) && $article_id > 0) {
			$id = secureIt($_GET['article_id']);
			if (deleteArticle($id)) {
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_article'
	if ($s == 'delete_event' && checkSession($hash_id) === true) {
		if (isset($_GET['event_id']) && is_numeric($_GET['event_id']) && $_GET['event_id'] > 0) {
			$id = secureIt($_GET['event_id']);
			if (deleteEvent($id)) {
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_event'
	if ($s == 'delete_group' && checkSession($hash_id) === true) {
		if (isset($_GET['group_id'])) {
			$id = secureIt($_GET['group_id']);
			if (deleteGroup($id) === true) {
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_group'
	if ($s == 'get_more_groups') {
		$html = '';
		$after_group_id = (isset($_GET['after_group_id']) && is_numeric($_GET['after_group_id']) && $_GET['after_group_id'] > 0) ? $_GET['after_group_id'] : 0;
		foreach (getAllGroups(10, $after_group_id) as $carovl['group']) {
			$html .= loadPage('admin/groups/group-list');
		}
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_groups'
	if ($s == 'mark_as_safe') {
		if (! empty($_GET['report_id'])) {
			if (deleteReport($_GET['report_id'])) {
				$data = array(
					'status' => 200,
					'html' => getAllReports(),
					'reports' => countUnseenReports()
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'mark_as_safe'
	if ($s == 'delete_reported_post') {
		if (! empty($_GET['post_id'])) {
			if (deletePost($_GET['post_id']) === true) {
				if (deleteReport($_GET['report_id']) === true) {
					$data = array(
						'status' => 200,
						'html' => getAllReports(),
						'reports' => countUnseenReports()
					);
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_reported_post'
	if ($s == 'update_general_setting' && checkSession($hash_id) === true) {
		$save_setting = false;
		$cache_system = 0;
		$chat_system = 0;
		$email_validation = 0;
		$sms_or_email = 'mail';
		$email_notification = 0;
		$seo_link = 0;
		$file_sharing = 0;
		$use_seo_friendly = 0;
		$message_seen = 0;
		$message_typing = 0;
		$user_lastseen = 0;
		$delete_account = 0;
		$profile_visit = 0;
		$video_upload = 0;
		$audio_upload = 0;
		$smooth_loading = 0;
		$groups = 0;
		$developers_page = 0;
		$maintenance_mode = 0;
		$events = 0;
		$profile_privacy = 0;
		if (! empty($_POST['cache_system'])) {
			$cache_system = $_POST['cache_system'];
		}
		if (! empty($_POST['chat_system'])) {
			$chat_system = $_POST['chat_system'];
		}
		if (! empty($_POST['email_validation'])) {
			$email_validation = $_POST['email_validation'];
		}
		if (! empty($_POST['sms_or_email'])) {
			$sms_or_email = $_POST['sms_or_email'];
		}
		if (! empty($_POST['email_notification'])) {
			$email_notification = $_POST['email_notification'];
		}
		if (! empty($_POST['seo_link'])) {
			$seo_link = $_POST['seo_link'];
		}
		if (! empty($_POST['file_sharing'])) {
			$file_sharing = $_POST['file_sharing'];
		}
		if (! empty($_POST['use_seo_friendly'])) {
			$use_seo_friendly = $_POST['use_seo_friendly'];
		}
		if (! empty($_POST['message_seen'])) {
			$message_seen = $_POST['message_seen'];
		}
		if (! empty($_POST['message_typing'])) {
			$message_typing = $_POST['message_typing'];
		}
		if (! empty($_POST['user_lastseen'])) {
			$user_lastseen = $_POST['user_lastseen'];
		}
		if (! empty($_POST['delete_account'])) {
			$delete_account = $_POST['delete_account'];
		}
		if (! empty($_POST['profile_visit'])) {
			$profile_visit = $_POST['profile_visit'];
		}
		if (! empty($_POST['video_upload'])) {
			$video_upload = $_POST['video_upload'];
		}
		if (! empty($_POST['audio_upload'])) {
			$audio_upload = $_POST['audio_upload'];
		}
		if (! empty($_POST['smooth_loading'])) {
			$smooth_loading = $_POST['smooth_loading'];
		}
		if (! empty($_POST['groups'])) {
			$groups = $_POST['groups'];
		}
		if (! empty($_POST['developers_page'])) {
			$developers_page = $_POST['developers_page'];
		}
		if (! empty($_POST['maintenance_mode'])) {
			$maintenance_mode = $_POST['maintenance_mode'];
		}
		if (! empty($_POST['events'])) {
			$events = $_POST['events'];
		}
		if (! empty($_POST['profile_privacy'])) {
			$profile_privacy = $_POST['profile_privacy'];
		}
		$saved_data = array(
			'cache_system' => $cache_system,
			'chat_system' => $chat_system,
			'email_validation' => $email_validation,
			'sms_or_email' => $sms_or_email,
			'email_notification' => $email_notification,
			'seo_link' => $seo_link,
			'file_sharing' => $file_sharing,
			'use_seo_friendly' => $use_seo_friendly,
			'message_seen' => $message_seen,
			'message_typing' => $message_typing,
			'user_lastseen' => $user_lastseen,
			'delete_account' => $delete_account,
			'profile_visit' => $profile_visit,
			'video_upload' => $video_upload,
			'audio_upload' => $audio_upload,
			'smooth_loading' => $smooth_loading,
			'groups' => $groups,
			'developers_page' => $developers_page,
			'maintenance_mode' => $maintenance_mode,
			'events' => $events,
			'profile_privacy' => $profile_privacy
		);
		foreach ($saved_data as $key => $value) {
			$save_setting = saveConfig($key, $value);
		}
		if ($save_setting === true) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_general_setting'
	if ($s == 'update_site_setting' && checkSession($hash_id) === true) {
		$save_setting = false;
		if (! empty($_POST['reCaptcha'])) {
			$_POST['reCaptcha'] = 1;
		} else {
			$_POST['reCaptcha'] = 0;
		}
		foreach ($_POST as $key => $value) {
			if ($key != 'hash_id') {
				$save_setting = saveConfig($key, $value);
			}
		}
		if ($save_setting === true) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_site_setting'
	if ($s == 'add_new_announcement') {
		if (! empty($_POST['announcement_text'])) {
			$html = '';
			$id = addNewAnnouncement($_POST['announcement_text']);
			if ($id > 0) {
				$carovl['active_announcement'] = getAnnouncement($id);
				$html .= loadPage('admin/announcement/active-list');
				$data = array(
					'status' => 200,
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'add_new_announcement'
	if ($s == 'delete_announcement') {
		if (! empty($_GET['id'])) {
			$delete_announcement = deleteAnnouncement($_GET['id']);
			if ($delete_announcement === true) {
				$data = array(
					'status' => 200,
					'count_active' => count(getActiveAnnouncements()),
					'count_inactive' => count(getInactiveAnnouncements())
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_announcement'
	if ($s == 'disable_announcement') {
		if (! empty($_GET['id'])) {
			$html = '';
			$disable_announcement = disableAnnouncement($_GET['id']);
			if ($disable_announcement === true) {
				$carovl['inactive_announcement'] = getAnnouncement($_GET['id']);
				$html .= loadPage('admin/announcement/inactive-list');
				$data = array(
					'status' => 200,
					'count' => count(getActiveAnnouncements()),
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'disable_announcement'
	if ($s == 'enable_announcement') {
		if (! empty($_GET['id'])) {
			$html = '';
			$enable_announcement = enableAnnouncement($_GET['id']);
			if ($enable_announcement === true) {
				$carovl['active_announcement'] = getAnnouncement($_GET['id']);
				$html .= loadPage('admin/announcement/active-list');
				$data = array(
					'status' => 200,
					'count_inactive' => count(getInactiveAnnouncements()),
					'html' => $html
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'enable_announcement'
	if ($s == 'create_new_backup') {
		$backup = createNewBackup($db_host, $db_user, $db_pass, $db_name);
		if ($backup) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'create_new_backup'
	if ($s == 'test_server') {
		$send_message_data = array(
			'from_email' => $carovl['config']['site_email'],
			'from_name' => $carovl['config']['site_name'],
			'to_email' => $carovl['user']['email'],
			'to_name' => $carovl['user']['username'],
			'subject' => 'Test Message from ' . $carovl['config']['site_name'],
			'charSet' => 'utf-8',
			'message_body' => 'If you can see this message, then your SMTP configuration is working fine.',
			'is_html' => false
		);
		$send_message = sendEmailMessage($send_message_data);
		if ($send_message === true) {
			$data['status'] = 200;
		} else {
			$data['status'] = 300;
			$data['error'] = $mail->ErrorInfo;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'test_server'
	if ($s == 'update_email_setting' && checkSession($hash_id) === true) {
		$save_setting = false;
		foreach ($_POST as $key => $value) {
			if ($key != 'hash_id') {
				$save_setting = saveConfig($key, $value);
			}
		}
		if ($save_setting === true) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_email_setting'
	if ($s == 'update_social_login_setting' && checkSession($hash_id) === true) {
		$facebook_login = 0;
		$google_login = 0;
		$twitter_login = 0;
		if (! empty($_POST['facebook_login'])) {
			$facebook_login = 1;
		}
		if (! empty($_POST['google_login'])) {
			$google_login = 1;
		}
		if (! empty($_POST['twitter_login'])) {
			$twitter_login = 1;
		}
		$facebook_app_id = '';
		$facebook_app_key = '';
		if (! empty($_POST['facebook_app_id'])) {
			$facebook_app_id = $_POST['facebook_app_id'];
		}
		if (! empty($_POST['facebook_app_key'])) {
			$facebook_app_key = $_POST['facebook_app_key'];
		}
		$google_app_id = '';
		$google_app_key = '';
		if (! empty($_POST['google_app_id'])) {
			$google_app_id = $_POST['google_app_id'];
		}
		if (! empty($_POST['google_app_key'])) {
			$google_app_key = $_POST['google_app_key'];
		}
		$twitter_app_id = '';
		$twitter_app_key = '';
		if (! empty($_POST['twitter_app_id'])) {
			$twitter_app_id = $_POST['twitter_app_id'];
		}
		if (! empty($_POST['twitter_app_key'])) {
			$twitter_app_key = $_POST['twitter_app_key'];
		}
		$save_setting = false;
		$saved_data = array(
			'facebook_login' => $facebook_login,
			'google_login' => $google_login,
			'twitter_login' => $twitter_login,
			'facebook_app_id' => $facebook_app_id,
			'facebook_app_key' => $facebook_app_key,
			'google_app_id' => $google_app_id,
			'google_app_key' => $google_app_key,
			'twitter_app_id' => $twitter_app_id,
			'twitter_app_key' => $twitter_app_key
		);
		foreach ($saved_data as $key => $value) {
			$save_setting = saveConfig($key, $value);
		}
		if ($save_setting === true) {
			$data['status'] = 200;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_social_login_setting'
	if ($s == 'add_new_language' && checkSession($hash_id) === true) {
		$mysqli = langsNameFromDb();
		$_POST['lang'] = strtolower($_POST['lang']);
		if (in_array($_POST['lang'], $mysqli)) {
			$data['status'] = 300;
			$data['error'] = $carovl['lang']['language_already_exist'];
		} else {
			$lang_name = secureIt($_POST['lang']);
			$query = mysqli_query($sql_connect, "ALTER TABLE " . T_LANGS . " ADD `$lang_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL");
			if ($query) {
				$content = file_get_contents('assets/languages/add-ons/english.php');
				$f = fopen("assets/languages/add-ons/$lang_name.php", "wb");
				fwrite($f, $content);
				fclose($f);
				$english = langsFromDb('english');
				foreach ($english as $key => $lang) {
					$lang = mysqli_real_escape_string($sql_connect, $lang);
					$query = mysqli_query($sql_connect, "UPDATE " . T_LANGS . " SET `{$lang_name}` = '{$lang}' WHERE `lang_key` = '{$key}'");
				}
				$data['status'] = 200;
				$data['href'] = seoLink('index.php?page=admincp&tab=languages');
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'add_new_language'
	if ($s == 'add_new_language_keyword' && checkSession($hash_id) === true) {
		if (! empty($_POST['lang_key'])) {
			$lang_key = secureIt($_POST['lang_key']);
			$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_LANGS . " WHERE `lang_key` = '{$lang_key}'");
			$fetched_data = mysqli_fetch_assoc($query);
			if ($fetched_data['count'] == 0) {
				$query = mysqli_query($sql_connect, "INSERT INTO " . T_LANGS . " (`lang_key`) VALUE ('{$lang_key}')");
				if ($query) {
					$data['status'] = 200;
					$data['href'] = seoLink('index.php?page=admincp&tab=edit-key&id=' . $lang_key);
				}
			} else {
				$data['status'] = 300;
				$data['error'] = $carovl['lang']['keyword_already_used'];
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'add_new_language_keyword'
	if ($s == 'update_language_keyword' && checkSession($hash_id) === true) {
		$lang_data = array();
		$lang_key = secureIt($_POST['key_id']);
		$langs = langsNameFromDb();
		foreach ($_POST as $key => $value) {
			if (in_array($key, $langs)) {
				$key = secureIt($key);
				$value = mysqli_real_escape_string($sql_connect, $value);
				$query = mysqli_query($sql_connect, "UPDATE " . T_LANGS . " SET `{$key}` = '{$value}' WHERE `lang_key` = '{$lang_key}'");
				if ($query) {
					$data['status'] = 200;
				}
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_language_keyword'
	if ($s == 'delete_language' && checkMainSession($hash_id) === true) {
		$langs = langsNameFromDb();
		if (in_array($_GET['id'], $langs)) {
			$lang_name = secureIt($_GET['id']);
			$query = mysqli_query($sql_connect, "ALTER TABLE " . T_LANGS . " DROP COLUMN `$lang_name`");
			if ($query) {
				unlink("assets/languages/add-ons/$lang_name.php");
				$data['status'] = 200;
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'delete_language'
	if ($s == 'get_cache_size') {
		$html = sizeFormat(folderSize('cache'));
		$data = array(
			'status' => 200,
			'html' => $html
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_cache_size'
	if ($s == 'clear_cache') {
		clearCache();
		$data['status'] = 200;
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	}
	if ($s == 'send_mail') {
		if (empty($_POST['message']) || empty($_POST['subject'])) {
			$errors = $carovl['lang']['please_check_your_details'];
		} else {
			$recipient = 'all';
			if ($_POST['recipient'] == 'active') {
				$recipient = 'active';
			} elseif ($_POST['recipient'] == 'inactive') {
				$recipient = 'inactive';
			}
			$users = getAllUsersByType($recipient);
			foreach ($users as $user) {
				$send_message_data = array(
					'from_email' => $carovl['config']['site_email'],
					'from_name' => $carovl['config']['site_name'],
					'to_email' => $user['email'],
					'to_name' => $user['username'],
					'subject' => $_POST['subject'],
					'message_body' => $_POST['message'],
					'charSet' => 'utf-8',
					'is_html' => true
				);
				$send = sendEmailMessage($send_message_data);
			}
		}
		header("Content-type: application/json");
		if (! empty($errors)) {
			$errors_data = array(
				'status' => 300,
				'error' => $errors
			);
			echo json_encode($errors_data);
		} elseif (empty($errors)) {
			$data = array(
				'status' => 200
			);
			echo json_encode($data);
		}
		exit();
	} // end s == 'send_mail'
	if ($s == 'update_policy' && checkSession($hash_id) === true) {
		$save_setting = false;
		foreach ($_POST as $key => $value) {
			if ($key != 'hash_id') {
				$save_setting = savePolicy($key, $value);
			}
		}
		if ($save_setting === true) {
			$data = array(
				'status' => 200,
				'success' => $carovl['lang']['saved']
			);
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_policy'
	if ($s == 'update_ads') {
		if (! empty($_POST['type']) && ! empty($_POST['advertisement_text'])) {
			$ads_data = array(
				'type' => $_POST['type'],
				'code' => $_POST['advertisement_text']
			);
			if (updateAdsText($ads_data)) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_ads'
	if ($s == 'update_ads_status') {
		if (! empty($_GET['type'])) {
			if (updateAdsStatus($_GET['type']) == 'active') {
				$data = array(
					'status' => 200
				);
			} else {
				$data = array(
					'status' => 300
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_ads_status'
	if ($s == 'update_sms_setting') {
		if (checkSession($hash_id) === true) {
			$save_setting = false;
			foreach ($_POST as $key => $value) {
				if ($key != 'hash_id') {
					$save_setting = saveConfig($key, $value);
				}
			}
			if ($save_setting === true) {
				$data = array(
					'status' => 200
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'update_sms_setting'
	if ($s == 'test_sms') {
		$message = 'This is test message from ' . $carovl['config']['site_name'];
		$send_message = sendSmsMessage($carovl['config']['site_phone_number'], $message);
		if ($send_message === true) {
			$data['status'] = 200;
		} else {
			$data['status'] = 300;
			$data['error'] = $carovl['lang']['error_while_sending_sms'];
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'test_sms'
	if ($s == 'reset_desktop_app_key') {
		$app_key = sha1(md5(time())) . '-' . generateKey();
		$app_id = strtoupper(generateKey());
		$key_array = array(
			'desktop_app_id' => $app_id,
			'desktop_app_key' => $app_key
		);
		foreach ($key_array as $key => $value) {
			$save_setting = saveConfig($key, $value);
		}
		if ($save_setting == true) {
			$data = array(
				'status' => 200,
				'desktop_app_id' => $app_id,
				'desktop_app_key' => $app_key
			);
		} else {
			$data['status'] = 300;
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	}
} // end f == 'admin_setting'
if ($f == 'activities') {
	if ($s == 'get_more_activities') {
		if (! empty($_POST['after_activity_id'])) {
			$html = '';
			foreach (getActivities(array('after_activity_id' => secureIt($_POST['after_activity_id']))) as $carovl['activity']) {
				$html .= loadPage('activity/activity-list');
			}
			$data = array(
				'status' => 200,
				'html' => $html
			);
			if (empty($html)) {
				$data['no_activities'] = $carovl['lang']['no_more_activities'];
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'get_more_activities'
} // end f == 'activities'
if ($f == 'products') {
	if ($s == 'new_product' && checkSession($hash_id) === true) {
		if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])) {
			$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
		} elseif (empty($_POST['price']) || $_POST['price'] == '0.00') {
			$errors[] = $error_icon . $carovl['lang']['please_choose_price'];
		} elseif (! is_numeric($_POST['price'])) {
			$errors[] = $error_icon . $carovl['lang']['please_choose_value_price'];
		} elseif (empty($_FILES['product_image']['name'])) {
			$errors[] = $error_icon . $carovl['lang']['please_upload_thumbnail_image'];
		}
		if (isset($_FILES['product_image']['name'])) {
			$allowed = array(
				'gif',
				'png',
				'jpg',
				'jpeg'
			);
			for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) { 
				$extension = pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION);
				if (! in_array($extension, $allowed)) {
					$errors[] = $error_icon . $carovl['lang']['invalid_extension'];
				}
			}
		}
		$type = 0;
		if (! empty($_POST['type'])) {
			$type = 1;
		}
		if (empty($errors)) {
			$product_data = array(
				'user_id' => secureIt($carovl['user']['user_id']),
				'name' => secureIt($_POST['name']),
				'description' => secureIt($_POST['description']),
				'category' => secureIt($_POST['category']),
				'time' => secureIt(time()),
				'price' => secureIt($_POST['price']),
				'type' => $type,
				'location' => secureIt($_POST['location']),
				'active' => secureIt(1)
			);
			$register_product = registerProduct($product_data);
			$post_data = array(
				'user_id' => secureIt($carovl['user']['user_id']),
				'product_id' => secureIt($register_product),
				'post_privacy' => secureIt(0),
				'time' => time()
			);
			$register_post = registerPost($post_data);
			if (count($_FILES['product_image']['name']) > 0 && ! empty($register_post) && $register_post > 0) {
				for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) { 
					$fileinfo = array(
						'file' => $_FILES['product_image']['tmp_name'][$i],
						'name' => $_FILES['product_image']['name'][$i],
						'size' => $_FILES['product_image']['size'][$i],
						'type' => $_FILES['product_image']['type'][$i],
						'types' => 'jpg,png,jpeg,gif'
					);
					$media = shareFile($fileinfo, 1);
					if (! empty($media)) {
						$register_product_media = registerProductMedia($register_product, $media['filename']);
					}
				}
			}
			$data = array(
				'status' => 200,
				'href' => seoLink('index.php?page=product&id=' . $register_product . '_' . slugPost($_POST['name']))
			);
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'new_product'
	if ($s == 'edit_product' && checkSession($hash_id) === true) {
		if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])) {
			$errors[] = $error_icon . $carovl['lang']['please_check_your_details'];
		} elseif (empty($_POST['price']) || $_POST['price'] == '0.00') {
			$errors[] = $error_icon . $carovl['lang']['please_choose_price'];
		} elseif (! is_numeric($_POST['price'])) {
			$errors[] = $error_icon . $carovl['lang']['please_choose_value_price'];
		}
		if (isset($_FILES['product_image']['name'])) {
			$allowed = array(
				'gif',
				'png',
				'jpg',
				'jpeg'
			);
			for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
				$extension = pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION);
				if (! in_array($extension, $allowed)) {
					$errors[] = $error_icon . $carovl['lang']['invalid_extension'];
				}
			}
		}
		$type = 0;
		if (! empty($_POST['type'])) {
			$type = 1;
		}
		if (empty($errors)) {
			$product_data = array(
				'name' => $_POST['name'],
				'category' => $_POST['category'],
				'description' => $_POST['description'],
				'price' => secureIt($_POST['price']),
				'location' => secureIt($_POST['location']),
				'type' => $type
			);
			$update_product = updateProduct($_POST['product_id'], $product_data);
			$id = getPostIdFromProductId($_POST['product_id']);
			if (isset($_FILES['product_image']['name'])) {
				if (count($_FILES['product_image']['name']) > 0 && ! empty($id) && $id > 0) {
					for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) { 
						$fileinfo = array(
							'file' => $_FILES['product_image']['tmp_name'][$i],
							'name' => $_FILES['product_image']['name'][$i],
							'size' => $_FILES['product_image']['size'][$i],
							'type' => $_FILES['product_image']['type'][$i],
							'types' => 'jpg,png,jpeg,gif'
						);
						$media = shareFile($fileinfo, 1);
						if (! empty($media)) {
							$register_product_media = registerProductMedia($_POST['product_id'], $media['filename']);
						}
					}
				}
			}
			$data = array(
				'status' => 200,
				'href' => seoLink('index.php?page=product&id=' . $_POST['product_id'] . '_' . slugPost($_POST['name']))
			);
		}
		header("Content-type: application/json");
		if (isset($errors)) {
			echo json_encode(array('errors' => $errors));
		} else {
			echo json_encode($data);
		}
		exit();
	} // end s == 'edit_product'
	if ($s == 'mark_as_sold') {
		if (! empty($_GET['post_id']) && ! empty($_GET['product_id']) && checkMainSession($hash_id) === true) {
			if (markProductAsSold($_GET['post_id'], $_GET['product_id'])) {
				$data = array(
					'status' => 200,
					'text' => $carovl['lang']['sold']
				);
			}
		}
		header("Content-type: application/json");
		echo json_encode($data);
		exit();
	} // end s == 'mark_as_sold'
} // end f == 'products'
if ($f == 'view_announcement') {
	if (isset($_GET['id']) && ! empty($_GET['id']) && $_GET['id'] > 0 && checkMainSession($hash_id) === true) {
		$id = $_GET['id'];
		if (viewAnnouncement($id)) {
			$data['status'] = 200;
			$data['date'] = time();
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
} // end  f == 'view_announcement'
mysqli_close($sql_connect);
unset($carovl);
?>