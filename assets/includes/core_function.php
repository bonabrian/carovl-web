<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('load_app.php');
use Twilio\Rest\Client;

// Save Config
function saveConfig($update_name, $value)
{
	global $carovl, $config, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! array_key_exists($update_name, $config)) {
		return false;
	}
	$update_name = secureIt($update_name);
	$value = mysqli_real_escape_string($sql_connect, $value);
	$query = mysqli_query($sql_connect, "UPDATE " . T_CONFIG . " SET `value` = '{$value}' WHERE `name` = '{$update_name}'");
	if ($query) {
		return true;
	} else {
		return false;
	}
}
// Save Policy
function savePolicy($update_name, $value)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$update_name = secureIt($update_name);
	$value = mysqli_real_escape_string($sql_connect, $value);
	$query = mysqli_query($sql_connect, "UPDATE " . T_POLICY . " SET `text` = '{$value}' WHERE `type` = '{$update_name}'");
	if ($query) {
		return true;
	} else {
		return false;
	}
}
function checkUserSessionId($user_id = 0, $session_id = '', $platform = 'web')
{
    global $carovl, $sql_connect;
    if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($session_id)) {
        return false;
    }
    $platform = secureIt($platform);
    $query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `session` FROM " . T_SESSIONS . " WHERE `user_id` = {$user_id} AND `session_id` = '{$session_id}' AND `platform` = '{$platform}'");
    $fetched_data = mysqli_fetch_assoc($query);
    if ($fetched_data['session'] > 0) {
    	return true;
    }
    return false;
}
// Redirect Smooth
function redirectSmooth($url)
{
	global $carovl;
	if ($carovl['config']['smooth_loading'] == 0) {
		return header("Location: $url");
		exit();
	} else {
		return $carovl['redirect'] = 1;
	}
}
// Get Configuration From Database
function getConfig()
{
	global $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_CONFIG);
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[$fetched_data['name']] = $fetched_data['value'];
	}
	return $data;
}
// Get Banned Users
function getBanned($type = '')
{
	global $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_BANNED_IP . " ORDER BY `id` DESC");
	if ($type == 'user') {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$data[] = $fetched_data['ip_address'];
		}
	} else {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$data[] = $fetched_data;
		}
	}
	return $data;
}
function getLangDetails($lang_key = '')
{
	global $sql_connect;
	if (empty($lang_key)) {
		return false;
	}
	$lang_key = secureIt($lang_key);
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_LANGS . " WHERE `lang_key` = '{$lang_key}'");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		unset($fetched_data['lang_key']);
		unset($fetched_data['id']);
		$data[] = $fetched_data;
	}
	return $data;
}
// Get Language Keys From Database
function langsFromDb($lang = 'english')
{
	global $carovl, $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `lang_key`, `$lang` FROM " . T_LANGS);
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[$fetched_data['lang_key']] = htmlspecialchars_decode($fetched_data[$lang]);
	}
	return $data;
}
// Get All Languages From Database
function langsNameFromDb($lang = 'english')
{
	global $carovl, $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SHOW COLUMNS FROM " . T_LANGS);
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = $fetched_data['Field'];
	}
	unset($data[0]);
	unset($data[1]);
	return $data;
}
// Creating Main Session
function createMainSession()
{
	$hash = substr(sha1(rand(1111, 9999)), 0, 20);
	if (! empty($_SESSION['main_hash_id'])) {
		$_SESSION['main_hash_id'] = $_SESSION['main_hash_id'];
		return $_SESSION['main_hash_id'];
	}
	$_SESSION['main_hash_id'] = $hash;
	return $hash;
}
function checkMainSession($hash = '')
{
	if (! isset($_SESSION['main_hash_id']) || empty($_SESSION['main_hash_id'])) {
		return false;
	}
	if (empty($hash)) {
		return false;
	}
	if ($hash == $_SESSION['main_hash_id']) {
		return true;
	}
	return false;
}
// Activate User 
function activateUser($email, $code)
{
	global $sql_connect;
	$email = secureIt($email);
	$code = secureIt($code);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `email` = '{$email}' AND `email_code` = '{$code}' AND `active` = '0'");
	$result = sqlResult($query, 0);
	if ($result == 1) {
		mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `active` = '1' WHERE `email` = '{$email}'");
		return true;
	} else {
		return false;
	}
}
// Check If Post Exist
function isPostExist($post_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_POSTS . " WHERE `id` = {$post_id}");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Check If Username Exists
function isUsernameExist($username, $active = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($username)) {
		return false;
	}
	$active_text = '';
	if ($active == 1) {
		$active_text = " AND `active` = '1'";
	}
	$username = secureIt($username);
	$query = mysqli_query($sql_connect, "SELECT (SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `username` = '{$username}' {$active_text}) AS `users`, (SELECT COUNT(`id`) FROM " . T_GROUPS . " WHERE `group_name` = '{$username}' {$active_text}) AS `groups`");
	$fetched_data = mysqli_fetch_assoc($query);
	if ($fetched_data['users'] == 1) {
		return array(
			true,
			'type' => 'user'
		);
	} elseif ($fetched_data['groups'] == 1) {
		return array(
			true,
			'type' => 'group'
		);
	} else {
		return array(
			false
		);
	}
}
// Check If Email Exist
function isEmailExist($email)
{
	global $sql_connect;
	if (empty($email)) {
		return false;
	}
	$email = secureIt($email);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `email` = '{$email}'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Check If Phone Number Exist
function isPhoneExist($phone)
{
	global $sql_connect;
	if (empty($phone)) {
		return false;
	}
	$phone = secureIt($phone);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `phone_number` = '{$phone}'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Import Profile Photo from URL
function importImageFromLogin($media, $email = '')
{
	global $carovl;
	$email = secureIt($email);
	if (! file_exists('uploads/photos/' . date('Y'))) {
		mkdir('uploads/photos/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/photos/' . date('Y') . '/' . date('m'))) {
		mkdir('uploads/photos/' . date('Y') . '/' . date('m'), 0777, true);
	}
	$dir = 'uploads/photos/' . date('Y') . '/' . date('m');
	$file_dir = $dir . '/' . generateKey() . '_avatar.jpg';
	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer" => false,
			"verify_peer_name" => false
		)
	);
	$get_image = file_get_contents($media, false, stream_context_create($arrContextOptions));
	if (! empty($get_image)) {
		$import_image = file_put_contents($file_dir, $get_image);
		if ($import_image) {
			resizeCropImage($carovl['profile_picture_width_crop'], $carovl['profile_picture_height_crop'], $file_dir, $file_dir, 100);
		}
	}
	if (file_exists($file_dir)) {
		return $file_dir;
	} else {
		return getDefaultAvatar($email);
	}
}
// Register User
function registerUser($registration_data)
{
	global $carovl, $sql_connect;
	if (empty($registration_data)) {
		return false;
	}
	$ip = '0.0.0.0';
	$get_ip = getIpAddress();
	if (! empty($get_ip)) {
		$ip = $get_ip;
	}
	$registration_data['registered'] = date('n') . '/' . date('Y');
	$registration_data['joined'] = time();
	$registration_data['password'] = md5($registration_data['password']);
	$registration_data['ip_address'] = secureIt($ip);
	$registration_data['language'] = $carovl['config']['default_lang'];
	if (! empty($_SESSION['lang'])) {
		$lang_name = strtolower($_SESSION['lang']);
		$lang_path = 'assets/languages/' . $lang_name . '.php';
		if (file_exists($lang_path)) {
			$registration_data['language'] = secureIt($lang_name);
		}
	}
	$fields = '`' . implode('`, `', array_keys($registration_data)) . '`';
	$data = '\'' . implode('\', \'', $registration_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_USERS . " ({$fields}) VALUES ({$data})");
	$user_id = mysqli_insert_id($sql_connect);
	if ($query) {
		return true;
	} else {
		return false;
	}
}
// Login User
function loginUser($username, $password)
{
	global $sql_connect;
	if (empty($username) || empty($password)) {
		return false;
	}
	$username = secureIt($username);
	$password = secureIt(md5($password));
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}') AND `password` = '{$password}'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Creating Login Session
function setLoginWithSession($email)
{
	if (empty($email)) {
		return false;
	}
	$email = secureIt($email);
	$_SESSION['user_id'] = createLoginSession(userIdFromEmail($email));
}
function createLoginSession($user_id = 0)
{
	global $sql_connect;
	if (empty($user_id)) {
		return false;
	}
	$user_id = secureIt($user_id);
	$hash = sha1(rand(111111111, 999999999)) . md5(microtime()) . rand(111111111, 999999999) . md5(rand(5555, 9999));
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_SESSIONS . " WHERE `session_id` = '{$hash}'");
	if ($query) {
		$query_two = mysqli_query($sql_connect, "INSERT INTO " . T_SESSIONS . " (`user_id`, `session_id`, `platform`, `time`) VALUES ('{$user_id}', '{$hash}', 'web', " . time() . ")");
		if ($query_two) {
			return $hash;
		}
	}
}
// Get User ID From Email
function userIdFromEmail($email)
{
	global $sql_connect;
	if (empty($email)) {
		return false;
	}
	$email = secureIt($email);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_USERS . " WHERE `email` = '{$email}'");
	return sqlResult($query, 0, 'user_id');
}
// Send Email Message
function sendEmailMessage($data = array())
{
	global $carovl, $sql_connect, $mail;
	$from_email = $data['from_email'] = secureIt($data['from_email']);
	$to_email = $data['to_email'] = secureIt($data['to_email']);
	$subject = $data['subject'];
	$message_body = mysqli_real_escape_string($sql_connect, $data['message_body']);
	$data['charSet'] = secureIt($data['charSet']);
	if (isset($data['insert_database'])) {
		if ($data['insert_database'] == 1) {
			$user_id = secureIt($carovl['user']['user_id']);
			$query = mysqli_query($sql_connect, "INSERT INTO " . T_EMAILS . " (`email_to`, `user_id`, `subject`, `message`) VALUES ('{$to_email}', '{$user_id}', '{$subject}', '{$message_body}')");
			if ($query) {
				return true;
			}
		}
		return true;
		exit();
	}
	if ($carovl['config']['smtp_or_mail'] == 'mail') {
		$mail->IsMail();
	} elseif ($carovl['config']['smtp_or_mail'] == 'smtp') {
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $carovl['config']['smtp_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $carovl['config']['smtp_username'];
		$mail->Password = $carovl['config']['smtp_password'];
		$mail->SMTPSecure = $carovl['config']['smtp_encryption'];
		$mail->Port = $carovl['config']['smtp_port'];
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
	} else {
		return false;
	}
	$mail->IsHTML($data['is_html']);
	$mail->setFrom($data['from_email'], $data['from_name']);
	$mail->addAddress($data['to_email'], $data['to_name']);
	$mail->Subject = $data['subject'];
	$mail->CharSet = $data['charSet'];
	$mail->MsgHTML($data['message_body']);
	if (! empty($data['reply-to'])) {
		$mail->ClearReplyTos();
		$mail->AddReplyTo($data['reply-to'], $data['from_name']);
	}
	if ($mail->send()) {
		$mail->ClearAddresses();
		return true;
	}
}
// Username From Email
function getUsernameFromEmail($email, $user_id)
{
	global $sql_connect;
	if (empty($email)) {
		return false;
	}
	if (empty($username)) {
		return false;
	}
	$email = secureIt($email);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `username` FROM " . T_USERS . " WHERE `email` = '{$email}' AND `user_id` = {$user_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		$username = $fetched_data['username'];
		return $username;
	}
}
// Get User ID From Username
function userIdFromUsername($username)
{
	global $sql_connect;
	if (empty($username)) {
		return false;
	}
	$username = secureIt($username);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}'");
	return sqlResult($query, 0, 'user_id');
}
// Get User ID From Session ID
function getUserIdFromPostId($post_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_POSTS . " WHERE `id` = {$post_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['user_id'];
	}
}
// Get User ID From Session ID
function getUserIdFromSessionId($session_id, $platform = 'web')
{
	global $sql_connect;
	if (empty($session_id)) {
		return false;
	}
	$platform = secureIt($platform);
	$session_id = secureIt($session_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_SESSIONS . " WHERE `session_id` = '{$session_id}' AND `platform` = '{$platform}'");
	return sqlResult($query, 0, 'user_id');
}
// Get User Data
function userData($user_id)
{
	global $carovl, $sql_connect, $cache;
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$data = array();
	$user_id = secureIt($user_id);
	$query = "SELECT * FROM " . T_USERS . " WHERE `user_id` = {$user_id}";
	$hashed_user_id = md5($user_id);
	if ($carovl['config']['cache_system'] == 1) {
		$fetched_data = $cache->read($hashed_user_id . '_U_Data.tmp');
		if (empty($fetched_data)) {
			$sql = mysqli_query($sql_connect, $query);
			$fetched_data = mysqli_fetch_assoc($sql);
			$cache->write($hashed_user_id . '_U_Data.tmp', $fetched_data);
		}
	} else {
		$sql = mysqli_query($sql_connect, $query);
		$fetched_data = mysqli_fetch_assoc($sql);
	}
	if (empty($fetched_data)) {
		return false;
	}
	$fetched_data['avatar_org'] = $fetched_data['avatar'];
	$fetched_data['cover_org'] = $fetched_data['cover'];
	$explode = @end(explode('.', $fetched_data['cover']));
	$explode2 = @explode('.', $fetched_data['cover']);
	$fetched_data['cover_full'] = $carovl['user_default_cover'];
	if ($fetched_data['cover'] != $carovl['user_default_cover']) {
		@$fetched_data['cover_full'] = $explode2[0] . '_full.' . $explode;
	}
	$fetched_data['avatar'] = getMedia($fetched_data['avatar']);
	$fetched_data['cover'] = getMedia($fetched_data['cover']);
	$fetched_data['id'] = $fetched_data['user_id'];
	$fetched_data['type'] = 'user';
	$fetched_data['url'] = seoLink('index.php?page=timeline&u=' . $fetched_data['username']);
	$fetched_data['name'] = '';
	if (! empty($fetched_data['first_name'])) {
		if (! empty($fetched_data['last_name'])) {
			$fetched_data['name'] = $fetched_data['first_name'] . ' ' . $fetched_data['last_name'];
		} else {
			$fetched_data['name'] = $fetched_data['first_name'];
		}
	} else {
		$fetched_data['name'] = $fetched_data['username'];
	}
	return $fetched_data;
}
function getMedia($media)
{
	global $carovl;
	if (empty($media)) {
		return '';
	}
	if ($carovl['config']['amazone_s3'] == 1) {
		if (empty($carovl['config']['amazone_s3_key']) || empty($carovl['config']['amazone_s3_s_key']) || empty($carovl['config']['region']) || empty($carovl['config']['bucket_name'])) {
			return $carovl['config']['site_url'] . '/' . $media;
		}
		return $carovl['config']['s3_site_url'] . '/' . $media;
	}
	return $carovl['config']['site_url'] . '/' . $media;
}
// Check If User Disabled By Admin
function isUserInactive($username)
{
	global $sql_connect;
	if (empty($username)) {
		return false;
	}
	$username = secureIt($username);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}') AND `active` = '2'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Check If User Active
function isUserActive($username)
{
	global $sql_connect;
	if (empty($username)) {
		return false;
	}
	$username = secureIt($username);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}') AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Update Last Seen
function updateLastSeen($user_id, $type = '', $session_id = 0)
{
	global $carovl, $sql_connect, $cache;
	if (empty($session_id)) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if ($type == 'first') {
		$user = userData($user_id);
		if ($user['status'] == 1) {
			return false;
		}
	} else {
		if ($carovl['user']['status'] == 1) {
			return false;
		}
	}
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `lastseen` = " . time() . " WHERE `user_id` = {$user_id} AND `active` = '1'");
	if ($query) {
		if ($carovl['config']['cache_system'] == 1) {
			$cache->delete(md5($user_id) . '_U_Data.tmp');
		}
		return true;
	} else {
		return false;
	}
}
// Get User Id For Activating Account
function userIdForLogin($username)
{
	global $sql_connect;
	if (empty($username)) {
		return false;
	}
	$username = secureIt($username);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}'");
	return sqlResult($query, 0, 'user_id');
}
// Check If User Has Complete The Startup
function isUserComplete($user_id)
{
	global $sql_connect;
	if (empty($user_id)) {
		return false;
	}
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `user_id` = '{$user_id}' AND `getstarted` = '0'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Upload an Image
function uploadImage($file, $name, $type, $filetype, $user_id = 0, $placement = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($file) || empty($name) || empty($type) || empty($user_id)) {
		return false;
	}
	$extension = pathinfo($name, PATHINFO_EXTENSION);
	if (! file_exists('uploads/photos/' . date('Y'))) {
		mkdir('uploads/photos/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/photos/' . date('Y') . '/' . date('m'))) {
		mkdir('uploads/photos/' . date('Y') . '/' . date('m'), 0777, true);
	}
	$allowed = 'jpg,png,jpeg,gif';
	$new_string = pathinfo($name, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($name, PATHINFO_EXTENSION));
	$extension_allowed = explode(',', $allowed);
	$file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
	if (! in_array($file_extension, $extension_allowed)) {
		return false;
	}
	$image_array = array(
		'image/png',
		'image/jpeg',
		'image/gif'
	);
	if (! in_array($filetype, $image_array)) {
		return false;
	}
	$dir = 'uploads/photos/' . date('Y') . '/' . date('m');
	if ($placement == 'group') {
		$image_data['id'] = secureIt($user_id);
	} elseif ($placement == 'event') {
		$image_data['event_id'] = secureIt($user_id);
	} else {
		$image_data['user_id'] = secureIt($user_id);
	}
	if ($type == 'cover') {
		if ($placement == 'group') {
			$query = mysqli_query($sql_connect, "SELECT `cover` FROM " . T_GROUPS . " WHERE `id` = " . $image_data['id'] . " AND `active` = '1'");
		} elseif ($placement == 'event') {
			$query = mysqli_query($sql_connect, "SELECT `cover` FROM " . T_EVENTS . " WHERE `id` = " . $image_data['event_id']);
		} else {
			$query = mysqli_query($sql_connect, "SELECT `cover` FROM " . T_USERS . " WHERE `user_id` = " . $image_data['user_id'] . " AND `active` = '1'");
		}
		$fetched_data = mysqli_fetch_assoc($query);
		$filename = $dir . '/' . generateKey() . '_' . date('d') . '_' . md5(microtime()) . '_cover.' . $extension;
		$image_data['cover'] = $filename;
		if (move_uploaded_file($file, $filename)) {
			$update_data = false;
			if ($placement == 'group') {
				$update_data = updateGroupData($image_data['id'], $image_data);
			} elseif ($placement == 'event') {
				$update_data = updateEvent($image_data['event_id'], array('cover' => $image_data['cover']));
			} else {
				$update_data = updateUserData($image_data['user_id'], $image_data);
				if ($update_data) {
					$last_file = $filename;
					$explode = @end(explode('.', $filename));
					$explode2 = @explode('.', $filename);
					$last_file = $explode2[0] . '_full.' . $explode;
					@compressImage($filename, $last_file, 50);
					//$upload_to_s3 = uploadToS3($last_file);
					$register_cover_image = registerPost(array(
						'user_id' => secureIt($image_data['user_id']),
						'post_file' => secureIt($last_file),
						'time' => time(),
						'post_type' => 'profile_cover_picture'
					));
				}
			}
			if ($update_data == true) {
				resizeCropImage(918, 300, $filename, $filename, 80);
				//$upload_to_s3 = uploadToS3($filename);
				return true;
			}
			return true;
		}
	} elseif ($type == 'avatar') {
		$filename = $dir . '/' . generateKey() . '_' . date('d') . '_' . md5(microtime()) . '_avatar.' . $extension;
		$image_data['avatar'] = $filename;
		if (move_uploaded_file($file, $filename)) {
			if ($placement == 'group') {
				$update_data = updateGroupData($image_data['id'], $image_data);
				resizeCropImage($carovl['profile_picture_width_crop'], $carovl['profile_picture_height_crop'], $filename, $filename, $carovl['profile_picture_image_quality']);
				return true;
			} else {
				$image_data['getstarted_image'] = 1;
				if (updateUserData($image_data['user_id'], $image_data)) {
					$explode = @end(explode('.', $filename));
					$explode2 = @explode('.', $filename);
					$last_file = $explode2[0] . '_full.' . $explode;
					$compress = compressImage($filename, $last_file, 50);
					if ($compress) {
						//$uploadToS3 = uploadToS3($last_file);
						$register_avatar_image = registerPost(array(
							'user_id' => secureIt($image_data['user_id']),
							'post_file' => secureIt($last_file, 0),
							'time' => time(),
							'post_type' => 'profile_picture'
						));
						resizeCropImage($carovl['profile_picture_width_crop'], $carovl['profile_picture_height_crop'], $filename, $filename, $carovl['profile_picture_image_quality']);
						//$upload_s3 = uploadToS3($filename);
					}
					return true;
				}
			}
		}
	}
}
// Update User Data
function updateUserData($user_id, $update_data)
{
	global $carovl, $sql_connect, $cache;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	$user_id = secureIt($user_id);
	if (isAdmin() === false && isModerator() === false) {
		if ($carovl['user']['user_id'] != $user_id) {
			return false;
		}
	}
	if (isset($update_data['verified'])) {
		if (isAdmin() === false && isModerator() === false) {
			return false;
		}
	}
	if (isset($update_data['country_id'])) {
		if (! array_key_exists($update_data['country_id'], $carovl['countries_name'])) {
			$update_data['country_id'] = 1;
		}
	}
	$update = array();
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . secureIt($data, 0) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET {$implode} WHERE `user_id` = {$user_id}");
	if ($carovl['config']['cache_system'] == 1) {
		$cache->delete(md5($user_id) . '_U_Data.tmp');
	}
	if ($query) {
		if (! empty($update_data['username'])) {
			updateUsernameInNotifications($user_id, $update_data['username']);
		}
		return true;
	} else {
		return false;
	}
}
// Check If User Admin
function isAdmin($user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($user_id);
	if (! empty($user_id) && $user_id > 0) {
		$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) AS `count` FROM " . T_USERS . " WHERE `admin` = '1' AND `user_id` = {$user_id}");
		$sql = mysqli_fetch_assoc($query);
		if ($sql['count'] > 0) {
			return true;
		} else {
			return false;
		}
	}
	if ($carovl['user']['admin'] == 1) {
		return true;
	}
	return false;
}
// Check If User Moderator
function isModerator($user_id = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if ($carovl['user']['admin'] == 2) {
		return true;
	}
	return false;
}
// Update On Notification Where User Change Username
function updateUsernameInNotifications($user_id = 0, $username = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if (empty($username)) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_NOTIFICATIONS . " SET `url` = 'index.php?page=timeline&u={$username}' WHERE `notifier_id` = {$user_id} AND (`type` = 'following' OR `type` = 'comment_mention' OR `type` = 'post_mention' OR `type` = 'liked_post' OR `type` = 'share_post' OR `type` = 'comment' OR `type` = 'comment_reply' OR `type` = 'comment_reply_mention' OR `type` = 'liked_comment' OR `type` = 'liked_reply_comment' OR `type` = 'visited_profile' OR `type` = 'accepted_request')");
	if ($query) {
		return true;
	}
}
// Register a Post
function registerPost($post_data = array())
{
	global $carovl, $sql_connect;
	if (empty($post_data['user_id']) || $post_data['user_id'] == 0) {
		$post_data['user_id'] = $carovl['user']['user_id'];
	}
	if (! is_numeric($post_data['user_id']) || $post_data['user_id'] < 0) {
		return false;
	}
	if ($post_data['user_id'] == $carovl['user']['user_id']) {
		$timeline = $carovl['user'];
	} else {
		$post_data['user_id'] = secureIt($post_data['user_id']);
		$timeline = userData($post_data['user_id']);
	}
	if ($timeline['user_id'] != $carovl['user']['user_id']) {
		return false;
	}
	if (! empty($post_data['group_id'])) {
		if (canBeOnGroup($post_data['group_id']) === false) {
			return false;
		}
	}
	if (! empty($post_data['post_text'])) {
		if ($carovl['config']['max_characters'] > 0) {
			if (mb_strlen($post_data['post_text']) > $carovl['config']['max_characters']) {
				return false;
			}
		}
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
		$i = 0;
		preg_match_all($link_regex, $post_data['post_text'], $matches);
		foreach ($matches[0] as $match) {
			$match_url = strip_tags($match);
			$syntax = '[a]' . urlencode($match_url) . '[/a]';
			$post_data['post_text'] = str_replace($match, $syntax, $post_data['post_text']);
		}
		$mention_regex = '/@([A-Za-z0-9_]+)/i';
		preg_match_all($mention_regex, $post_data['post_text'], $matches);
		foreach ($matches[1] as $match) {
			$match = secureIt($match);
			$match_user = userData(userIdFromUsername($match));
			$match_search = '@' . $match;
			$match_replace = '@[' . $match_user['user_id'] . ']';
			if (isset($match_user['user_id'])) {
				$post_data['post_text'] = str_replace($match_search, $match_replace, $post_data['post_text']);
				$mentions[] = $match_user['user_id'];
			}
		}
		$hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
		preg_match_all($hashtag_regex, $post_data['post_text'], $matches);
		foreach ($matches[1] as $match) {
			if (! is_numeric($match)) {
				$hashdata = getHashtag($match);
				if (is_array($hashdata)) {
					$match_search = '#' . $match;
					$match_replace = '#[' . $hashdata['id'] . ']';
					if (mb_detect_encoding($match_search, 'ASCII', true)) {
						$post_data['post_text'] = preg_replace("/$match_search\b/i", $match_replace, $post_data['post_text']);
					} else {
						$post_data['post_text'] = str_replace($match_search, $match_replace, $post_data['post_text']);
					}
					$hashtag_query = mysqli_query($sql_connect, "UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id']);
				}
			}
		}
	}
	$post_data['registered'] = date('n') . '/' . date('Y');
	if (empty($post_data['multi_image'])) {
		$post_data['multi_image'] = 0;
	}
	if (empty($post_data['post_text']) && empty($post_data['album_name']) && empty($post_data['post_file']) && empty($post_data['post_map']) && $post_data['multi_image'] == 0 && empty($post_data['product_id']) && empty($post_data['article_id']) && empty($post_data['event_id'])) {
		return false;
	}
	if (! isset($post_data['post_type'])) {
		$post_data['post_type'] = 'post';
	}
	$fields = '`' . implode('`, `', array_keys($post_data)) . '`';
	$data = '\'' . implode('\', \'', $post_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_POSTS . " ({$fields}) VALUES ({$data})");
	$post_id = mysqli_insert_id($sql_connect);
	if ($query) {
		mysqli_query($sql_connect, "UPDATE " . T_POSTS . " SET `post_id` = {$post_id} WHERE `id` = {$post_id}");
		if (isset($mentions) && is_array($mentions)) {
			foreach ($mentions as $mention) {
				$notification_data = array(
					'recipient_id' => $mention,
					'type' => 'post_mention',
					'url' => 'index.php?page=post&id=' . $post_id
				);
				registerNotification($notification_data);
			}
		}
		return $post_id;
	}
}
// Get Hashtag From String
function getHashtag($tag = '', $type = true)
{
	global $sql_connect;
	$create = false;
	if (empty($tag)) {
		return false;
	}
	$tag = secureIt($tag);
	$md5_tag = md5($tag);
	if (is_numeric($tag)) {
		$query = "SELECT * FROM " . T_HASHTAGS . " WHERE `id` = {$tag}";
	} else {
		$query = "SELECT * FROM " . T_HASHTAGS . " WHERE `hash` = '{$md5_tag}'";
		$create = true;
	}
	$sql = mysqli_query($sql_connect, $query);
	if (mysqli_num_rows($sql) == 1) {
		$fetched_data = mysqli_fetch_assoc($sql);
		return $fetched_data;
	} elseif (mysqli_num_rows($sql) == 0 && $type == true) {
		if ($create == true) {
			$hash = md5($tag);
			$query_two = mysqli_query($sql_connect, "INSERT INTO " . T_HASHTAGS . " (`hash`, `tag`, `last_trend_time`) VALUES ('{$hash}', '{$tag}', " . time() . ")");
			if ($query_two) {
				$hashtag_id = mysqli_insert_id($sql_connect);
				$data = array(
					'id' => $hashtag_id,
					'hash' => $hash,
					'tag' => $tag,
					'last_trend_time' => time(),
					'trend_use_num' => 0
				);
				return $data;
			}
		}
	}
}
// Register New Notification
function registerNotification($data = array())
{
	global $carovl, $sql_connect;
	if (empty($data['session_id'])) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	if (! isset($data['recipient_id']) || empty($data['recipient_id']) || ! is_numeric($data['recipient_id']) || $data['recipient_id'] < 1) {
		return false;
	}
	if (isBlocked($data['recipient_id'])) {
		return false;
	}
	if (! isset($data['post_id']) || empty($data['post_id'])) {
		$data['post_id'] = 0;
	}
	if (! is_numeric($data['post_id']) || $data['recipient_id'] < 0) {
		return false;
	}
	if (empty($data['notifier_id']) || $data['notifier_id'] == 0) {
		$data['notifier_id'] = secureIt($carovl['user']['user_id']);
	}
	if (! is_numeric($data['notifier_id']) || $data['notifier_id'] < 1) {
		return false;
	}
	if ($data['notifier_id'] == $carovl['user']['user_id']) {
		$notifier = $carovl['user'];
	} else {
		$data['notifier_id'] = secureIt($data['notifier_id']);
		$notifier = userData($data['notifier_id']);
		if (! isset($notifier['user_id'])) {
			return false;
		}
	}
	if ($notifier['user_id'] != $carovl['user']['user_id']) {
		return false;
	}
	if ($data['recipient_id'] == $data['notifier_id']) {
		return false;
	}
	if (! isset($data['text'])) {
		$data['text'] = '';
	}
	if (! isset($data['type']) || empty($data['type'])) {
		return false;
	}
	if (! isset($data['url']) || empty($data['url'])) {
		return false;
	}
	$recipient = userData($data['recipient_id']);
	if (! isset($recipient['user_id'])) {
		return false;
	}
	$url = $data['url'];
	$recipient['user_id'] = secureIt($recipient['user_id']);
	$data['post_id'] = secureIt($data['post_id']);
	$data['type'] = secureIt($data['type']);
	if (! empty($data['type2'])) {
		$data['type2'] = secureIt($data['type2']);
	} else {
		$data['type2'] = '';
	}
	if ($data['text'] != strip_tags($data['text'])) {
		$data['text'] = '';
	}
	$data['text'] = secureIt($data['text']);
	$notifier['user_id'] = secureIt($notifier['user_id']);
	$group_notification_query = '';
	$group_notification_query2 = '';
	if (! empty($data['group_id']) && $data['group_id'] > 0) {
		$group = groupData($data['group_id']);
		if (! isset($group['id'])) {

		}
		$group_id = secureIt($group['id']);
		$group_notification_query = '`group_id`, ';
		$group_notification_query2 = "{$group_id}, ";
	}
	$event_notification_query = '';
	$event_notification_query2 = '';
	if (! empty($data['event_id']) && $data['event_id'] > 0) {
		$event = eventData($data['event_id']);
		$event_id = secureIt($event['id']);
		$event_notification_query = '`event_id`, ';
		$event_notification_query2 = "{$event_id}, ";
	}
	$query_one = mysqli_query($sql_connect, "SELECT `id` FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $recipient['user_id'] . " AND `post_id` = " . $data['post_id'] . " AND `type` = '" . $data['type'] . "'");
	if (mysqli_num_rows($query_one) > 0) {
		if ($data['type'] != 'following') {
			$query_two = mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $recipient['user_id'] . " AND `post_id` = " . $data['post_id'] . " AND `type` = '" . $data['type'] . "'");
		}
	}
	if (! isset($data['undo']) || $data['undo'] != true) {
		$query_three = mysqli_query($sql_connect, "INSERT INTO " . T_NOTIFICATIONS . " (`recipient_id`, `notifier_id`, {$group_notification_query} {$event_notification_query} `post_id`, `type`, `type2`, `text`, `url`, `time`) VALUES (" . $recipient['user_id'] . ", " . $notifier['user_id'] . ", {$group_notification_query2} {$event_notification_query2} " . $data['post_id'] . ", '" . $data['type'] . "', '" . $data['type2'] . "', '" . $data['text'] . "', '{$url}', " . time() . ")");
		if ($query_three) {
			if ($carovl['config']['email_notification'] == 1 && $recipient['email_notification'] == 1) {
				$send_mail = false;
				if ($data['type'] == 'liked_post' && $recipient['e_liked'] == 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'share_post' && $recipient['e_shared'] == 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'comment' && $recipient['e_commented'] == 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'following' && $recipient['e_followed'] == 1) {
					$send_mail = true;
				}
				if (($data['type'] == 'comment_mention' || $data['type'] == 'post_mention') && $recipient['e_mentioned'] = 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'accepted_request' && $recipient['e_accepted'] == 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'visited_profile' && $recipient['e_visited'] == 1) {
					$send_mail = true;
				}
				if ($data['type'] == 'joined_group' && $recipient['e_joined_group'] == 1) {
					$send_mail = true;
				}
				if ($send_mail == true) {
					$post_data_id = postData($data['post_id']);
					$post_data['text'] = '';
					if (! empty($post_data_id['post_text'])) {
						$post_data['text'] = substr($post_data_id['post_text'], 0, 20);
					}
					$data['notifier'] = $notifier;
					$data['url'] = seoLink($url);
					$data['post_data'] = $post_data;
					$carovl['email_notification'] = $data;
					$send_message_data = array(
						'from_email' => $carovl['config']['site_email'],
						'from_name' => $carovl['config']['site_name'],
						'to_email' => $recipient['email'],
						'to_name' => $recipient['name'],
						'subject' => 'New Notification',
						'charSet' => 'utf-8',
						'message_body' => loadPage('emails/notification-email'),
						'is_html' => true
					);
					if ($carovl['config']['smtp_or_mail'] == 'smtp') {
						$send_message_data['insert_database'] = 1;
					}
					$send = sendEmailMessage($send_message_data);
				}
			}
			return true;
		}
	}
}
// Get Blocked Users
function getBlockedUsers($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$data = array();
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = {$user_id}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = userData($fetched_data['blocked']);
	}
	return $data;
}
// Check If User Has Blocked
function isBlocked($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_BLOCKS . " WHERE (`blocker` = {$logged_user_id} AND `blocked` = {$user_id}) OR (`blocker` = {$user_id} AND `blocked` = {$logged_user_id})");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Get Posts
function getPosts($data = array('filter_by' => 'all', 'after_post_id' => 0, 'group_id' => 0, 'publisher_id' => 0, 'limit' => 5, 'event_id' => 0))
{
	global $carovl, $sql_connect;
	if (empty($data['filter_by'])) {
		$data['filter_by'] = 'all';
	}
	$subquery = " `id` > 0 ";
	if (! empty($data['after_post_id']) && is_numeric($data['after_post_id']) && $data['after_post_id'] > 0) {
		$data['after_post_id'] = secureIt($data['after_post_id']);
		$subquery = " `id` < " . $data['after_post_id'] . " AND `id` <> " . $data['after_post_id'];
	} elseif (! empty($data['before_post_id']) && is_numeric($data['before_post_id']) && $data['before_post_id'] > 0) {
		$data['before_post_id'] = secureIt($data['before_post_id']);
		$subquery = " `id` > " . $data['before_post_id'] . " AND `id` <> " . $data['before_post_id'];
	}
	if (! empty($data['publisher_id']) && is_numeric($data['publisher_id']) && $data['publisher_id'] > 0) {
		$data['publisher_id'] = secureIt($data['publisher_id']);
		$publisher = userData($data['publisher_id']);
	}
	if (! empty($data['group_id']) && is_numeric($data['group_id']) && $data['group_id'] > 0) {
		$data['group_id'] = secureIt($data['group_id']);
		$group_publisher = groupData($data['group_id']);
	}
	if (! empty($data['event_id']) && is_numeric($data['event_id']) && $data['event_id'] > 0) {
		$data['event_id'] = secureIt($data['event_id']);
		$event_publisher = eventData($data['event_id']);
	}
	$query = "SELECT `id` FROM " . T_POSTS . " WHERE {$subquery} AND `post_type` <> 'profile_picture_deleted'";
	if (isset($publisher['user_id'])) {
		$user_id = secureIt($publisher['user_id']);
		$query .= " AND (`user_id` = {$user_id} OR `recipient_id` = {$user_id}) AND `post_share` IN (0,1) AND `group_id` = 0 AND `event_id` = 0";
		switch ($data['filter_by']) {
			case 'text':
				$query .= " AND `post_text` <> '' AND `post_file` = '' ";
				break;
			case 'files':
				$query .= " AND (`post_file` LIKE '%_file%' AND `post_file` NOT LIKE '%_video%' AND `post_file` NOT LIKE '%_avatar%' AND `post_file` NOT LIKE '%_audio%' AND `post_file` NOT LIKE '%_image%')";
				break;
			case 'photos':
				$query .= " AND (`post_file` LIKE '%_image%' OR `post_file` LIKE '%_avatar%' OR `post_file` LIKE '%_cover%')";
				break;
			case 'music':
				$query .= " AND `post_file` LIKE '%_audio%'";
				break;
			case 'video':
				$query .= " AND `post_file` LIKE '%_video%'";
				break;
			case 'maps':
				$query .= " AND `post_map` <> ''";
				break;
		}
	} elseif (isset($group_publisher['id'])) {
		$group_id = secureIt($group_publisher['id']);
		$query .= " AND (`group_id` = {$group_id}) AND `user_id` IN (SELECT `user_id` FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id})";
		switch ($data['filter_by']) {
			case 'text':
				$query .= " AND `post_text` <> '' AND `post_file` = '' ";
				break;
			case 'files':
				$query .= " AND (`post_file` LIKE '%_file%' AND `post_file` NOT LIKE '%_video%' AND `post_file` NOT LIKE '%_avatar%' AND `post_file` NOT LIKE '%_audio%' AND `post_file` NOT LIKE '%_image%')";
				break;
			case 'photos':
				$query .= " AND (`post_file` LIKE '%_image%' OR `post_file` LIKE '%_avatar%' OR `post_file` LIKE '%_cover%')";
				break;
			case 'music':
				$query .= " AND `post_file` LIKE '%_audio%'";
				break;
			case 'video':
				$query .= " AND `post_file` LIKE '%_video%'";
				break;
			case 'maps':
				$query .= " AND `post_map` <> ''";
				break;
		}
	} elseif (isset($event_publisher['id'])) {
		$event_id = secureIt($event_publisher['id']);
		$query .= " AND (`event_id` = {$event_id})";
		switch ($data['filter_by']) {
			case 'text':
				$query .= " AND `post_text` <> '' AND `post_file` = '' ";
				break;
			case 'files':
				$query .= " AND (`post_file` LIKE '%_file%' AND `post_file` NOT LIKE '%_video%' AND `post_file` NOT LIKE '%_avatar%' AND `post_file` NOT LIKE '%_audio%' AND `post_file` NOT LIKE '%_image%')";
				break;
			case 'photos':
				$query .= " AND (`post_file` LIKE '%_image%' OR `post_file` LIKE '%_avatar%' OR `post_file` LIKE '%_cover%')";
				break;
			case 'music':
				$query .= " AND `post_file` LIKE '%_audio%'";
				break;
			case 'video':
				$query .= " AND `post_file` LIKE '%_video%'";
				break;
			case 'maps':
				$query .= " AND `post_map` <> ''";
				break;
		}
	} else {
		if ($carovl['logged_in'] == true) {
			$logged_user_id = secureIt($carovl['user']['user_id']);
			if ($carovl['user']['order_posts_by'] == 1) {
				$query .= "
				AND (
					`user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$logged_user_id} AND `active` = '1')
					OR `recipient_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$logged_user_id} AND `active` = '1')
					OR `user_id` IN ({$logged_user_id})
					OR `group_id` IN (SELECT `id` FROM " . T_GROUPS . " WHERE `user_id` = {$logged_user_id})
					OR `group_id` IN (SELECT `group_id` FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$logged_user_id} AND `user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$logged_user_id} AND `active` = '1'))
					OR `event_id` IN (SELECT `event_id` FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$logged_user_id} AND `action` = 'going')
				)";
			}
			// Adding "AND `group_id` = 0";
			$query .= " AND `post_share` NOT IN (1) AND `group_id` = 0";
			switch ($data['filter_by']) {
				case 'text':
					$query .= " AND `post_text` <> '' AND `post_file` = '' ";
					break;
				case 'files':
					$query .= " AND (`post_file` LIKE '%_file%' AND `post_file` NOT LIKE '%_video%' AND `post_file` NOT LIKE '%_avatar%' AND `post_file` NOT LIKE '%_audio%' AND `post_file` NOT LIKE '%_image%')";
					break;
				case 'photos':
					$query .= " AND (`post_file` LIKE '%_image%' OR `post_file` LIKE '%_avatar%' OR `post_file` LIKE '%_cover%')";
					break;
				case 'music':
					$query .= " AND `post_file` LIKE '%_audio%'";
					break;
				case 'video':
					$query .= " AND `post_file` LIKE '%_video%'";
					break;
				case 'maps':
					$query .= " AND `post_map` <> ''";
					break;
			}
		}
	}
	if (empty($data['limit']) || ! is_numeric($data['limit']) || $data['limit'] < 1) {
		$data['limit'] = 5;
	}
	$limit = secureIt($data['limit']);
	if (isset($data['order'])) {
		$query .= " ORDER BY `id` " . secureIt($data['order']) . " LIMIT {$limit}";
	} else {
		$query .= " ORDER BY `id` DESC LIMIT {$limit}";
	}
	$data = array();
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$post = postData($fetched_data['id']);
		if (is_array($post)) {
			$data[] = $post;
		}
	}
	return $data;
}
// Get Post Data
function postData($post_id, $placement = '', $limited = '')
{
	global $carovl, $sql_connect, $cache;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	$data = array();
	$post_id = secureIt($post_id);
	$query = "SELECT * FROM " . T_POSTS . " WHERE `id` = {$post_id}";
	$hashed_post_id = md5($post_id);
	if ($carovl['config']['cache_system'] == 1) {
		$fetched_data = $cache->read($hashed_post_id . '_P_Data.tmp');
		if (empty($fetched_data)) {
			$sql = mysqli_query($sql_connect, $query);
			$fetched_data = mysqli_fetch_assoc($sql);
			$cache->write($hashed_post_id . '_P_Data.tmp', $fetched_data);
		}
	} else {
		$sql = mysqli_query($sql_connect, $query);
		$fetched_data = mysqli_fetch_assoc($sql);
	}
	if (empty($fetched_data['id'])) {
		return false;
	}
	$fetched_data['publisher'] = userData($fetched_data['user_id']);
	if ($fetched_data['id'] == $fetched_data['post_id']) {
		$story = $fetched_data;
	} else {
		$query_two = mysqli_query($sql_connect, "SELECT * FROM " . T_POSTS . " WHERE `id` = " . $fetched_data['post_id']);
		if (mysqli_num_rows($query_two) != 1) {
			return false;
		}
		$sql_fetch = mysqli_fetch_assoc($query_two);
		$story = $sql_fetch;
		$story['publisher'] = userData($story['user_id']);
	}
	$story['limit_comments'] = 5;
	$story['limited_comments'] = true;
	if ($limited == 'not_limited') {
		$story['limit_comments'] = 100000;
		$story['limited_comments'] = false;
	}
	$story['is_group_post'] = false;
	$story['group_recipient_exist'] = false;
	$story['group_admin'] = false;
	if ($placement != 'admin') {
		if (! empty($story['group_id'])) {
			if ($carovl['config']['groups'] == 0) {
				return false;
			}
			$story['group_recipient_exist'] = true;
			$story['group_recipient'] = groupData($story['group_id']);
			if ($story['group_recipient']['privacy'] == 1) {
				if ($carovl['logged_in'] == true) {
					if ($story['publisher']['user_id'] != $carovl['user']['user_id']) {
						if (isGroupOwner($story['group_id']) === false) {
							if (isGroupJoined($story['group_id']) === false) {
								return false;
							}
						}
					}
				} else {
					return false;
				}
			}
			if (isGroupOwner($story['group_id']) === false) {
				$story['is_group_post'] = true;
			} else {
				$story['group_admin'] = true;
			}
		}
		if ($story['post_privacy'] == 2) {
			if ($carovl['logged_in'] == true) {
				if ($story['publisher']['user_id'] != $carovl['user']['user_id']) {
					if (isFollowing($story['publisher']['user_id'], $carovl['user']['user_id']) === false && empty($story['group_id'])) {
						return false;
					}
				}
			} else {
				return false;
			}
		}
		if ($story['post_privacy'] == 3) {
			if ($carovl['logged_in'] == true) {
				if ($carovl['user']['user_id'] != $story['publisher']['user_id']) {
					return false;
				}
			} else {
				return false;
			}
		}
	}
	$story['original_text'] = editMarkup($story['post_text']);
	$story['original_text'] = str_replace('<br>', "\n", $story['original_text']);
	$story['post_text'] = getEmoticons($story['post_text']);
	$story['post_text'] = getMarkup($story['post_text']);
	if (isset($story['original_text']) && ! empty($story['original_text']) && $carovl['config']['use_seo_friendly'] == 1) {
		$story['url'] = seoLink('index.php?page=post&id=' . $story['id']) . '_' . slugPost($story['original_text']);
	} else {
		$story['url'] = seoLink('index.php?page=post&id=' . $story['id']);
	}
	$story['via_type'] = '';
	if ($story['id'] != $fetched_data['id'] && $story['user_id'] != $fetched_data['user_id']) {
		$story['via_type'] = 'share';
		$story['via'] = $fetched_data['publisher'];
	}
	$story['recipient_exists'] = false;
	$story['recipient'] = '';
	if ($story['recipient_id'] > 0) {
		$story['recipient_exists'] = true;
		$story['recipient'] = userData($story['recipient_id']);
	}
	$story['admin'] = false;
	if ($carovl['logged_in'] == true) {
		if ($story['publisher']['user_id'] == $carovl['user']['user_id']) {
			$story['admin'] = true;
		}
		if ($story['recipient_exists'] == true) {
			if ($story['recipient']['user_id'] == $carovl['user']['user_id']) {
				$story['admin'] = true;
			}
		}
	}
	$story['is_post_saved'] = false;
	$story['is_post_reported'] = false;
	$story['is_post_liked'] = false;
	$story['post_comments'] = 0;
	$story['post_shares'] = 0;
	$story['post_likes'] = 0;
	$story['post_link_image'] = getMedia($story['post_link_image']);
	$story['get_post_comments'] = getPostComments($story['id'], $story['limit_comments']);
	$story['photo_album'] = array();
	if (! empty($story['album_name'])) {
		$story['photo_album'] = getAlbumPhotos($story['id']);
	}
	if ($story['multi_image'] == 1) {
		$story['photo_multi'] = getAlbumPhotos($story['id']);
	}
	if ($story['article_id'] > 0) {
		$story['article'] = articleData($story['article_id']);
	}
	if ($story['event_id'] > 0) {
		$story['event'] = eventData($story['event_id']);
	}
	if ($story['product_id'] > 0) {
		$story['product'] = productData($story['product_id']);
	}
	if ($carovl['logged_in'] == true) {
		$story['post_comments'] = countPostComments($story['id']);
		$story['post_shares'] = countPostShares($story['id']);
		$story['post_likes'] = countPostLikes($story['id']);
		$story['is_post_liked'] = (isPostLiked($story['id'], $carovl['user']['user_id']) === true) ? true : false;
		$story['is_post_saved'] = (isPostSaved($story['id'], $carovl['user']['user_id']) === true) ? true : false;
		$story['is_post_reported'] = (isPostReported($story['id'], $carovl['user']['user_id']) === true) ? true : false;
		if (isBlocked($story['user_id']) || isBlocked($story['recipient_id'])) {
			if (empty($story['group_id'])) {
				return false;
			}
		}
	}
	return $story;
}
function editMarkup($text, $link = true, $hashtag = true, $mention = true)
{
	if ($link == true) {
		$link_search = '/\[a\](.*?)\[\/a\]/i';
		if (preg_match_all($link_search, $text, $matches)) {
			foreach ($matches[1] as $match) {
				$match_decode = urldecode($match);
				$match_url = $match_decode;
				if (! preg_match("/http(|s)\:\/\//", $match_decode)) {
					$match_url = 'http://' . $match_url;
				}
				$text = str_replace('[a]' . $match . '[/a]', $match_decode, $text);
			}
		}
	}
	if ($hashtag == true) {
		$hashtag_regex = '/(#\[([0-9]+)\])/i';
		preg_match_all($hashtag_regex, $text, $matches);
		$match_i = 0;
		foreach ($matches[1] as $match) {
			$hashtag = $matches[1][$match_i];
			$hashkey = $matches[2][$match_i];
			$hashdata = getHashtag($hashkey);
			if (is_array($hashdata)) {
				$hashlink = '#' . $hashdata['tag'];
				$text = str_replace($hashtag, $hashlink, $text);
			}
			$match_i++;
		}
	}
	if ($mention == true) {
		$mention_regex = '/@\[([0-9]+)\]/i';
		if (preg_match_all($mention_regex, $text, $matches)) {
			foreach ($matches[1] as $match) {
				$match = secureIt($match);
				$match_user = userData($match);
				$match_search = '@[' . $match . ']';
				$match_replace = '@' . $match_user['username'];
				if (isset($match_user['user_id'])) {
					$text = str_replace($match_search, $match_replace, $text);
				}
			}
		}
	}
	return $text;
}
// Get Emoticons
function getEmoticons($string = '')
{
	global $emoticons;
	foreach ($emoticons as $code => $name) {
		$code = $code;
		$name = '<i class="twa-lg twa twa-' . $name . '"></i>';
		$string = str_replace($code, $name, $string);
	}
	return $string;
}
function getMarkup($text, $link = true, $hashtag = true, $mention = true)
{
	if ($link == true) {
		$link_search = '/\[a\](.*?)\[\/a\]/i';
		if (preg_match_all($link_search, $text, $matches)) {
			foreach ($matches[1] as $match) {
				$match_decode = urldecode($match);
				$match_decode_url = $match_decode;
				$count_url = mb_strlen($match_decode);
				if ($count_url > 50) {
					$match_decode_url = mb_substr($match_decode_url, 0, 30) . '....' . mb_substr($match_decode_url, 30, 20);
				}
				$match_url = $match_decode;
				if (! preg_match("/http(|s)\:\/\//", $match_decode)) {
					$match_url = 'http://' . $match_url;
				}
				$text = str_replace('[a]' . $match . '[/a]', '<a href="' . strip_tags($match_url) . '" target="_blank" class="hash" rel="nofollow">' . $match_decode_url . '</a>', $text);
			}
		}
	}
	if ($hashtag == true) {
		$hashtag_regex = '/(#\[([0-9]+)\])/i';
		preg_match_all($hashtag_regex, $text, $matches);
		$match_i = 0;
		foreach ($matches[1] as $match) {
			$hashtag = $matches[1][$match_i];
			$hashkey = $matches[2][$match_i];
			$hashdata = getHashtag($hashkey);
			if (is_array($hashdata)) {
				$hashlink = '<a href="' . seoLink('index.php?page=hashtag&hash=' . $hashdata['tag']) . '" data-redirect="?page=hashtag&hash=' . $hashdata['tag'] . '" class="hash">#' . $hashdata['tag'] . '</a>';
				$text = str_replace($hashtag, $hashlink, $text);
			}
			$match_i++;
		}
	}
	if ($mention == true) {
		$mention_regex = '/@\[([0-9]+)\]/i';
		if (preg_match_all($mention_regex, $text, $matches)) {
			foreach ($matches[1] as $match) {
				$match = secureIt($match);
				$match_user = userData($match);
				$match_search = '@[' . $match . ']';
				$match_replace = '<span class="user-preview user-mention" data-id="' . $match_user['id'] . '" data-type="' . $match_user['type'] . '">@<a href="' . seoLink('index.php?page=timeline&u=' . $match_user['username']) . '" class="hash" data-redirect="?page=timeline&u=' . $match_user['username'] . '">' . $match_user['username'] . '</a></span>';
				if (isset($match_user['user_id'])) {
					$text = str_replace($match_search, $match_replace, $text);
				}
			}
		}
	}
	return $text;
}
// Get Post Comments
function getPostComments($post_id = 0, $limit = 5)
{
	global $carovl, $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($post_id);
	$data = array();
	$query = "SELECT `id` FROM " . T_COMMENTS . " WHERE `post_id` = {$post_id} AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}') ORDER BY `id` ASC";
	if (($comments_num = countPostComments($post_id)) > $limit) {
		$query .= " LIMIT " . ($comments_num - $limit) . ", {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = commentData($fetched_data['id']);
	}
	return $data;
}
// Get Post Likes
function getPostLikes($post_id = 0)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	$post_id = secureIt($post_id);
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_LIKES . " WHERE `post_id` = {$post_id}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
// Get Comment Data
function commentData($comment_id = 0)
{
	global $carovl, $sql_connect;
	if (empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 0) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_COMMENTS . " WHERE `id` = {$comment_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	$fetched_data['publisher'] = userData($fetched_data['user_id']);
	$fetched_data['url'] = seoLink('index.php?page=timeline&u=' . $fetched_data['publisher']['username']);
	$fetched_data['original_text'] = editMarkup($fetched_data['text']);
	$fetched_data['original_text'] = str_replace('<br>', "\n", $fetched_data['original_text']);
	$fetched_data['text'] = getMarkup($fetched_data['text']);
	$fetched_data['text'] = getEmoticons($fetched_data['text']);
	$fetched_data['owner'] = false;
	$fetched_data['post_owner'] = false;
	$fetched_data['comment_likes'] = countCommentLikes($fetched_data['id']);
	$fetched_data['is_comment_liked'] = false;
	if ($carovl['logged_in'] == true) {
		$fetched_data['owner'] = ($fetched_data['publisher']['user_id'] == $carovl['user']['user_id']) ? true : false;
		$fetched_data['post_owner'] = (isPostOwner($fetched_data['post_id'], $carovl['user']['user_id'])) ? true : false;
		$fetched_data['is_comment_liked'] = (isCommentLiked($fetched_data['id'], $carovl['user']['user_id'])) ? true : false;
	}
	return $fetched_data;
}
// Count Comment Likes
function countCommentLikes($comment_id)
{
	global $sql_connect;
	if (empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 1) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `likes` FROM " . T_COMMENT_LIKES . " WHERE `comment_id` = {$comment_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['likes'];
	}
}
// Check if User is Post Owner
function isPostOwner($post_id, $user_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_POSTS . " WHERE `id` = {$post_id} AND `user_id` = {$user_id}");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Check if Comment is Liked
function isCommentLiked($comment_id, $user_id)
{
	global $sql_connect;
	if (empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 1) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_COMMENT_LIKES . " WHERE `comment_id` = {$comment_id} AND `user_id` = {$user_id}");
	if (mysqli_num_rows($query) >= 1) {
		return true;
	}
}
// Count Post Comments
function countPostComments($post_id = '')
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 0) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `comments` FROM " . T_COMMENTS . " WHERE `post_id` = {$post_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['comments'];
}
// Count Shared Post
function countPostShares($post_id = 0)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `shares` FROM " . T_POSTS . " WHERE `post_id` = {$post_id} AND `post_share` = 1");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['shares'];
	}
}
function countPostNotes($post_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$responses = countPostLikes($post_id) + countPostComments($post_id) + countPostShares($post_id);
	return $responses;
}
// Count Post Likes
function countPostLikes($post_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `likes` FROM " . T_LIKES . " WHERE `post_id` = {$post_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['likes'];
	}
}
// Check If Post Shared
function isPostShared($post_id, $user_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id} AND `post_share` = '1'");
	if (mysqli_num_rows($query) >= 1) {
		return true;
	}
}
// Check If Post Liked
function isPostLiked($post_id, $user_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_LIKES . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
	if (mysqli_num_rows($query) >= 1) {
		return true;
	}
}
// Check If Post Saved
function isPostSaved($post_id, $user_id)
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_SAVED_POSTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
	if (mysqli_num_rows($query) >= 1) {
		return true;
	} else {
		return false;
	}
}
// Check If Post Reported
function isPostReported($post_id = '', $user_id = '')
{
	global $sql_connect;
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_REPORTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
	if (mysqli_num_rows($query) >= 1) {
		return true;
	}
}
// Check If Following
function isFollowing($following_id, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($following_id) || ! is_numeric($following_id) || $following_id < 0) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		$user_id = $carovl['user']['user_id'];
	}
	$following_id = secureIt($following_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_FOLLOWERS . " WHERE `following_id` = {$following_id} AND `follower_id` = {$user_id} AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Generate Hash For Session
function createSession()
{
	$hash = sha1(rand(1111, 9999));
	if (! empty($_SESSION['hash_id'])) {
		$_SESSION['hash_id'] = $_SESSION['hash_id'];
		return $_SESSION['hash_id'];
	}
	$_SESSION['hash_id'] = $hash;
	return $hash;
}
// Check the Session
function checkSession($hash = '')
{
	if (! isset($_SESSION['hash_id']) || empty($_SESSION['hash_id'])) {
		return false;
	}
	if (empty($hash)) {
		return false;
	}
	if ($hash == $_SESSION['hash_id']) {
		return true;
	}
	return false;
}
function slugPost($string)
{
	$slug = urlSlug($string, array(
		'delimiter' => '-',
		'limit' => 80,
		'lowercase' => true,
		'replacements' => array(
			'/\b(an)\b/i' => 'a',
			'/\b(example)\b/i' => 'Example'
		)
	));
	return $slug . '.html';
}
// Upload File to Server
function shareFile($data = array(), $type = 0)
{
	global $carovl, $sql_connect;
	$allowed = '';
	// Files Folder
	if (! file_exists('uploads/files/' . date('Y'))) {
		@mkdir('uploads/files/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/files/' . date('Y') . '/' . date('m'))) {
		@mkdir('uploads/files/' . date('Y') . '/' . date('m'), 0777, true);
	}
	// Photos Folder
	if (! file_exists('uploads/photos/' . date('Y'))) {
		@mkdir('uploads/photos/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/photos/' . date('Y') . '/' . date('m'))) {
		@mkdir('uploads/photos/' . date('Y') . '/' . date('m'), 0777, true);
	}
	// Videos Folder
	if (! file_exists('uploads/videos/' . date('Y'))) {
		@mkdir('uploads/videos/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/videos/' . date('Y') . '/' . date('m'))) {
		@mkdir('uploads/videos/' . date('Y') . '/' . date('m'), 0777, true);
	}
	// Audios Folder
	if (! file_exists('uploads/audios/' . date('Y'))) {
		@mkdir('uploads/audios/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/audios/' . date('Y') . '/' . date('m'))) {
		@mkdir('uploads/audios/' . date('Y') . '/' . date('m'), 0777, true);
	}
	if (isset($data['file']) && ! empty($data['file'])) {
		$data['file'] = secureIt($data['file']);
	}
	if (isset($data['name']) && ! empty($data['name'])) {
		$data['name'] = secureIt($data['name']);
	}
	if (empty($data)) {
		return false;
	}
	if ($carovl['config']['file_sharing'] == 1) {
		if (isset($data['types'])) {
			$allowed = $data['types'];
		} else {
			$allowed = $carovl['config']['allowed_extension'];
		}
	} else {
		$allowed = 'jpg,png,gif,jpeg';
	}
	$new_string = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
	$extension_allowed = explode(',', $allowed);
	$file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
	if (! in_array($file_extension, $extension_allowed)) {
		return false;
	}
	if ($data['size'] > $carovl['config']['max_upload']) {
		return false;
	}
	if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
		$folder = 'photos';
		$filetype = 'image';
	} elseif ($file_extension == 'mp4' || $file_extension == 'mov' || $file_extension == 'webm' || $file_extension == 'flv') {
		$folder = 'videos';
		$filetype = 'video';
	} elseif ($file_extension == 'mp3' || $file_extension == 'wav') {
		$folder = 'audios';
		$filetype = 'audio';
	} else {
		$folder = 'files';
		$filetype = 'file';
	}
	if (empty($folder) || empty($filetype)) {
		return false;
	}
	$ar = array(
		'text/plain',
		'video/mp4',
		'video/mov',
		'video/mpeg',
		'video/flv',
		'video/avi',
		'video/webm',
		'audio/wav',
		'audio/mpeg',
		'video/quicktime',
		'audio/mp3',
		'image/png',
		'image/jpeg',
		'image/gif',
		'application/pdf',
		'application/msword',
		'application/zip',
		'application/x-rar-compressed',
		'text/pdf',
		'application/x-pointplus',
		'text/css',
		'application/octet-stream'
	);
	if (! in_array($data['type'], $ar)) {
		return false;
	}
	$dir = "uploads/{$folder}/" . date('Y') . '/' . date('m');
	$filename = $dir . '/' . generateKey() . '_' . date('d') . '_' . md5(microtime()) . "_{$filetype}.{$file_extension}";
	$second_file = pathinfo($filename, PATHINFO_EXTENSION);
	if (move_uploaded_file($data['file'], $filename)) {
		if ($second_file == 'jpg' || $second_file == 'jpeg' || $second_file == 'png' || $second_file == 'gif') {
			if ($type == 1) {
				@compressImage($filename, $filename, 80);
				$explode = @end(explode('.', $filename));
				$explode2 = @explode('.', $filename);
				$last_file = $explode2[0] . '_small.' . $explode;
				if (resizeCropImage(645, 430, $filename, $last_file, 60)) {
					if ($carovl['config']['amazone_s3'] == 1 && ! empty($last_file)) {
						//$upload_s3 = uploadToS3($last_file);
					}
				}
			} else {
				if ($second_file != 'gif') {
					@compressImage($filename, $filename, 80);
				}
			}
		}
		if (! empty($data['crop'])) {
			$crop_image = resizeCropImage($data['crop']['width'], $data['crop']['height'], $filename, $filename, 100);
		}
		if ($carovl['config']['amazone_s3'] == 1 && ! empty($filename)) {
			// $upload_to_s3 = uploadToS3($filename);
		}
		$last_data = array();
		$last_data['filename'] = $filename;
		$last_data['name'] = $data['name'];
		return $last_data;
	}
}
// Import Image From URL
function importImageFromUrl($media)
{
	global $carovl;
	$default_avatar = getDefaultAvatar($carovl['user']['username']);
	if (empty($media)) {
		return $default_avatar;
	}
	if (! file_exists('uploads/photos/' . date('Y'))) {
		mkdir('uploads/photos/' . date('Y'), 0777, true);
	}
	if (! file_exists('uploads/photos/' . date('Y') . '/' . date('m'))) {
		mkdir('uploads/photos/' . date('Y') . '/' . date('m'), 0777, true);
	}
	$extension = 0;
	if (empty($extension)) {
		$extension = '.jpg';
	}
	$dir = 'uploads/photos/' . date('Y') . '/' . date('m');
	$file_dir = $dir . '/' . generateKey() . '_url_image' . $extension;
	$arrContextOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false
		)
	);
	$fileget = file_get_contents($media);
	if (! empty($fileget)) {
		$import_image = @file_put_contents($file_dir, $fileget);
	}
	if (file_exists($file_dir)) {
		//$upload_to_s3 = uploadToS3($file_dir);
		return $file_dir;
	} else {
		return $default_avatar;
	}
}
// Register For Image > 1
function registerAlbumMedia($id, $media)
{
	global $carovl, $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	if (empty($media)) {
		return false;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_ALBUMS_MEDIA . " (`post_id`, `image`) VALUES ({$id}, '{$media}')");
	if ($query) {
		return true;
	}
}
// Get Multi Images
function getAlbumPhotos($post_id)
{
	global $carovl, $sql_connect;
	$data = array();
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT `id`, `image`, `post_id` FROM " . T_ALBUMS_MEDIA . " WHERE `post_id` = {$post_id} ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$explode = @end(explode('.', $fetched_data['image']));
		$explode2 = @explode('.', $fetched_data['image']);
		$fetched_data['image_org'] = $explode2[0] . '_small.' . $explode;
		$fetched_data['image'] = getMedia($fetched_data['image']);
		$data[] = $fetched_data;
	}
	return $data;
}
// Display Uploaded File
function displaySharedFile($media, $placement = '')
{
	global $carovl, $sql_connect;
	$carovl['media']['filename'] = getMedia($media['filename']);
	$carovl['media']['name'] = secureIt($media['name']);
	$carovl['media']['type'] = $media['type'];
	$carovl['media']['story_id'] = @$media['story_id'];
	$icon_size = 'fa-3x';
	if ($placement == 'conversations') {
		$icon_size = '';
	}
	if (! empty($carovl['media']['filename'])) {
		$file_extension = pathinfo($carovl['media']['filename'], PATHINFO_EXTENSION);
		$file = '';
		$media_file = '';
		$start_link = '<a href="' . $carovl['media']['filename'] . '">';
		$end_link = '</a>';
		$file_extension = strtolower($file_extension);

		if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
			if ($placement != 'conversations' && $media['type'] != 'message') {
				$media_file .= '<img src="' . $carovl['media']['filename'] . '" alt="' . $media['alt'] . '" onclick="openImage(' . $media['story_id'] . ');" class="img-fluid pointer">';
			} else {
				$media_file .= '<a href="' . $carovl['media']['filename'] . '" target="_blank"><img src="' . $carovl['media']['filename'] . '" alt="' . $media['alt'] . '" class="img-fluid pointer"></a>';
			}
		}
		if ($file_extension == 'mp3' || $file_extension == 'wav') {
			$media_file .= loadPage('players/audio');
		}
		if ($file_extension == 'txt') {
			$file .= '<div class="text-center shared-file"><i class="fa ' . $icon_size . ' fa-file-text-o"></i><h4 class="file-name mt-3">' . $carovl['media']['name'] . '</h4></div>';
		}
		if ($file_extension == 'zip' || $file_extension == 'rar' || $file_extension == 'tar') {
			$file .= '<div class="text-center shared-file"><i class="fa ' . $icon_size . ' fa-file-archive-o"></i><h4 class="file-name mt-3">' . $carovl['media']['name'] . '</h4></div>';
		}
		if ($file_extension == 'pdf') {
			$file .= '<div class="text-center shared-file"><i class="fa ' . $icon_size . ' fa-file-pdf-o"></i><h4 class="file-name mt-3">' . $carovl['media']['name'] . '</h4></div>';
		}
		if ($file_extension == 'doc' || $file_extension == 'docx') {
			$file .= '<div class="text-center shared-file"><i class="fa ' . $icon_size . ' fa-file-word-o"></i><h4 class="file-name mt-3">' . $carovl['media']['name'] . '</h4></div>';
		}
		if (empty($file)) {
			$file .= '<div class="text-center shared-file"><i class="fa ' . $icon_size . ' fa-file-o"></i><h4 class="file-name mt-3">' . $carovl['media']['name'] . '</h4></div>';
		}
		if ($file_extension == 'mp4' || $file_extension == 'mkv' || $file_extension == 'avi' || $file_extension == 'webm' || $file_extension == 'mov') {
			$media_file .= loadPage('players/video');
		}
		$last_file = '';
		if (isset($media_file) && ! empty($media_file)) {
			$last_file = $media_file;
		} else {
			$last_file = $start_link . $file . $end_link;
		}
		return $last_file;
	}
}
function isValidPasswordResetToken($string)
{
	global $sql_connect;
	$string_exp = explode('_', $string);
	$user_id = secureIt($string_exp[0]);
	$password = secureIt($string_exp[1]);
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (empty($password)) {
		return false;
	}
	$query = mysqli_query($sql_connect, " SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `user_id` = {$user_id} AND `password` = '{$password}' AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
// Reset User Password
function resetPassword($user_id, $password)
{
	global $sql_connect;
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if (empty($password)) {
		return false;
	}
	$user_id = secureIt($user_id);
	$password = md5($password);
	$query = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `password` = '{$password}' WHERE `user_id` = {$user_id}");
	if ($query) {
		return true;
	} else {
		return false;
	}
}
// Register Post Like
function registerLike($post_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = getUserIdFromPostId($post_id);
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$post = postData($post_id);
	$text = '';
	$type2 = '';
	if (empty($user_id)) {
		return false;
	}
	if (isset($post['post_text']) && ! empty($post['post_text'])) {
		$text = substr($post['post_text'], 0, 10) . '...';
	}
	if (isset($post['post_file']) && ! empty($post['post_file'])) {
		if (strpos($post['post_file'], '_image') !== false) {
			$type2 = 'post_image';
		} elseif (strpos($post['post_file'], '_video') !== false) {
			$type2 = 'post_video';
		} elseif (strpos($post['post_file'], '_audio') !== false) {
			$type2 = 'post_audio';
		} elseif (strpos($post['post_file'], '_avatar') !== false) {
			$type2 = 'post_avatar';
		} elseif (strpos($post['post_file'], '_cover') !== false) {
			$type2 = 'post_cover';
		} else {
			$type2 = 'post_file';
		}
	}
	if (isPostLiked($post_id, $carovl['user']['user_id']) === true) {
		$query = "DELETE FROM " . T_LIKES . " WHERE `post_id` = {$post_id} AND `user_id` = {$logged_user_id}";
		mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `post_id` = {$post_id} AND `recipient_id` = {$user_id} AND `type` = 'liked_post'");
		$delete_activity = deleteActivity($post_id, $logged_user_id, 'liked_post');
		$sql = mysqli_query($sql_connect, $query);
		if ($sql) {
			return 'unliked';
		}
	} else {
		$query = mysqli_query($sql_connect, "INSERT INTO " . T_LIKES . " (`user_id`, `post_id`) VALUES ({$logged_user_id}, {$post_id})");
		if ($query) {
			if ($type2 != 'post_avatar') {
				$activity_data = array(
					'post_id' => $post_id,
					'user_id' => $logged_user_id,
					'post_user_id' => $user_id,
					'activity_type' => 'liked_post'
				);
				$register_activity = registerActivity($activity_data);
			}
			$notification_data = array(
				'recipient_id' => $user_id,
				'post_id' => $post_id,
				'type' => 'liked_post',
				'text' => $text,
				'type2' => $type2,
				'url' => 'index.php?page=post&id=' . $post_id
			);
			registerNotification($notification_data);
			return 'liked';
		}
	}
}
// Delete User Activity
function deleteActivity($post_id, $user_id, $activity_type)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_ACTIVITIES . " WHERE `user_id` = '{$user_id}' AND `post_id` = '{$post_id}' AND `activity_type` = '{$activity_type}'");
	return ($query) ? true : false;
}
// Register Activity
function registerActivity($data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($data['post_id']) || ! is_numeric($data['post_id']) || $data['post_id'] < 1) {
		return false;
	}
	if (empty($data['activity_type'])) {
		return false;
	}
	$post_id = secureIt($data['post_id']);
	$user_id = secureIt($data['user_id']);
	$post_user_id = secureIt($data['post_user_id']);
	$activity_type = secureIt($data['activity_type']);
	$time = time();
	if ($user_id == $post_user_id) {
		return false;
	}
	$query = "INSERT INTO " . T_ACTIVITIES . " (`user_id`, `post_id`, `activity_type`, `time`) VALUES ('{$user_id}', '{$post_id}', '{$activity_type}', '{$time}')";
	if (isActivity($post_id, $user_id, $activity_type) === true) {
		$query_delete = mysqli_query($sql_connect, "DELETE FROM " . T_ACTIVITIES . " WHERE `user_id` = '{$user_id}' AND `post_id` = '{$post_id}'");
		if ($query_delete) {
			$query_one = mysqli_query($sql_connect, $query);
		}
	} else {
		$query_one = mysqli_query($sql_connect, $query);
	}
	if ($query_one) {
		return true;
	}
}
function isActivity($post_id, $user_id, $activity_type)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ACTIVITIES . " WHERE `user_id` = '{$user_id}' AND `post_id` = '{$post_id}' AND `activity_type` = '{$activity_type}'");
	return (mysqli_num_rows($query) > 0) ? true : false;
}
function getFollowingSuggestions($limit, $query)
{
	global $carovl, $sql_connect;
	$data = array();
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($query)) {
		return false;
	}
	$query_one = " WHERE ((`username` LIKE '%" . secureIt($query) . "%') OR CONCAT (`first_name`, ' ', `last_name`) LIKE '%" . secureIt($query) . "%')";
	$user_id = secureIt($carovl['user']['user_id']);
	$query_two = "SELECT `user_id` FROM " . T_USERS;
	$query_two .= $query_one;
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$query_two .= " AND (`user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}') AND (`user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `following_id` <> {$user_id} AND `active` = '1') OR `user_id` IN (SELECT `follower_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` <> {$user_id} AND `following_id` = {$user_id} AND `active` = '1'))) AND `active` = '1'";
	$query_two .= " LIMIT {$limit}";
	$sql = mysqli_query($sql_connect, $query_two);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$user = userData($fetched_data['user_id']);
		$html_fi['username'] = $user['username'];
		//$html_fi['label'] = $user['name'];
		$html_fi['img'] = $user['avatar'];
		$data[] = $html_fi;
	}
	if (empty($data)) {
		$sql = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_USERS . " {$query_one} AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}') LIMIT {$limit}");
		while ($fetched_data = mysqli_fetch_assoc($sql)) {
			$user = userData($fetched_data['user_id']);
			$html_fi['username'] = $user['username'];
			//$html_fi['label'] = $user['name'];
			$html_fi['img'] = $user['avatar'];
			$data[] = $html_fi;
		}
	}
	return $data;
}
// Count Notifications
function countNotifications($data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($data['account_id']) || $data['account_id'] == 0) {
		$data['account_id'] = secureIt($carovl['user']['user_id']);
		$account = $carovl['user'];
	}
	if (! is_numeric($data['account_id']) || $data['account_id'] < 1) {
		return false;
	}
	if ($data['account_id'] != $carovl['user']['user_id']) {
		$data['account_id'] = secureIt($data['account_id']);
		$account = userData($data['account_id']);
	}
	$query = "SELECT COUNT(`id`) AS `notifications` FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $account['user_id'];
	if (isset($data['unread']) && $data['unread'] == true) {
		$query .= " AND `seen` = 0";
	}
	if (isset($data['remove_notification']) && ! empty($data['remove_notification'])) {
		foreach ($data['remove_notification'] as $key => $remove_notification) {
			$query .= " AND `type` <> '{$remove_notification}'";
		}
	}
	$query .= " ORDER BY `id` DESC";
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['notifications'];
}
// Count Messages
function countMessages($data = array(), $type = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($data['user_id']) || $data['user_id'] == 0) {
		$data['user_id'] = $carovl['user']['user_id'];
	}
	if (! is_numeric($data['user_id']) || $data['user_id'] < 1) {
		return false;
	}
	$data['user_id'] = secureIt($data['user_id']);
	if ($type == 'interval') {
		$account = $carovl['user'];
	} else {
		$account = userData($data['user_id']);
	}
	if (empty($account['user_id'])) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	if (isset($data['user_id']) && is_numeric($data['user_id']) && $data['user_id'] > 0) {
		$user_id = secureIt($data['user_id']);
		if (isset($data['new']) && $data['new'] == true) {
			$query = "SELECT COUNT(`id`) AS `messages` FROM " . T_MESSAGES . " WHERE `to_id` = {$logged_user_id} AND (`from_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `from_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}'))";
			if ($carovl['user']['user_id'] != $user_id) {
				$query .= " AND `from_id` = {$user_id}";
			}
		} else {
			$query = "SELECT COUNT(`id`) AS `messages` FROM " . T_MESSAGES . " WHERE ((`from_id` = {$user_id} AND `to_id` = {$logged_user_id} AND `deleted_two` = '0') OR (`from_id` = {$logged_user_id} AND `to_id` = {$user_id} AND `deleted_one` = '0') AND (`from_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `from_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}')))";
		}
	} else {
		$query = "SELECT COUNT(`id`) AS `messages` FROM " . T_MESSAGES . " WHERE `to_id` = {$logged_user_id} AND (`from_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `from_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}'))";
	}
	if (isset($data['new']) && $data['new'] == true) {
		$query .= " AND `seen` = 0";
	}
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['messages'];
}
// Count Follow Requests
function countFollowRequests($data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	if (empty($data['account_id']) || $data['account_id'] == 0) {
		$data['account_id'] = $user_id;
		$account = $carovl['user'];
	}
	if (! is_numeric($data['account_id']) || $data['account_id'] < 1) {
		return false;
	}
	if ($data['account_id'] != $user_id) {
		$data['account_id'] = secureIt($data['account_id']);
		$account = userData($data['account_id']);
	}
	$query = "SELECT COUNT(`id`) AS `follow_requests` FROM " . T_FOLLOWERS . " WHERE `active` = '0' AND `following_id` = " . $account['user_id'] . " AND `follower_id` IN (SELECT `user_id` FROM " . T_USERS . " WHERE `active` = '1')";
	if (isset($data['unread']) && $data['unread'] == true) {
		$query .= " AND `seen` = 0";
	}
	$query .= "ORDER BY `id` DESC";
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['follow_requests'];
}
// Get User Messages
function getMessagesUser($user_id, $q = '', $limit = 50, $new = false, $update = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (! isset($user_id)) {
		$user_id = $carovl['user']['user_id'];
	}
	$data = array();
	if (isset($q) AND ! empty($q)) {
		$query = "SELECT `user_id` AS `conversation_user_id` FROM " . T_USERS . " WHERE (`user_id` IN (SELECT `from_id` FROM " . T_MESSAGES . " WHERE `to_id` = {$user_id} AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}') AND `active` = '1'";
		if (isset($new) && $new == true) {
			$query .= " AND `seen` = 0";
		}
		$query .= " ORDER BY `user_id` DESC)";
		if (! isset($new) || $new == false) {
			$query .= " OR `user_id` IN (SELECT `to_id` FROM " . T_MESSAGES . " WHERE `from_id` = {$user_id} ORDER BY `id` DESC)";
		}
		$query .= ") AND ((`username` LIKE '%{$q}%') OR CONCAT(`first_name`, ' ', `last_name`) LIKE '%{$q}%')";
	} else {
		$query = "SELECT `conversation_user_id` FROM " . T_USER_CHATS . " WHERE `user_id` = '{$user_id}' AND (`conversation_user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `conversation_user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}')) ORDER BY `time` DESC";
	}
	$query .= " LIMIT {$limit}";
	$sql = mysqli_query($sql_connect, $query);
	if (mysqli_num_rows($sql) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($sql)) {
			$user = userData($fetched_data['conversation_user_id']);
			if (! empty($user)) {
				$data[] = $user;
			}
		}
	}
	return $data;
}
// Delete User Conversation
function deleteConversation($user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$user_id = secureIt($user_id);
	$user_data = userData($user_id);
	if (empty($user_data)) {
		return false;
	}
	$logged_user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_MESSAGES . " WHERE (`from_id` = {$user_id} AND `to_id` = {$logged_user_id}) OR (`from_id` = {$logged_user_id} AND `to_id` = {$user_id})");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$delete_message = deleteMessage($fetched_data['id'], '', $logged_user_id);
	}
	$query_one = mysqli_query($sql_connect, "DELETE FROM " . T_USER_CHATS . " WHERE `conversation_user_id` = {$user_id} AND `user_id` = {$logged_user_id}");
	if ($query_one) {
		return true;
	}
}
function getUsersForConversation($user_id, $q = '', $limit = 10)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (! isset($user_id)) {
		$user_id = $carovl['user']['user_id'];
	}
	$user_id = secureIt($user_id);
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `user_id` AS `users` FROM " . T_USERS . " WHERE `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = {$user_id}) AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = {$user_id}) AND `active` = '1' AND ((`username` LIKE '%{$q}%') OR CONCAT(`first_name`, ' ', `last_name`) LIKE '%{$q}%') AND `user_id` <> {$user_id} LIMIT {$limit}");
	if (mysqli_num_rows($query) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$user = userData($fetched_data['users']);
			if (! empty($user)) {
				$data[] = $user;
			}
		}
	}
	return $data;
}
// Delete Message Conversation
function deleteMessage($message_id, $media = '', $deleter_id = 0)
{
	global $carovl, $sql_connect;
	if (empty($deleter_id)) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	if (empty($message_id) || ! is_numeric($message_id) || $message_id < 0) {
		return false;
	}
	$user_id = $deleter_id;
	if (empty($user_id) && $carovl['logged_in'] == true) {
		$user_id = $carovl['user']['user_id'];
	}
	$message_id = secureIt($message_id);
	$query_one = mysqli_query($sql_connect, "SELECT * FROM " . T_MESSAGES . " WHERE `id` = {$message_id}");
	if (mysqli_num_rows($query_one) == 1) {
		$fetched_data = mysqli_fetch_assoc($query_one);
		$delete_type = 'deleted_one';
		if ($fetched_data['to_id'] == $user_id) {
			$delete_type = 'deleted_two';
		}
		$query = mysqli_query($sql_connect, "UPDATE " . T_MESSAGES . " SET `$delete_type` = '1' WHERE `id` = {$message_id}");
		if ($query) {
			$query_two = mysqli_query($sql_connect, "SELECT * FROM " . T_MESSAGES . " WHERE `id` = {$message_id}");
			$fetched_data2 = mysqli_fetch_assoc($query_two);
			if ($fetched_data2['deleted_one'] == 1 && $fetched_data2['deleted_two'] == 1) {
				$query_three = mysqli_query($sql_connect, "DELETE FROM " . T_MESSAGES . " WHERE `id` = {$message_id}");
				if ($query_three) {
					if (isset($fetched_data2['media']) && ! empty($fetched_data2['media'])) {
						@unlink($fetched_data2['media']);
					}
					return true;
				}
			}
			return true;
		}
		return false;
	}
}
// Get thumbnail of messages
function getMessagesHeader($data = array())
{
	global $carovl, $sql_connect;
	if (empty($data['session_id'])) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	$message_data = array();
	$user_id = secureIt($data['user_id']);
	if (! empty($data['session_id'])) {
		$logged_user_id = getUserIdFromSessionId($data['session_id'], $data['platform']);
		if (empty($logged_user_id)) {
			return false;
		}
	} else {
		$logged_user_id = secureIt($carovl['user']['user_id']);
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$query = "SELECT * FROM " . T_MESSAGES;
	if (isset($data['new']) && $data['new'] == true) {
		$query .= " WHERE `seen` = 0 AND `from_id` = {$user_id} AND `to_id` = {$logged_user_id} AND `deleted_two` = '0'";
	} else {
		$query .= " WHERE ((`from_id` = {$user_id} AND `to_id` = {$logged_user_id} AND `deleted_two` = '0') OR (`from_id` = {$logged_user_id} AND `to_id` = {$user_id} AND `deleted_one` = '0'))";
	}
	if (! empty($data['message_id'])) {
		$data['message_id'] = secureIt($data['message_id']);
		$query .= " AND `id` = " . $data['message_id'];
	} elseif (! empty($data['before_message_id']) && is_numeric($data['before_message_id']) && $data['before_message_id'] > 0) {
		$data['before_message_id'] = secureIt($data['before_message_id']);
		$query .= " AND `id` < " . $data['before_message_id'] . " AND `id` <> " . $data['before_message_id'];
	} elseif (! empty($data['after_message_id']) && is_numeric($data['after_message_id']) && $data['after_message_id'] > 0) {
		$data['after_message_id'] = secureIt($data['after_message_id']);
		$query .= " AND `id` > " . $data['after_message_id'] . " AND `id` <> " . $data['after_message_id'];
	}
	$sql = mysqli_query($sql_connect, $query);
	$query_limit_from = mysqli_num_rows($sql) - 50;
	if ($query_limit_from < 1) {
		$query_limit_from = 0;
	}
	$query .= "ORDER BY `id` DESC LIMIT 1";
	$sql_two = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql_two);
	if (! isset($data['user_data'])) {
		$fetched_data['message_user'] = userData($fetched_data['from_id']);
		$fetched_data['owner'] = ($fetched_data['message_user']['user_id'] == $logged_user_id) ? 1 : 0;
	}
	if (! empty($fetched_data['text'])) {
		$fetched_data['text'] = editMarkup($fetched_data['text']);
	}
	return $fetched_data;
}
// Register Post Comment
function registerPostComment($data = array())
{
	global $carovl, $sql_connect;
	if (empty($data['post_id']) || ! is_numeric($data['post_id']) || $data['post_id'] < 0) {
		return false;
	}
	if (empty($data['text'])) {
		return false;
	}
	if (empty($data['user_id']) || ! is_numeric($data['user_id']) || $data['user_id'] < 0) {
		return false;
	}
	if (! empty($data['text'])) {
		if ($carovl['config']['max_characters'] > 0) {
			if (strlen($data['text']) > $carovl['config']['max_characters']) {
				return false;
			}
		}
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
		$i = 0;
		preg_match_all($link_regex, $data['text'], $matches);
		foreach ($matches[0] as $match) {
			$match_url = strip_tags($match);
			$syntax = '[a]' . urlencode($match_url) . '[/a]';
			$data['text'] = str_replace($match, $syntax, $data['text']);
		}
		$mention_regex = '/@([A-Za-z0-9_]+)/i';
		preg_match_all($mention_regex, $data['text'], $matches);
		foreach ($matches[1] as $match) {
			$match = secureIt($match);
			$match_user = userData(userIdFromUsername($match));
			$match_search = '@' . $match;
			$match_replace = '@[' . $match_user['user_id'] . ']';
			if (isset($match_user['user_id'])) {
				$data['text'] = str_replace($match_search, $match_replace, $data['text']);
				$mentions[] = $match_user['user_id'];
			}
		}
	}
	$hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
	preg_match_all($hashtag_regex, $data['text'], $matches);
	foreach ($matches[1] as $match) {
		if (! is_numeric($match)) {
			$hashdata = getHashtag($match);
			if (is_array($hashdata)) {
				$match_search = '#' . $match;
				$match_replace = '#[' . $hashdata['id'] . ']';
				if (mb_detect_encoding($match_search, 'ASCII', true)) {
					$data['text'] = preg_replace("/$match_search\b/i", $match_replace, $data['text']);
				} else {
					$data['text'] = str_replace($match_search, $match_replace, $data['text']);
				}
				$hashtag_query = mysqli_query($sql_connect, "UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id']);
			}
		}
	}
	$post = postData($data['post_id']);
	$text = '';
	$type2 = '';
	if (isset($post['post_text']) && ! empty($post['post_text'])) {
		$text = substr($post['post_text'], 0, 10) . '...';
	}
	if (isset($post['post_file']) && ! empty($post['post_file'])) {
		if (strpos($post['post_file'], '_image') !== false) {
			$type2 = 'post_image';
		} elseif (strpos($post['post_file'], '_video') !== false) {
			$type2 = 'post_video';
		} elseif (strpos($post['post_file'], '_avatar') !== false) {
			$type2 = 'post_avatar';
		} elseif (strpos($post['post_file'], '_audio') !== false) {
			$type2 = 'post_audio';
		} elseif (strpos($post['post_file'], '_cover') !== false) {
			$type2 = 'post_cover';
		} else {
			$type2 = 'post_file';
		}
	}
	$user_id = getUserIdFromPostId($data['post_id']);
	if (empty($user_id)) {
		return false;
	}
	$fields = '`' . implode('`, `', array_keys($data)) . '`';
	$comment_data = '\'' . implode('\', \'', $data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_COMMENTS . " ({$fields}) VALUES ({$comment_data})");
	if ($query) {
		$insert_comment_id = mysqli_insert_id($sql_connect);
		$activity_data = array(
			'post_id' => $data['post_id'],
			'user_id' => $data['user_id'],
			'post_user_id' => $user_id,
			'activity_type' => 'commented_post'
		);
		$register_activity = registerActivity($activity_data);
		$notification_data = array(
			'recipient_id' => $user_id,
			'post_id' => $data['post_id'],
			'type' => 'comment',
			'text' => $text,
			'type2' => $type2,
			'url' => 'index.php?page=post&id=' . $data['post_id'] . '&ref=' . $insert_comment_id
		);
		registerNotification($notification_data);
		if (isset($mentions) && is_array($mentions)) {
			foreach ($mentions as $mention) {
				$notification_data = array(
					'recipient_id' => $mention,
					'type' => 'comment_mention',
					'url' => 'index.php?page=post&id=' . $data['post_id']
				);
				registerNotification($notification_data);
			}
		}
		return $insert_comment_id;
	}
}
// Delete Post
function deletePost($post_id = 0)
{
	global $carovl, $sql_connect, $cache;
	if ($post_id < 1 || empty($post_id) || ! is_numeric($post_id)) {
		return false;
	}
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($post_id);
	$event = postData($post_id);
	if (isset($event['event_id']) && $event['event_id'] > 0) {
		$subquery = "";
	} else {
		$subquery = " AND (`user_id` = {$user_id} OR `recipient_id` = {$user_id} OR `group_id` IN (SELECT `id` FROM " . T_GROUPS . " WHERE `user_id` = {$user_id}))";
	}
	$query = mysqli_query($sql_connect, "SELECT `id`, `user_id`, `recipient_id`, `post_file`, `post_type`, `post_link_image`, `multi_image`, `album_name` FROM " . T_POSTS . " WHERE `id` = {$post_id} {$subquery}");
	if (mysqli_num_rows($query) > 0 || isAdmin() || isModerator()) {
		$fetched_data = mysqli_fetch_assoc($query);
		if ($fetched_data['post_type'] == 'profile_picture' || $fetched_data['post_type'] == 'profile_picture_deleted' || $fetched_data['post_type'] == 'profile_cover_picture') {
			$query_delete_one = mysqli_query($sql_connect, "UPDATE " . T_POSTS . " SET `post_type` = 'profile_picture_deleted' WHERE `id` = '" . $fetched_data['id'] . "'");
			return true;
		}
		if (isset($fetched_data['post_file']) && ! empty($fetched_data['post_file'])) {
			if ($fetched_data['post_type'] != 'profile_picture' && $fetched_data['post_type'] != 'profile_cover_picture') {
				@unlink(trim($fetched_data['post_file']));
				// $delete_from_s3 = deleteFromS3($fetched_data['post_file']);
			}
		}
		if (isset($fetched_data['post_link_image']) && ! empty($fetched_data['post_link_image'])) {
			@unlink($fetched_data['post_link_image']);
			// $delete_from_s3 = deleteFromS3($fetched_data['post_link_image']);
		}
		if (! empty($fetched_data['album_name']) || ! empty($fetched_data['multi_image'])) {
			$query_delete_two = mysqli_query($sql_connect, "SELECT `image` FROM " . T_ALBUMS_MEDIA . " WHERE `post_id` = {$post_id}");
			while ($fetched_delete_data = mysqli_fetch_assoc($query_delete_two)) {
				$explode = @end(explode('.', $fetched_delete_data['image']));
				$explode2 = @explode('.', $fetched_delete_data['image']);
				$media_one = $explode2[0] . '_small.' . $explode;
				@unlink(trim($media_one));
				@unlink($fetched_delete_data['image']);
				// $delete_from_s3 = deleteFromS3($media_one);
				// $delete_from_s3 = deleteFromS3($fetched_delete_data['image']);
			}
		}
		$query_two = mysqli_query($sql_connect, "SELECT `id` FROM " . T_COMMENTS . " WHERE `post_id` = {$post_id}");
		while ($fetched_data = mysqli_fetch_assoc($query_two)) {
			deletePostComment($fetched_data['id']);
		}
		$product = postData($post_id);
		$product_id = $product['product_id'];
		if (! empty($product_id)) {
			$query_three = mysqli_query($sql_connect, "SELECT `image` FROM " . T_PRODUCTS_MEDIA . " WHERE `product_id` = {$product_id}");
			while ($fetched_data = mysqli_fetch_assoc($query_three)) {
				$explode = @end(explode('.', $fetched_data['image']));
				$explode2 = @explode('.', $fetched_data['image']);
				$media_one = $explode2[0] . '_small.' . $explode;
				@unlink(trim($media_one));
				@unlink($fetched_data['image']);
				// $delete_from_s3 = deleteFromS3($media_one);
				// $delete_from_s3 = deleteFromS3($fetched_data['image']);
			}
		}
		$article = postData($post_id);
		$article_id = $article['article_id'];
		if (! empty($article_id)) {
			$query_four = mysqli_query($sql_connect, "SELECT `article_thumbnail` FROM " . T_ARTICLES . " WHERE `id` = {$article_id}");
			while ($fetched_data = mysqli_fetch_assoc($query_four)) {
				@unlink($fetched_data['article_thumbnail']);
			}
		}
		$query_delete = mysqli_query($sql_connect, "DELETE FROM " . T_POSTS . " WHERE `id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_POSTS . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_ALBUMS_MEDIA . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENTS . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_LIKES . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_SAVED_POSTS . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_ACTIVITIES . " WHERE `post_id` = {$post_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_PRODUCTS_MEDIA . " WHERE `product_id` = {$product_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_PRODUCTS . " WHERE `id` = {$product_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_ARTICLES . " WHERE `id` = {$article_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_VIDEO_VIEWS . " WHERE `post_id` = {$post_id}");
		if ($carovl['config']['cache_system'] == 1) {
			$cache->delete(md5($post_id) . '_P_Data.tmp');
		}
		return true;
	} else {
		return false;
	}
}
// Delete Post Comments
function deletePostComment($comment_id = '')
{
	global $carovl, $sql_connect;
	if ($comment_id < 0 || empty($comment_id) || ! is_numeric($comment_id)) {
		return false;
	}
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$post_id = getPostIdFromCommentId($comment_id);
	$query = mysqli_query($sql_connect, "SELECT `id`, `user_id` FROM " . T_COMMENTS . " WHERE `id` = {$comment_id} AND `user_id` = {$logged_user_id}");
	if (mysqli_num_rows($query) > 0 || isPostOwner($post_id, $logged_user_id) === true) {
		$query_delete = mysqli_query($sql_connect, "DELETE FROM " . T_COMMENTS . " WHERE `id` = {$comment_id}");
		$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENT_LIKES . " WHERE `comment_id` = {$comment_id}");
		if ($query_delete) {
			$query_one = mysqli_query($sql_connect, "SELECT `id` FROM " . T_COMMENT_REPLIES . " WHERE `comment_id` = {$comment_id}");
			while ($fetched_data = mysqli_fetch_assoc($query_one)) {
				deleteCommentReply($fetched_data['id']);
			}
			$delete_activity = deleteActivity($post_id, $logged_user_id, 'commented_post');
			return true;
		}
	} else {
		return false;
	}
}
// Get Post Id From URL
function getPostIdFromUrl($string)
{
	$slug_string = '';
	$string = secureIt($string);
	if (preg_match('/[^a-z\s-]/i', $string)) {
		$string_exp = @explode('_', $string);
		$slug_string = $string_exp[0];
	} else {
		$slug_string = $string;
	}
	return secureIt($slug_string);
}
// Get Post Id From Comment Id
function getPostIdFromCommentId($comment_id = 0)
{
	global $sql_connect;
	if (empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 1) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$query = mysqli_query($sql_connect, "SELECT `post_id` FROM " . T_COMMENTS . " WHERE `id` = {$comment_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['post_id'];
	}
}
// Delete Comment Reply
function deleteCommentReply($comment_id = '')
{
	global $carovl, $sql_connect;
	if ($comment_id < 0 || empty($comment_id) || !is_numeric($comment_id)) {
		return false;
	}
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$query_delete = mysqli_query($sql_connect, "DELETE FROM " . T_COMMENT_REPLIES . " WHERE `id` = {$comment_id}");
	$query_delete .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENT_LIKES . " WHERE `reply_id` = {$comment_id}");
	if ($query_delete) {
		return true;
	}
}
// Get User Follow Requests
function getFollowRequests($user_id = 0, $q = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$data = array();
	if (empty($user_id) || $user_id == 0) {
		$user_id = $carovl['user']['user_id'];
	}
	if (! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `follower_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` <> {$user_id} AND `following_id` = {$user_id} AND `active` = '0') AND `active` = '1'";
	if (! empty($q)) {
		$q = secureIt($q);
		$query .= " AND CONCAT(`first_name`, ' ', `last_name`) LIKE '%{$q}%'";
	}
	$query .= " ORDER BY `user_id` DESC";
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
// Get User Notifications
function getNotifications($data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$get = array();
	if (! isset($data['account_id']) || empty($data['account_id'])) {
		$data['account_id'] = $carovl['user']['user_id'];
	}
	if (! is_numeric($data['account_id']) || $data['account_id'] < 1) {
		return false;
	}
	if ($data['account_id'] == $carovl['user']['user_id']) {
		$account = $carovl['user'];
	} else {
		$data['account_id'] = $data['account_id'];
		$account = userData($data['account_id']);
	}
	if ($account['user_id'] != $carovl['user']['user_id']) {
		return false;
	}
	if (empty($data['limit'])) {
		$data['limit'] = 15;
	}
	$new_notif = countNotifications(array(
		'unread' => true
	));
	if ($new_notif > 0) {
		$subquery = '';
		if (isset($data['type2']) && ! empty($data['type2'])) {
			if ($data['type2'] == 'popunder') {
				$timepopunder = time() - 60;
				$subquery = " AND `seen_pop` = 0 AND `time` >= " . $timepopunder;
			}
		}
		$query = "SELECT * FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $account['user_id'] . " AND `seen` = 0 {$subquery} ORDER BY `id` DESC";
	} else {
		$query = "SELECT * FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $account['user_id'];
		if (isset($data['unread']) && $data['unread'] == true) {
			$query .= " AND `seen` = 0";
		}
		if (isset($data['type2']) && ! empty($data['type2'])) {
			if ($data['type2'] == 'popunder') {
				$timepopunder = time() - 60;
				$query .= " AND `seen_pop` = 0 AND `time` >= " . $timepopunder;
			}
		}
		$query .= " ORDER BY `id` DESC LIMIT " . $data['limit'];
	}
	if (isset($data['all']) && $data['all'] == true) {
		$query = "SELECT * FROM " . T_NOTIFICATIONS . " WHERE `recipient_id` = " . $account['user_id'] . " AND `seen` = 0 ORDER BY `id` DESC LIMIT 20";
	}
	$sql = mysqli_query($sql_connect, $query);
	if (mysqli_num_rows($sql) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($sql)) {
			$fetched_data['notifier'] = userData($fetched_data['notifier_id']);
			$fetched_data['notifier']['url'] = seoLink('index.php?page=timeline&u=' . $fetched_data['notifier']['username']);
			$cutted_url = substr($fetched_data['url'], 9);
			$fetched_data['url'] = seoLink($fetched_data['url']);
			$fetched_data['ajax_url'] = $cutted_url;
			$get[] = $fetched_data;
		}
	}
	mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `time` < " . (time() - (60 * 60 * 24 * 5)) . " AND `seen` > 0");
	return $get;
}
function isOwner($user_id)
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$user_id = secureIt($user_id);
	$logged_user_id = secureIt($carovl['user']['user_id']);
	if (isAdmin($logged_user_id) === false) {
		if ($user_id == $logged_user_id) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}
// Get User Profile Image
function getUserProfileImage($image = '', $type = '')
{
	global $carovl, $sql_connect;
	if (empty($image)) {
		return false;
	}
	$explode = @end(explode('.', $image));
	$explode2 = @explode('.', $image);
	$image = $explode2[0] . '_full.' . $explode;
	$query = mysqli_query($sql_connect, "SELECT `post_id` FROM " . T_POSTS . " WHERE `post_file` = '{$image}'");
	if (mysqli_num_rows($query) > 0) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['post_id'];
	}
}
// Register Comment Like
function registerCommentLike($comment_id, $text = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($comment_id) || empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 1) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$user_id = secureIt($carovl['user']['user_id']);
	$comment_timeline_id = getUserIdFromCommentId($comment_id);
	$post_id = getPostIdFromCommentId($comment_id);
	$post_data = postData($post_id);
	if (empty($comment_timeline_id)) {
		return false;
	}
	if (isset($text) && ! empty($text)) {
		$text = substr($text, 0, 25);
	}
	$text = secureIt($text);
	if (isCommentLiked($comment_id, $user_id) === true) {
		$query = "DELETE FROM " . T_COMMENT_LIKES . " WHERE `comment_id` = {$comment_id} AND `user_id` = {$user_id}";
		mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `post_id` = {$post_id} AND `recipient_id` = {$comment_timeline_id} AND `type` = 'liked_comment'");
		$sql = mysqli_query($sql_connect, $query);
		if ($sql) {
			return 'unliked';
		}
	} else {
		$query_two = mysqli_query($sql_connect, "INSERT INTO " . T_COMMENT_LIKES . " (`user_id`, `post_id`, `comment_id`) VALUES ({$user_id}, {$post_id}, {$comment_id})");
		if ($query_two) {
			$notification_data = array(
				'recipient_id' => $comment_timeline_id,
				'post_id' => $post_id,
				'type' => 'liked_comment',
				'text' => $text,
				'url' => 'index.php?page=post&id=' . $post_id . '&ref=' . $comment_id
			);
			registerNotification($notification_data);
			return 'liked';
		}
	}
}
// Get User ID from Comment Id
function getUserIdFromCommentId($comment_id)
{
	global $sql_connect;
	if (empty($comment_id) || ! is_numeric($comment_id) || $comment_id < 1) {
		return false;
	}
	$comment_id = secureIt($comment_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_COMMENTS . " WHERE `id` = {$comment_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['user_id'];
	}
}
// Get Follow Button
function getFollowButton($user_id = 0)
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if ($user_id == $carovl['user']['user_id']) {
		return false;
	}
	$account = $carovl['follow'] = userData($user_id);
	if (! isset($carovl['follow']['user_id'])) {
		return false;
	}
	$user_id = secureIt($user_id);
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$follow_button = 'buttons/follow';
	$unfollow_button = 'buttons/unfollow';
	$requested_button = 'buttons/requested';
	$accept_button = 'buttons/accept-request';
	if (isFollowing($user_id, $logged_user_id)) {
		return loadPage($unfollow_button);
	} else {
		if (isFollowRequested($user_id, $logged_user_id)) {
			return loadPage($requested_button);
		} else {
			return loadPage($follow_button);
		}
	}
}
function isFollowRequested($following_id = 0, $follower_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($following_id) || empty($following_id) || ! is_numeric($following_id) || $following_id < 1) {
		return false;
	}
	if (! isset($follower_id) || empty($follower_id) || ! is_numeric($follower_id) || $follower_id < 1) {
		$follower_id = $carovl['user']['user_id'];
	}
	if (! is_numeric($follower_id) || $follower_id < 1) {
		return false;
	}
	$following_id = secureIt($following_id);
	$follower_id = secureIt($follower_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$follower_id} AND `following_id` = {$following_id} AND `active` = '0'");
	if (mysqli_num_rows($query) > 0) {
		return true;
	}
}
function deleteFollow($following_id = 0, $follower_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($following_id) || empty($following_id) || ! is_numeric($following_id) || $following_id < 1) {
		return false;
	}
	if (! isset($follower_id) || empty($follower_id) || ! is_numeric($follower_id) || $follower_id < 1) {
		return false;
	}
	$following_id = secureIt($following_id);
	$follower_id = secureIt($follower_id);
	if (isFollowing($following_id, $follower_id) === false && isFollowRequested($following_id, $follower_id) === false) {
		return false;
	} else {
		$query = mysqli_query($sql_connect, "DELETE FROM " . T_FOLLOWERS . " WHERE `following_id` = {$following_id} AND `follower_id` = {$follower_id}");
		if ($query) {
			return true;
		}
	}
}
function registerFollow($following_id = 0, $follower_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($following_id) || empty($following_id) || $following_id < 1) {
		return false;
	}
	if (! isset($follower_id) || empty($follower_id) || $follower_id < 1) {
		return false;
	}
	if (isBlocked($following_id)) {
		return false;
	}
	$following_id = secureIt($following_id);
	$follower_id = secureIt($follower_id);
	$active = 1;
	if (isFollowing($following_id, $follower_id) === true) {
		return false;
	}
	$follower_data = userData($follower_id);
	$following_data = userData($following_id);
	if (empty($follower_data['user_id']) || empty($following_data['user_id'])) {
		return false;
	}
	if ($following_data['follow_privacy'] == 1) {
		$active = 0;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_FOLLOWERS . " (`following_id`, `follower_id`, `active`) VALUES ({$following_id}, {$follower_id}, '{$active}')");
	if ($query) {
		if ($active == 1) {
			$notification_data = array(
				'recipient_id' => $following_id,
				'notifier_id' => $follower_id,
				'type' => 'following',
				'url' => 'index.php?page=timeline&u=' . $follower_data['username']
			);
			registerNotification($notification_data);
		}
		return true;
	}
}
// Get Followers User
function getFollowers($user_id, $type = '', $limit = '', $after_user_id = '')
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$after_user_id = secureIt($after_user_id);
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `follower_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` <> {$user_id} AND `following_id` = {$user_id} AND `active` = '1') AND `active` = '1'";
	if (! empty($after_user_id) && is_numeric($after_user_id)) {
		$query .= " AND `user_id` < {$after_user_id}";
	}
	if ($carovl['logged_in'] == true) {
		$logged_user_id = secureIt($carovl['user']['user_id']);
		$query .= " AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}')";
	}
	if ($type == 'profile' && ! empty($limit) && is_numeric($limit)) {
		$query .= " ORDER BY `user_id` DESC LIMIT {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
// Get Following User
function getFollowing($user_id, $type = '', $limit = '', $after_user_id = '')
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$after_user_id = secureIt($after_user_id);
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `following_id` <> {$user_id} AND `active` = '1') AND `active` = '1'";
	if (! empty($after_user_id) && is_numeric($after_user_id)) {
		$query .= " AND `user_id` < {$after_user_id}";
	}
	if ($carovl['logged_in'] == true) {
		$logged_user_id = secureIt($carovl['user']['user_id']);
		$query .= " AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}')";
	}
	if ($type == 'profile' && ! empty($limit) && is_numeric($limit)) {
		$query .= " ORDER BY `user_id` DESC LIMIT {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
// Count Followers User
function countFollowers($user_id)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$query = "SELECT COUNT(`user_id`) AS `count` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `follower_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` <> {$user_id} AND `following_id` = {$user_id} AND `active` = '1') AND `active` = '1'";
	if ($carovl['logged_in'] == true) {
		$logged_user_id = secureIt($carovl['user']['user_id']);
		$query .= "AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}')";
	}
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['count'];
}
// Count Following User
function countFollowing($user_id)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$query = "SELECT COUNT(`user_id`) AS `count` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `following_id` <> {$user_id} AND `active` = '1') AND `active` = '1'";
	if ($carovl['logged_in'] == true) {
		$logged_user_id = secureIt($carovl['user']['user_id']);
		$query .= "AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}')";
	}
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['count'];
}
// Register Article
function registerArticle($article_data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($article_data)) {
		return false;
	}
	$fields = '`' . implode('`, `', array_keys($article_data)) . '`';
	$data = '\'' . implode('\', \'', $article_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_ARTICLES . " ({$fields}) VALUES ({$data})");
	if ($query) {
		return mysqli_insert_id($sql_connect);
	}
	return false;
}
function updateArticle($id = 0, $update_data = array())
{
	global $carovl, $sql_connect;
	$update = array();
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	if (empty($id)) {
		return false;
	}
	$id = secureIt($id);
	if (isArticleOwner($id) === false) {
		return false;
	}
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . secureIt($data, 0) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_ARTICLES . " SET {$implode} WHERE `id` = {$id}");
	return $query;
}
function isArticleOwner($article_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($article_id)) {
		return false;
	}
	if (empty($user_id)) {
		$user_id = $carovl['user']['user_id'];
	}
	$user_id = secureIt($user_id);
	$article_id = secureIt($article_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_ARTICLES . " WHERE `user_id` = {$user_id} AND `id` = {$article_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	return ($fetched_data['count'] > 0) ? true : false;
}
function articleData($id = 0)
{
	global $carovl, $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_ARTICLES . " WHERE `id` = '{$id}'");
	$fetched_data = mysqli_fetch_assoc($query);
	if (! empty($fetched_data)) {
		$fetched_data['author'] = userData($fetched_data['user_id']);
		$fetched_data['thumbnail'] = getMedia($fetched_data['article_thumbnail']);
		$fetched_data['tags'] = @explode(',', $fetched_data['article_tags']);
		$fetched_data['time_posted'] = timeElapsedString($fetched_data['time']);
		$fetched_data['url'] = seoLink('index.php?page=article&id=' . $fetched_data['id'] . '_' . slugPost($fetched_data['article_title']));
		if ($carovl['logged_in'] == true) {
			$fetched_data['is_post_admin'] = ($fetched_data['user_id'] == $carovl['user']['user_id']) ? true : false;
		}
	}
	return $fetched_data;
}
function getPostIdFromArticleId($id)
{
	global $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `article_id` = '{$id}'");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['id'];
}
// Get User Articles
function getUserArticles($user_id = 0, $offset = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	$after_article_id = '';
	if ($offset > 0) {
		$after_article_id = " AND `id` < {$offset} AND `id` <> {$offset}";
	}
	$user_id = secureIt($user_id);
	$offset = secureIt($offset);
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_ARTICLES . " WHERE `user_id` = '{$user_id}' {$after_article_id} ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = articleData($fetched_data['id']);
	}
	return $data;
}
function isOnline($user_id)
{
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$lastseen = userData($user_id);
	$time = time() - 60;
	if ($lastseen['lastseen'] < $time) {
		return false;
	} else {
		return true;
	}
}
function registerTyping($recipient_id, $is_typing = 1)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($recipient_id) || ! is_numeric($recipient_id) || $recipient_id < 0) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$recipient_id = secureIt($recipient_id);
	$typing = 1;
	if ($is_typing == 0) {
		$typing = 0;
	}
	if (isFollowing($user_id, $recipient_id) === false) {
		return false;
	}
	if (isFollowing($recipient_id, $user_id) === false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_FOLLOWERS . " SET `is_typing` = '$typing' WHERE `following_id` = '{$user_id}' AND `follower_id` = {$recipient_id}");
	if ($query) {
		return true;
	}
}
function getMessages($data = array(), $limit = 50)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$message_data = array();
	$user_id = secureIt($data['user_id']);
	$logged_user_id = secureIt($carovl['user']['user_id']);
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$query = "SELECT * FROM " . T_MESSAGES;
	if (isset($data['new']) && $data['new'] == true) {
		$query .= " WHERE `seen` = 0 AND `from_id` = {$user_id} AND `to_id` = {$logged_user_id} AND `deleted_two` = '0'";
	} else {
		$query .= " WHERE ((`from_id` = {$user_id} AND `to_id` = {$logged_user_id} AND `deleted_two` = '0') OR (`from_id` = {$logged_user_id} AND `to_id` = {$user_id} AND `deleted_one` = '0'))";
	}
	if (! empty($data['message_id'])) {
		$data['message_id'] = secureIt($data['message_id']);
		$query .= " AND `id` = " . $data['message_id'];
	} elseif (! empty($data['before_message_id']) && is_numeric($data['before_message_id']) && $data['before_message_id'] > 0) {
		$data['before_message_id'] = secureIt($data['before_message_id']);
		$query .= " AND `id` < " . $data['before_message_id'] . " AND `id` <> " . $data['before_message_id'];
	} elseif (! empty($data['after_message_id']) && is_numeric($data['after_message_id']) && $data['after_message_id'] > 0) {
		$data['after_message_id'] = secureIt($data['after_message_id']);
		$query .= " AND `id` > " . $data['after_message_id'] . " AND `id` <> " . $data['after_message_id'];
	}
	$sql = mysqli_query($sql_connect, $query);
	$query .= " ORDER BY `id` ASC";
	$sql_one = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql_one)) {
		$fetched_data['message_user'] = userData($fetched_data['from_id']);
		$fetched_data['text'] = getMarkup($fetched_data['text']);
		$fetched_data['text'] = getEmoticons($fetched_data['text']);
		$fetched_data['media'] = getMedia($fetched_data['media']);
		$fetched_data['owner'] = ($fetched_data['message_user']['user_id'] == $carovl['user']['user_id']) ? 1 : 0;
		$message_data[] = $fetched_data;
		if ($fetched_data['message_user']['user_id'] == $user_id && $fetched_data['seen'] == 0) {
			mysqli_query($sql_connect, "UPDATE " . T_MESSAGES . " SET `seen` = " . time() . " WHERE `id` = " . $fetched_data['id']);
		}
	}
	return $message_data;
}
function registerMessage($msg_data = array(), $session_id = 0)
{
	global $carovl, $sql_connect;
	if (empty($session_id)) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	if (empty($msg_data)) {
		return false;
	}
	if (empty($msg_data['to_id']) || ! is_numeric($msg_data['to_id']) || $msg_data['to_id'] < 0) {
		return false;
	}
	if (empty($msg_data['from_id']) || ! is_numeric($msg_data['from_id']) || $msg_data['from_id'] < 0) {
		return false;
	}
	if ($msg_data['to_id'] == $msg_data['from_id']) {
		return false;
	}
	if (empty($msg_data['text']) || ! isset($msg_data['text']) || strlen($msg_data['text']) < 0) {
		if (empty($msg_data['media']) || ! isset($msg_data['media']) || strlen($msg_data['media']) < 0) {
			return false;
		}
	}
	$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
	$i = 0;
	preg_match_all($link_regex, $msg_data['text'], $matches);
	foreach ($matches[0] as $match) {
		$match_url = strip_tags($match);
		$syntax = '[a]' . urlencode($match_url) . '[/a]';
		$msg_data['text'] = str_replace($match, $syntax, $msg_data['text']);
	}
	$mention_regex = '/@([A-Za-z0-9_]+)/i';
	preg_match_all($mention_regex, $msg_data['text'], $matches);
	foreach ($matches[1] as $match) {
		$match = secureIt($match);
		$match_user = userData(userIdFromUsername($match));
		$match_search = '@' . $match;
		$match_replace = '@[' . $match_user['user_id'] . ']';
		if (isset($match_user['user_id'])) {
			$msg_data['text'] = str_replace($match_search, $match_replace, $msg_data['text']);
			$mentions[] = $match_user['user_id'];
		}
	}
	$hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
	preg_match_all($hashtag_regex, $msg_data['text'], $matches);
	foreach ($matches[1] as $match) {
		if (! is_numeric($match)) {
			$hashdata = getHashtag($match);
			if (is_array($hashdata)) {
				$match_search = '#' . $match;
				$match_replace = '#[' . $hashdata['id'] . ']';
				if (mb_detect_encoding($match_search, 'ASCII', true)) {
					$msg_data['text'] = preg_replace("/$match_search\b/i", $match_replace, $msg_data['text']);
				} else {
					$msg_data['text'] = str_replace($match_search, $match_replace, $msg_data['text']);
				}
				$hashtag_query = "UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id'];
			}
		}
	}
	$fields = '`' . implode('`, `', array_keys($msg_data)) . '`';
	$data = '\'' . implode('\', \'', $msg_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_MESSAGES . " ({$fields}) VALUES ({$data})");
	if ($query) {
		$message_id = mysqli_insert_id($sql_connect);
		$from_id = 0;
		if (! empty($session_id)) {
			$from_id = $msg_data['from_id'];
		}
		$update_user_chats = createUserChat($msg_data['to_id'], $session_id, $from_id);
		return $message_id;
	} else {
		return false;
	}
}
function createUserChat($user_id = 0, $session_id = '', $from_id = 0)
{
	global $carovl, $sql_connect;
	if (empty($session_id)) {
		if ($carovl['logged_in'] == false) {
			return false;
		}
	}
	if (empty($user_id)) {
		return false;
	}
	if (! empty($from_id)) {
		$logged_user_id = $from_id;
	} else {
		$logged_user_id = secureIt($carovl['user']['user_id']);
	}
	$user_id = secureIt($user_id);
	$time = time();
	$query_one = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_USER_CHATS . " WHERE `conversation_user_id` = '{$user_id}' AND `user_id` = '{$logged_user_id}'");
	$fetched_data = mysqli_fetch_assoc($query_one);
	if ($fetched_data['count'] > 0) {
		$query_two = mysqli_query($sql_connect, "UPDATE " . T_USER_CHATS . " SET `time` = '{$time}' WHERE `conversation_user_id` = '{$user_id}' AND `user_id` = '{$logged_user_id}'");
		$query_two = mysqli_query($sql_connect, "UPDATE " . T_USER_CHATS . " SET `time` = '{$time}' WHERE `conversation_user_id` = '{$logged_user_id}' AND `user_id` = '{$user_id}'");
		$query_three = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_USER_CHATS . " WHERE `user_id` = '{$user_id}' AND `conversation_user_id` = '{$logged_user_id}'");
		$fetched_data2 = mysqli_fetch_assoc($query_three);
		if ($fetched_data2['count'] == 0) {
			$query_four = mysqli_query($sql_connect, "INSERT INTO " . T_USER_CHATS . " (`user_id`, `conversation_user_id`, `time`) VALUES ('{$user_id}', '{$logged_user_id}', '{$time}')");
		}
		if ($query_two) {
			return true;
		}
	} else {
		$query_two = mysqli_query($sql_connect, "INSERT INTO " . T_USER_CHATS . " (`user_id`, `conversation_user_id`, `time`) VALUES ('{$logged_user_id}', '{$user_id}', '{$time}')");
		if ($query_two) {
			$query_three = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_USER_CHATS . " WHERE `conversation_user_id` = '{$logged_user_id}' AND `user_id` = '{$user_id}'");
			$fetched_data = mysqli_fetch_assoc($query_three);
			if ($fetched_data['count'] == 0) {
				$query_four = mysqli_query($sql_connect, "INSERT INTO " . T_USER_CHATS . " (`user_id`, `conversation_user_id`, `time`) VALUES ('{$user_id}', '{$logged_user_id}', '{$time}')");
			}
			return true;
		}
	}
}
function isTyping($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT `is_typing` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$logged_user_id} AND `following_id` = {$user_id} AND `is_typing` = 1");
	return (sqlResult($query, 0) == 1) ? true : false;
}
function seenMessage($message_id)
{
	global $sql_connect;
	$message_id = secureIt($message_id);
	$query = mysqli_query($sql_connect, "SELECT `seen` FROM " . T_MESSAGES . " WHERE `id` = {$message_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	if ($fetched_data['seen'] > 0) {
		$data = array();
		$data['time'] = date('c', $fetched_data['seen']);
		$data['seen'] = timeElapsedString($fetched_data['seen']);
		return $data;
	} else {
		return false;
	}
}
// Block User
function registerBlock($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || $user_id < 0 || ! is_numeric($user_id)) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_BLOCKS . " (`blocker`, `blocked`) VALUES ('{$logged_user_id}', '{$user_id}')");
	if ($query) {
		$query_delete = mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE (`notifier_id` = '{$user_id}' AND `recipient_id` = '{$logged_user_id}') OR (`notifier_id` = '{$logged_user_id}' AND `recipient_id` = '{$user_id}')");
		return true;
	} else {
		return false;
	}
}
// Remove Block
function removeBlock($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || $user_id < 0 || ! is_numeric($user_id)) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}' AND `blocked` = '{$user_id}'");
	return ($query) ? true : false;
}
function getSearch($q)
{
	global $carovl, $sql_connect;
	$q = secureIt($q);
	$data = array();
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE ((`username` LIKE '%{$q}%') OR CONCAT(`first_name`, ' ', `last_name`) LIKE '%{$q}%') AND `active` = '1'";
	if ($carovl['logged_in'] == true) {
		$logged_user_id = secureIt($carovl['user']['user_id']);
		$query .= " AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$logged_user_id}') AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$logged_user_id}')";
	}
	$query .= " LIMIT 3";
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	$sql = mysqli_query($sql_connect, "SELECT `id` FROM " . T_GROUPS . " WHERE ((`group_name` LIKE '%{$q}%') OR `group_title` LIKE '%{$q}%') AND `active` = '1'");
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = groupData($fetched_data['id']);
	}
	return $data;
}
function userStatus($user_id, $lastseen, $type = '')
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if ($carovl['user']['show_lastseen'] == 0) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	if (empty($lastseen) || ! is_numeric($lastseen) || $lastseen < 0) {
		return false;
	}
	$status = '';
	$user_id = secureIt($user_id);
	$lastseen = secureIt($lastseen);
	$time = time() - 60;
	if ($lastseen < $time) {
		if ($type == 'profile') {
			$status = '<span class="lastseen">' . $carovl['lang']['lastseen'] . ' <span style="color: #999;">' . timeElapsedString($lastseen) . '</span></span>';
		} else {
			$status = '<span class="lastseen">' . $carovl['lang']['lastseen'] . ' ' . timeElapsedString($lastseen) . '</span>';
		}
	} else {
		$status = '<span class="online">' . $carovl['lang']['online'] . '</span>';
	}
	return $status;
}
function getHashSearch($q)
{
	global $sql_connect;
	$search_query = str_replace('#', '', secureIt($q));
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_HASHTAGS . " WHERE `tag` LIKE '%{$search_query}%' ORDER BY `trend_use_num` DESC LIMIT 10");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$fetched_data['url'] = seoLink('index.php?page=hashtag&hash=' . $fetched_data['tag']);
		$data[] = $fetched_data;
	}
	return $data;
}
function countHashtagPosts($hash)
{
	global $sql_connect;
	$search_query = str_replace('#', '', secureIt($hash));
	$hashdata = getHashtag($search_query, false);
	if (is_array($hashdata)) {
		$search_string = '#[' . $hashdata['id'] . ']';
		$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_POSTS . " WHERE `post_text` LIKE '%{$search_string}%'");
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['count'];
	}
}
function registerRecentSearch($id = 0, $type = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	if (empty($type)) {
		return false;
	}
	$id = secureIt($id);
	$type = secureIt($type);
	if ($type == 'timeline') {
		$type = 'user';
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$query_one = mysqli_query($sql_connect, "SELECT `id` FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id} AND `search_id` = '{$id}' AND `search_type` = '{$type}'");
	if (mysqli_num_rows($query_one) > 0) {
		$query_two = mysqli_query($sql_connect, "DELETE FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id} AND `search_id` = '{$id}' AND `search_type` = '{$type}'");
	}
	$query_three = mysqli_query($sql_connect, "INSERT INTO " . T_RECENT_SEARCHES . " (`user_id`, `search_id`, `search_type`) VALUES ('{$user_id}', '{$id}', '{$type}')");
	if ($query_three) {
		return $id;
	}
}
function getRecentSearchs()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `search_id`, `search_type` FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id} AND `search_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = '{$user_id}') AND `search_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = '{$user_id}') ORDER BY `id` DESC LIMIT 10");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		if ($fetched_data['search_type'] == 'user') {
			$fetched_data2 = userData($fetched_data['search_id']);
		} elseif ($fetched_data['search_type'] == 'group') {
			$fetched_data2 = groupData($fetched_data['search_id']);
		} else {
			return false;
		}
		$data[] = $fetched_data2;
	}
	return $data;
}
function savePost($post_data = array())
{
	global $carovl, $sql_connect;
	if (empty($post_data)) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($post_data['post_id']);
	if (isPostSaved($post_id, $user_id)) {
		$query = mysqli_query($sql_connect, "DELETE FROM " . T_SAVED_POSTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
		if ($query) {
			return 'unsaved';
		}
	} else {
		$query = mysqli_query($sql_connect, "INSERT INTO " . T_SAVED_POSTS . " (`user_id`, `post_id`) VALUES ({$user_id}, {$post_id})");
		if ($query) {
			return 'saved';
		}
	}
}
function getSavedPosts($user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `post_id` FROM " . T_SAVED_POSTS . " WHERE `user_id` = {$user_id} ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$post_data = postData($fetched_data['post_id']);
		if (is_array($post_data)) {
			$data[] = $post_data;
		}
	}
	return $data;
}
// Report Post
function reportPost($post_data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($post_data)) {
		return false;
	}
	if (isPostExist($post_data['post_id']) === false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($post_data['post_id']);
	if (isPostReported($post_id, $user_id)) {
		$query_one = mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
		if ($query_one) {
			return 'unreport';
		}
	} else {
		$query_two = mysqli_query($sql_connect, "INSERT INTO " . T_REPORTS . " (`user_id`, `post_id`, `time`) VALUES ({$user_id}, {$post_id}, " . time() . ")");
		if ($query_two) {
			return 'report';
		}
	}
}
function getHashtagPosts($q, $after_post_id = 0, $limit = 5, $before_post_id = 0)
{
	global $sql_connect;
	$data = array();
	$search_query = str_replace('#', '', secureIt($q));
	$hashdata = getHashtag($search_query, false);
	if (is_array($hashdata) && count($hashdata) > 0) {
		$search_string = '#[' . $hashdata['id'] . ']';
		$query = "SELECT `id` FROM " . T_POSTS . " WHERE `post_text` LIKE '%{$search_string}%'";
		if (isset($after_post_id) && ! empty($after_post_id) && is_numeric($after_post_id)) {
			$after_post_id = secureIt($after_post_id);
			$query .= " AND `id` < {$after_post_id}";
		}
		if (isset($before_post_id) && ! empty($before_post_id) && is_numeric($before_post_id)) {
			$before_post_id = secureIt($before_post_id);
			$query .= " AND `id` > {$before_post_id}";
		}
		$query .= " ORDER BY `id` DESC LIMIT {$limit}";
		$sql = mysqli_query($sql_connect, $query);
		while ($fetched_data = mysqli_fetch_assoc($sql)) {
			$post_data = postData($fetched_data['id']);
			if (is_array($post_data)) {
				$data[] = $post_data;
			}
		}
	}
	return $data;
}
function clearRecentSearches()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$query_one = mysqli_query($sql_connect, "SELECT `id` FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id}");
	if (mysqli_num_rows($query_one) > 0) {
		$query_two = mysqli_query($sql_connect, "DELETE FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id}");
		if ($query_two) {
			return true;
		}
	}
}
// Remove Account
function deleteUser($user_id)
{
	global $carovl, $sql_connect, $cache;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	if (isAdmin() === false && isModerator() === false) {
		if ($carovl['user']['user_id'] != $user_id) {
			return false;
		}
	}
	if (isModerator() === true) {
		if (isAdmin($user_id)) {
			return false;
		}
	}
	$user = userData($user_id);
	$default_avatar = getDefaultAvatar($user['username']);
	$query_one = mysqli_query($sql_connect, "SELECT `avatar`, `cover` FROM " . T_USERS . " WHERE `user_id` = {$user_id}");
	$fetched_data = mysqli_fetch_assoc($query_one);
	if (isset($fetched_data['avatar']) && ! empty($fetched_data['avatar']) && $fetched_data['avatar'] != $default_avatar) {
		$explode = @end(explode('.', $fetched_data['avatar']));
		$explode2 = @explode('.', $fetched_data['avatar']);
		$media_one = $explode2[0] . '_avatar_full.' . $explode;
		@unlink(trim($media_one));
		@unlink($fetched_data['avatar']);
	}
	if (isset($fetched_data['cover']) && ! empty($fetched_data['cover']) && $fetched_data['cover'] != $carovl['user_default_cover']) {
		$explode = @end(explode('.', $fetched_data['cover']));
		$explode2 = @explode('.', $fetched_data['cover']);
		$media_one = $explode2[0] . '_cover_full.' . $explode;
		@unlink(trim($media_one));
		@unlink($fetched_data['cover']);
	}
	$query_two = mysqli_query($sql_connect, "SELECT `media` FROM " . T_MESSAGES . " WHERE `from_id` = {$user_id} OR `to_id` = {$user_id}");
	if (mysqli_num_rows($query_two) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_two)) {
			if (isset($fetched_data['media']) && ! empty($fetched_data['media'])) {
				@unlink($fetched_data['media']);
			}
		}
	}
	$query_three = mysqli_query($sql_connect, "SELECT `id`, `post_id`, `post_file` FROM " . T_POSTS . " WHERE `user_id` = {$user_id}");
	if (mysqli_num_rows($query_three) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_three)) {
			$delete_reports = mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `post_id` = " . $fetched_data['id']);
			$delete_reports .= mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `post_id` = " . $fetched_data['post_id']);
			if (isset($fetched_data['post_file']) && ! empty($fetched_data['post_file'])) {
				@unlink($fetched_data['post_file']);
			}
		}
	}
	if ($carovl['config']['cache_system'] == 1) {
		$cache->delete(md5($user_id) . '_U_Data.tmp');
		$query_four = mysqli_query($sql_connect, "SELECT `id`, `post_id` FROM " . T_POSTS . " WHERE `user_id` = {$user_id} OR `recipient_id` = {$user_id}");
		if (mysqli_num_rows($query_four) > 0) {
			while ($fetched_data = mysqli_fetch_assoc($query_two)) {
				$cache->delete(md5($fetched_data['id']) . '_P_Data.tmp');
				$cache->delete(md5($fetched_data['post_id']) . '_P_Data.tmp');
			}
		}
	}
	$query_five = mysqli_query($sql_connect, "SELECT `id` FROM " . T_GROUPS . " WHERE `user_id` = {$user_id}");
	if (mysqli_num_rows($query_five) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_five)) {
			$delete_groups = deleteGroup($fetched_data['id']);
		}
	}
	$query_six = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `user_id` = {$user_id} OR `recipient_id` = {$user_id}");
	if (mysqli_num_rows($query_six) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_six)) {
			$delete_posts = deletePost($fetched_data['id']);
		}
	}
	$query_seven = mysqli_query($sql_connect, "SELECT `id` FROM " . T_EVENTS . " WHERE `user_id` = {$user_id}");
	if (mysqli_num_rows($query_seven) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_seven)) {
			$delete_events = deleteEvent($fetched_data['id']);
		}
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_USERS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_RECENT_SEARCHES . " WHERE `user_id` = {$user_id} OR `search_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} OR `following_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_MESSAGES . " WHERE `from_id` = {$user_id} OR `to_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `notifier_id` = {$user_id} OR `recipient_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENTS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_LIKES . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_SAVED_POSTS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENT_LIKES . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_COMMENT_REPLIES . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_ACTIVITIES . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_VERIFICATION_REQUESTS . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_BLOCKS . " WHERE `blocker` = {$user_id} OR `blocked` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_USER_CHATS . " WHERE `conversation_user_id` = {$user_id} OR `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_ARTICLES . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_VIDEO_VIEWS . " WHERE `user_id` = {$user_id}");
	if ($query) {
		return true;
	}
}
// Edit Post
function updatePost($data = array())
{
	global $carovl, $sql_connect, $cache;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if ($data['post_id'] < 0 || empty($data['post_id']) || ! is_numeric($data['post_id'])) {
		return false;
	}
	if (empty($data['text'])) {
		return false;
	}
	$post_text = secureIt($data['text']);
	$user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($data['post_id']);
	if (isPostOwner($post_id, $user_id) === false) {
		return false;
	}
	if (! empty($post_text)) {
		if ($carovl['config']['max_characters'] > 0) {
			if (strlen($post_text) > $carovl['config']['max_characters']) {
				return false;
			}
		}
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
		preg_match_all($link_regex, $post_text, $matches);
		foreach ($matches[0] as $match) {
			$match_url = strip_tags($match);
			$syntax = '[a]' . urlencode($match_url) . '[/a]';
			$post_text = str_replace($match, $syntax, $post_text);
		}
		$mention_regex = '/@([A-Za-z0-9_]+)/i';
		preg_match_all($mention_regex, $post_text, $matches);
		foreach ($matches[1] as $match) {
			$match = secureIt($match);
			$match_user = userData(userIdFromUsername($match));
			$match_search = '@' . $match;
			$match_replace = '@[' . $match_user['user_id'] . ']';
			if (isset($match_user['user_id'])) {
				$post_text = str_replace($match_search, $match_replace, $post_text);
				$mentions[] = $match_user['user_id'];
			}
		}
	}
	$hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
	preg_match_all($hashtag_regex, $post_text, $matches);
	foreach ($matches[1] as $match) {
		if (! is_numeric($match)) {
			$hashdata = getHashtag($match);
			if (is_array($hashdata)) {
				$match_search = '#' . $match;
				$match_replace = '#[' . $hashdata['id'] . ']';
				if (mb_detect_encoding($match_search, 'ASCII', true)) {
					$post_text = preg_replace("/$match_search\b/i", $match_replace, $post_text);
				} else {
					$post_text = str_replace($match_search, $match_replace, $post_text);
				}
				$hashtag_query = mysqli_query($sql_connect, "UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id']);
			}
		}
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_POSTS . " SET `post_text` = '{$post_text}', `edited` = " . $data['edited'] . " WHERE `id` = {$post_id}");
	if ($query) {
		if ($carovl['config']['cache_system'] == 1) {
			$cache->delete(md5($data['post_id']) . '_P_Data.tmp');
		}
		if (isset($mentions) && is_array($mentions)) {
			foreach ($mentions as $mention) {
				$notification_data = array(
					'recipient_id' => $mention,
					'type' => 'post_mention',
					'post_id' => $post_id,
					'url' => 'index.php?page=post&id=' . $post_id
				);
				registerNotification($notification_data);
			}
		}
		$query_one = mysqli_query($sql_connect, "SELECT `post_text` FROM " . T_POSTS . " WHERE `id` = {$post_id}");
		$fetched_data = mysqli_fetch_assoc($query_one);
		$fetched_data['post_text'] = getMarkup($fetched_data['post_text']);
		$fetched_data['post_text'] = getEmoticons($fetched_data['post_text']);
		return $fetched_data['post_text'];
	}
}
// Accept Follow Request
function acceptFollowRequest($following_id = 0, $follower_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($following_id) || empty($following_id) || ! is_numeric($following_id) || $following_id < 1) {
		return false;
	}
	if (! isset($follower_id) || empty($follower_id) || ! is_numeric($follower_id) || $follower_id < 1) {
		return false;
	}
	$following_id = secureIt($following_id);
	$follower_id = secureIt($follower_id);
	if (isFollowRequested($following_id, $follower_id) === false) {
		return false;
	}
	$user = userData($follower_id);
	if (empty($user['user_id'])) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_FOLLOWERS . " SET `active` = '1' WHERE `following_id` = {$follower_id} AND `follower_id` = {$following_id} AND `active` = '0'");
	if ($query) {
		$notification_data = array(
			'recipient_id' => $following_id,
			'type' => 'accepted_request',
			'url' => 'index.php?page=timeline&u=' . $user['username']
		);
		if (registerNotification($notification_data) === true) {
			return true;
		} else {
			return false;
		}
	}
}
// Reject Follow Request
function rejectFollowRequest($following_id = 0, $follower_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($following_id) || empty($following_id) || ! is_numeric($following_id) || $following_id < 1) {
		return false;
	}
	if (! isset($follower_id) || empty($follower_id) || ! is_numeric($follower_id) || $follower_id < 1) {
		return false;
	}
	$following_id = secureIt($following_id);
	$follower_id = secureIt($follower_id);
	if (isFollowRequested($following_id, $follower_id) === false) {
		return false;
	} else {
		$query = mysqli_query($sql_connect, "DELETE FROM " . T_FOLLOWERS . " WHERE `following_id` = {$follower_id} AND `follower_id` = {$following_id}");
		if ($query) {
			return true;
		}
	}
}
// ----Events-----

// Register Event
function registerEvent($event_data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$fields = '`' . implode('`, `', array_keys($event_data)) . '`';
	$data = '\'' . implode('\', \'', $event_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_EVENTS . " ({$fields}) VALUES ({$data})");
	if ($query) {
		$id = mysqli_insert_id($sql_connect);
		$register_post = registerPost(array(
			'user_id' => secureIt($carovl['user']['user_id']),
			'time' => time(),
			'post_privacy' => '0',
			'event_id' => $id
		));
		return $id;
	}
	return false;
}
// Event Data
function eventData($id = false)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false || ! $id || ! is_numeric($id)) {
		return false;
	}
	$current_time = secureIt(time());
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_EVENTS . " WHERE `id` = {$id}");
	$fetched_data = mysqli_fetch_assoc($query);
	if (! empty($fetched_data)) {
		$startTimeObj = new DateTime($fetched_data['start_date'] . " " . $fetched_data['start_time']);
		$start_time = $startTimeObj->getTimeStamp();
		$endTimeObj = new DateTime($fetched_data['end_date'] . " " . $fetched_data['end_time']);
		$end_time = $endTimeObj->getTimeStamp();
		$fetched_data['event_time'] = date('l, j M h:i A', $start_time) . ' - ' . date('j M h:i A', $end_time);
		$hour = 60 * 60;
		$three_days = 60 * 60 * 24 * 3;
		$week = 60 * 60 * 24 * 7;
		if ($start_time < $current_time) {
			$fetched_data['time_remaining'] = $carovl['lang']['happening_now'];
		} elseif (($current_time + $hour) > $start_time) {
			$mins_remaining = round(($start_time - $current_time) / 60);
			$fetched_data['time_remaining'] = str_replace('{min_count}', $mins_remaining, $carovl['lang']['event_in_minutes']);
		} elseif (($current_time + $three_days) > $start_time) {
			$hours_remaining = ceil((($start_time - $current_time) / 60) / 60);
			$fetched_data['time_remaining'] = str_replace('{hours_count}', $hours_remaining, $carovl['lang']['event_in_hours']);
		} elseif (($current_time + $week) > $start_time) {
			$fetched_data['time_remaining'] = date('l h:i A', $start_time);
		} else {
			$fetched_data['time_remaining'] = str_replace('{event_time}', $start_time, $carovl['lang']['on_event_time']);
		}
		$fetched_data['publisher'] = userData($fetched_data['user_id']);
		$fetched_data['cover'] = getMedia($fetched_data['cover']);
		$fetched_data['owner'] = isEventOwner($fetched_data['id']);
		$fetched_data['url'] = seoLink('index.php?page=view-event&id=' . $fetched_data['id']);
		return $fetched_data;
	}
	return array();
}
// Update Event
function updateEvent($id = 0, $update_data = array())
{
	global $carovl, $sql_connect;
	$update = array();
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	if (empty($id)) {
		return false;
	}
	$id = secureIt($id);
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . secureIt($data, 0) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_EVENTS . " SET {$implode} WHERE `id` = {$id}");
	return $query;
}
// Check Event Owner
function isEventOwner($id, $user = false, $admin = true)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false || ! $id) {
		return false;
	}
	$user = ($user && is_numeric($user)) ? $user : $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_EVENTS . " WHERE `id` = {$id}");
	$fetched_data = mysqli_fetch_assoc($query);
	$result = false;
	if (! empty($fetched_data)) {
		if ($fetched_data['user_id'] == $user) {
			if ($admin == true) {
				if (isAdmin($user)) {
					$result = true;
				}
			}
			$result = true;
		}
	}
	return $result;
}
// Count Going Event
function countEventAction($action = '', $event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	$action_array = array(
		'going',
		'interested',
		'invited'
	);
	if (empty($action)) {
		return false;
	}
	if (! in_array($action, $action_array)) {
		return false;
	}
	$action = secureIt($action);
	$event_id = secureIt($event_id);
	$user_id = $carovl['user']['user_id'];
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND `action` = '{$action}'");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function getGoingButton($event_id = 0)
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($event_id) || ! is_numeric($event_id) || $event_id < 0) {
		return false;
	}
	if (isEventOwner($event_id, false, false)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$event = $carovl['going'] = eventData($event_id);
	if (! isset($carovl['going']['id'])) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$going = 'buttons/going';
	$not_going = 'buttons/not-going';
	if (eventGoingExist($event_id) === true) {
		return loadPage($not_going);
	} else {
		return loadPage($going);
	}
}
function getInterestedButton($event_id = 0)
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($event_id) || ! is_numeric($event_id) || $event_id < 0) {
		return false;
	}
	if (isEventOwner($event_id, false, false)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$event = $carovl['interested'] = eventData($event_id);
	if (! isset($carovl['interested']['id'])) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$interested = 'buttons/interested';
	$not_interested = 'buttons/not-interested';
	if (eventInterestedExist($event_id) === true) {
		return loadPage($not_interested);
	} else {
		return loadPage($interested);
	}
}
function eventGoingExist($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$user_id = $carovl['user']['user_id'];
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND `user_id` = {$user_id} AND `action` = 'going'");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = $fetched_data;
	}
	if (count($data) > 0) {
		return true;
	}
	return false;
}
function eventInterestedExist($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$user_id = $carovl['user']['user_id'];
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND `user_id` = {$user_id} AND `action` = 'interested'");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = $fetched_data;
	}
	if (count($data) > 0) {
		return true;
	}
	return false;
}
function eventInvitedExist($event_id, $user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	if (! $user_id || ! is_numeric($user_id)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$user_id = secureIt($user_id);
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND `user_id` = {$user_id}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = $fetched_data;
	}
	if (count($data) > 0) {
		return true;
	}
	return false;
}
function searchFollowers($user_id, $filter = '', $limit = 10, $event_id = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (empty($event_id)) {
		return false;
	}
	$user_id = secureIt($user_id);
	$filter = secureIt($filter);
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE `user_id` IN (SELECT `follower_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` <> {$user_id} AND `following_id` = {$user_id} AND `active` = '1') AND `active` = '1'";
	if (! empty($filter)) {
		$query .= " AND `username` LIKE '%{$filter}%'";
	}
	$query .= " AND `user_id` NOT IN (SELECT `user_id` FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND (`action` = 'going' || `action` = 'invited'))";
	$query .= " AND `user_id` NOT IN (SELECT `user_id` FROM " . T_EVENTS . " WHERE `id` = {$event_id})";
	$query .= " LIMIT {$limit}";
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
// Register User Invite to The Event
function registerEventInvite($user_id, $event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id)) {
		return false;
	}
	if (empty($event_id) || ! is_numeric($user_id)) {
		return false;
	}
	if (! isEventOwner($event_id, $user_id) && addEventInvitedUsers($event_id, $user_id)) {
		$notification_data = array(
			'recipient_id' => $user_id,
			'type' => 'invited_event',
			'event_id' => $event_id,
			'url' => 'index.php?page=view-event&id=' . $event_id
		);
		registerNotification($notification_data);
		return true;
	}
	return false;
}
function addEventInvitedUsers($event_id, $user_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	if (! $user_id || ! is_numeric($user_id)) {
		return false;
	}
	if (eventInvitedExist($event_id, $user_id)) {
		return false;
	}
	$invited_id = secureIt($user_id);
	$inviter_id = $carovl['user']['user_id'];
	$event_id = secureIt($event_id);
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_EVENTS_ACTION . " (`event_id`, `user_id`, `inviter_id`, `action`) VALUES ({$event_id}, {$invited_id}, {$inviter_id}, 'invited')");
	return $query;
}
// Register Going Event
function addEventGoingUsers($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	if (eventGoingExist($event_id)) {
		return false;
	}
	$user_id = $carovl['user']['user_id'];
	$event_id = secureIt($event_id);
	$event_data = eventData($event_id);
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_EVENTS_ACTION . " (`event_id`, `user_id`, `action`) VALUES ({$event_id}, {$user_id}, 'going')");
	if ($query) {
		$delete_invite = mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$event_id} AND `user_id` = {$user_id} AND `action` = 'invited'");
		$notification_data = array(
			'recipient_id' => $event_data['user_id'],
			'type' => 'going_event',
			'event_id' => $event_id,
			'url' => 'index.php?page=timeline&u=' . $carovl['user']['username']
		);
		registerNotification($notification_data);
	}
	return $query;
}
// Register Interested Event
function addEventInterestedUsers($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	if (eventInterestedExist($event_id)) {
		return false;
	}
	$user_id = $carovl['user']['user_id'];
	$event_id = secureIt($event_id);
	$event_data = eventData($event_id);
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_EVENTS_ACTION . " (`event_id`, `user_id`, `action`) VALUES ({$event_id}, {$user_id}, 'interested')");
	if ($query) {
		$notification_data = array(
			'recipient_id' => $event_data['user_id'],
			'type' => 'interested_event',
			'event_id' => $event_id,
			'url' => 'index.php?page=timeline&u=' . $carovl['user']['username']
		);
		registerNotification($notification_data);
	}
	return $query;
}
// Unset Going Event
function unsetEventGoingUsers($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND `event_id` = {$event_id} AND `action` = 'going'");
	if ($query) {
		return true;
	}
	return false;
}
// Unset Interested Event
function unsetEventInterestedUsers($event_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! $event_id || ! is_numeric($event_id)) {
		return false;
	}
	$event_id = secureIt($event_id);
	$user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND `event_id` = {$event_id} AND `action` = 'interested'");
	if ($query) {
		return true;
	}
	return false;
}
function deleteEvent($id = false)
{
	global $carovl, $sql_connect;
	$query = false;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isEventOwner($id)) {
		if (isAdmin() == false) {
			return false;
		}
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS . " WHERE `id` = {$id}");
	if ($query) {
		$query = mysqli_query($sql_connect, "DELETE FROM " . T_EVENTS_ACTION . " WHERE `event_id` = {$id}");
		$query = mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `event_id` = {$id}");
		$query_two = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `event_id` = {$id}");
		if (mysqli_num_rows($query_two) > 0) {
			while ($fetched_data = mysqli_fetch_assoc($query_two)) {
				$delete_posts = deletePost($fetched_data['id']);
			}
		}
		return true;
	}
	return false;
}
function getEvents($args = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$options = array(
		'offset' => 0,
		'limit' => 10,
		'is_admin' => 0
	);
	$args = array_merge($options, $args);
	$subquery = "";
	$total = "";
	$offset = $args['offset'];
	$limit = $args['limit'];
	$user_id = $carovl['user']['user_id'];
	if ($offset > 0) {
		$subquery = " AND `id` < {$offset} AND `id` <> {$offset}";
	}
	if ($limit && is_numeric($limit)) {
		$total = " LIMIT {$limit}";
	}
	$query = "SELECT * FROM " . T_EVENTS . " WHERE `end_date` >= CURDATE()";
	if (empty($args['is_admin'])) {
		$query .= " AND `id` NOT IN (SELECT `event_id` FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND (`action` = 'going' OR `action` = 'interested')) {$subquery} ORDER BY `id` DESC {$total}";
	}
	$sql = mysqli_query($sql_connect, $query);
	$data = array();
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = eventData($fetched_data['id']);
	}
	return $data;
}
function getGoingEvents($offset = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$subquery = "";
	if ($offset > 0) {
		$subquery = " AND `event_id` < {$offset} AND `event_id` <> {$offset}";
	}
	$user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "SELECT `event_id` FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND `action` = 'going' ORDER BY `event_id` DESC LIMIT 10");
	$data = array();
	if ($query && ! empty($query)) {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$data[] = eventData($fetched_data['event_id']);
		}
	}
	return $data;
}
function getInterestedEvents($offset = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$subquery = "";
	if ($offset > 0) {
		$subquery = " AND `event_id` < {$offset} AND `event_id` <> {$offset}";
	}
	$user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "SELECT `event_id` FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND `action` = 'interested' ORDER BY `event_id` DESC LIMIT 10");
	$data = array();
	if ($query && ! empty($query)) {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$data[] = eventData($fetched_data['event_id']);
		}
	}
	return $data;
}
function getInvitedEvents($offset = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$subquery = "";
	if ($offset > 0) {
		$subquery = " AND `event_id` < {$offset} AND `event_id` <> {$offset}";
	}
	$user_id = $carovl['user']['user_id'];
	$query = mysqli_query($sql_connect, "SELECT `event_id` FROM " . T_EVENTS_ACTION . " WHERE `user_id` = {$user_id} AND `action` = 'invited' ORDER BY `event_id` DESC LIMIT 10");
	$data = array();
	if ($query && ! empty($query)) {
		while ($fetched_data = mysqli_fetch_assoc($query)) {
			$data[] = eventData($fetched_data['event_id']);
		}
	}
	return $data;
}
function getPastEvents()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_EVENTS . " WHERE `end_date` < CURDATE() ORDER BY `id` DESC");
	$data = array();
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = eventData($fetched_data['id']);
	}
	return $data;
}

// -----Groups-----
function registerGroup($registration_data)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($registration_data)) {
		return false;
	}
	$registration_data['registered'] = date('n') . '/' . date('Y');
	$fields = '`' . implode('`, `', array_keys($registration_data)) . '`';
	$data = '\'' . implode('\', \'', $registration_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_GROUPS . " ({$fields}) VALUES ({$data})");
	if ($query) {
		$id = mysqli_insert_id($sql_connect);
		registerGroupJoin($id, $carovl['user']['user_id']);
		return true;
	} else {
		return false;
	}
}
function registerGroupJoin($group_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($group_id) || empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	if (! isset($user_id) || empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$user_id = secureIt($user_id);
	$group_owner = getUserIdFromGroupId($group_id);
	$active = 1;
	if (isGroupJoined($group_id, $user_id) === true) {
		return false;
	}
	$group = groupData($group_id);
	if ($group['join_privacy'] == 1) {
		$active = 0;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_GROUP_MEMBERS . " (`user_id`, `group_id`, `active`, `time`) VALUES ({$user_id}, {$group_id}, '{$active}', " . time() . ")");
	if ($query) {
		if ($active == 1) {
			$notification_data = array(
				'recipient_id' => $group_owner,
				'notifier_id' => $user_id,
				'type' => 'joined_group',
				'group_id' => $group_id,
				'url' => 'index.php?page=timeline&u=' . $group['group_name']
			);
			registerNotification($notification_data);
		} elseif ($active == 0) {
			$notification_data = array(
				'recipient_id' => $group_owner,
				'notifier_id' => $user_id,
				'type' => 'requested_join_group',
				'group_id' => $group_id,
				'url' => 'index.php?page=group-setting&group=' . $group['group_name'] . '&tab=join-requests'
			);
			registerNotification($notification_data);
		}
	}
	return true;
}
function getUserIdFromGroupId($group_id = 0)
{
	global $sql_connect;
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_GROUPS . " WHERE `id` = {$group_id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		return $fetched_data['user_id'];
	}
}
function isGroupJoined($group_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 0) {
		return false;
	}
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 0) {
		$user_id = secureIt($carovl['user']['user_id']);
	}
	$group_id = secureIt($group_id);
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$user_id} AND `group_id` = {$group_id} AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
function myGroups()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$data = array();
	$user_id = secureIt($carovl['user']['user_id']);
	$query = mysqli_query($sql_connect, "SELECT `group_id` FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$user_id}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		if (is_array($fetched_data)) {
			$data[] = groupData($fetched_data['group_id']);
		}
	}
	return $data;
}
function groupData($group_id = 0)
{
	global $carovl, $sql_connect, $cache;
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = "SELECT * FROM " . T_GROUPS . " WHERE `id` = {$group_id}";
	$hashed_group_id = md5($group_id);
	if ($carovl['config']['cache_system'] == 1) {
		$fetched_data = $cache->read($hashed_group_id . '_G_Data.tmp');
		if (empty($fetched_data)) {
			$sql = mysqli_query($sql_connect, $query);
			$fetched_data = mysqli_fetch_assoc($sql);
			$cache->write($hashed_group_id . '_G_Data.tmp', $fetched_data);
		}
	} else {
		$sql = mysqli_query($sql_connect, $query);
		$fetched_data = mysqli_fetch_assoc($sql);
	}
	if (empty($fetched_data)) {
		return false;
	}
	$fetched_data['group_id'] = $fetched_data['id'];
	$fetched_data['avatar'] = getMedia($fetched_data['avatar']);
	$fetched_data['cover'] = getMedia($fetched_data['cover']);
	$fetched_data['url'] = seoLink('index.php?page=timeline&u=' . $fetched_data['group_name']);
	$fetched_data['name'] = $fetched_data['group_title'];
	$fetched_data['type'] = 'group';
	$fetched_data['username'] = $fetched_data['group_name'];
	return $fetched_data;
}
function groupIdFromGroupname($group_name = '')
{
	global $sql_connect;
	if (empty($group_name)) {
		return false;
	}
	$group_name = secureIt($group_name);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_GROUPS . " WHERE `group_name` = '{$group_name}'");
	return sqlResult($query, 0, 'id');
}
function isGroupOwner($group_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 0) {
		return false;
	}
	if (empty($user_id)) {
		$user_id = secureIt($carovl['user']['user_id']);
	}
	if (! is_numeric($user_id) || $user_id < 0) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_GROUPS . " WHERE `id` = {$group_id} AND `user_id` = {$user_id} AND `active` = '1'");
	return (sqlResult($query, '0') == 1) ? true : false;
}
function getJoinButton($group_id = 0)
{
	global $carovl;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 0) {
		return false;
	}
	if (isGroupOwner($group_id)) {
		return false;
	}
	$group_id = secureIt($group_id);
	$group = $carovl['join'] = groupData($group_id);
	if (! isset($carovl['join']['id'])) {
		return false;
	}
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$join_button = 'buttons/join';
	$leave_button = 'buttons/leave';
	$requested_button = 'buttons/join-requested';
	if (isGroupJoined($group_id, $logged_user_id) === true) {
		return loadPage($leave_button);
	} else {
		if (isJoinRequested($group_id) === true) {
			return loadPage($requested_button);
		} else {
			return loadPage($join_button);
		}
	}
}
function isJoinRequested($group_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($user_id);
	if (! isset($user_id) || empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		$user_id = secureIt($carovl['user']['user_id']);
	}
	if (! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id} AND `user_id` = {$user_id} AND `active` = '0'");
	if (mysqli_num_rows($query) > 0) {
		return true;
	}
}
function leaveGroup($group_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($group_id) || empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	if (! isset($user_id) || empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$user_id = secureIt($user_id);
	$active = 1;
	if (isGroupJoined($group_id, $user_id) === false && isJoinRequested($group_id, $user_id) === false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$user_id} AND `group_id` = {$group_id}");
	if ($query) {
		return true;
	}
}
function canBeOnGroup($group_id)
{
	global $sql_connect;
	if (empty($group_id)) {
		return false;
	}
	$group_id = secureIt($group_id);
	if (isGroupOwner($group_id)) {
		return true;
	}
	$group = groupData($group_id);
	if (empty($group)) {
		return false;
	}
	if ($group['privacy'] == 1) {
		if (isGroupJoined($group_id) === true) {
			return true;
		}
	} elseif ($group['privacy'] == 0) {
		return true;
	} else {
		return false;
	}
}
function countGroupMembers($group_id = 0)
{
	global $carovl, $sql_connect;
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`group_id`) AS `count` FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id} AND `active` = '1'");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function isGroupExist($group_name = '')
{
	global $sql_connect;
	if (empty($group_name)) {
		return false;
	}
	$group_name = secureIt($group_name);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_GROUPS . " WHERE `group_name` = '{$group_name}' AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
function updateGroupData($group_id = 0, $update_data)
{
	global $carovl, $sql_connect, $cache;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 0) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	$group_id = secureIt($group_id);
	if (isAdmin() === false || isModerator() === false) {
		if (isGroupOwner($group_id) === false) {
			return false;
		}
	}
	$update = array();
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . secureIt($data, 0) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_GROUPS . " SET {$implode} WHERE `id` = {$group_id}");
	if ($carovl['config']['cache_system'] == 1) {
		$cache->delete(md5($group_id) . '_G_Data.tmp');
	}
	if ($query) {
		return true;
	} else {
		return false;
	}
}
function getGroupMembers($group_id = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id} AND `active` = '1'");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
function getGroupRequests($group_id = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id} AND `active` = '0' ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
function rejectJoinRequest($user_id, $group_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($user_id) || empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (! isset($group_id) || empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$group_id = secureIt($group_id);
	if (isGroupOwner($group_id) === false) {
		return false;
	}
	if (isJoinRequested($group_id, $user_id) === false) {
		return false;
	}
	if (isGroupJoined($group_id, $user_id) === true) {
		return false;
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_GROUP_MEMBERS . " WHERE `user_id` = {$user_id} AND `group_id` = {$group_id} AND `active` = '0'");
	if ($query) {
		return true;
	}
}
function acceptJoinRequest($user_id, $group_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($user_id) || empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (! isset($group_id) || empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$group_id = secureIt($group_id);
	if (isGroupOwner($group_id) === false) {
		return false;
	}
	if (isJoinRequested($group_id, $user_id) === false) {
		return false;
	}
	if (isGroupJoined($group_id, $user_id) === true) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_GROUP_MEMBERS . " SET `active` = '1' WHERE `user_id` = {$user_id} AND `group_id` = {$group_id} AND `active` = '0'");
	if ($query) {
		$group = groupData($group_id);
		$notification_data = array(
			'recipient_id' => $user_id,
			'notifier_id' => $group['user_id'],
			'type' => 'accepted_join_request',
			'group_id' => $group_id,
			'url' => 'index.php?page=timeline&u=' . $group['username']
		);
		registerNotification($notification_data);
		return true;
	}
}
function deleteGroup($group_id = 0)
{
	global $carovl, $sql_connect, $cache;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($group_id) || ! is_numeric($group_id) || $group_id < 1) {
		return false;
	}
	$group_id = secureIt($group_id);
	if (isAdmin() === false && isModerator() === false) {
		if (isGroupOwner($group_id) === false) {
			return false;
		}
	}
	$query_one = mysqli_query($sql_connect, "SELECT `avatar`, `cover` FROM " . T_GROUPS . " WHERE `id` = {$group_id}");
	$fetched_data = mysqli_fetch_assoc($query_one);
	if (isset($fetched_data['avatar']) && ! empty($fetched_data['avatar']) && $fetched_data['avatar'] != $carovl['group_default_avatar']) {
		@unlink($fetched_data['avatar']);
	}
	if (isset($fetched_data['cover']) && ! empty($fetched_data['cover']) && $fetched_data['cover'] != $carovl['user_default_cover']) {
		@unlink($fetched_data['cover']);
	}
	$query_two = mysqli_query($sql_connect, "SELECT `post_file` FROM " . T_POSTS . " WHERE `group_id` = {$group_id}");
	if (mysqli_num_rows($query_two) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_two)) {
			if (isset($fetched_data['post_file']) && ! empty($fetched_data['post_file'])) {
				@unlink($fetched_data['post_file']);
			}
		}
	}
	$query_three = mysqli_query($sql_connect, "SELECT `id`, `post_id` FROM " . T_POSTS . " WHERE `group_id` = {$group_id}");
	if (mysqli_num_rows($query_three) > 0) {
		while ($fetched_data = mysqli_fetch_assoc($query_three)) {
			$delete_posts = deletePost($fetched_data['id']);
			$delete_posts = deletePost($fetched_data['post_id']);
		}
	}
	if ($carovl['config']['cache_system'] == 1) {
		$cache->delete(md5($group_id) . '_G_Data.tmp');
		$query_four = mysqli_query($sql_connect, "SELECT `id`, `post_id` FROM " . T_POSTS . " WHERE `group_id` = {$group_id}");
		if (mysqli_num_rows($query_four) > 0) {
			while ($fetched_data = mysqli_fetch_assoc($query_four)) {
				$cache->delete(md5($fetched_data['id']) . '_P_Data.tmp');
				$cache->delete(md5($fetched_data['post_id']) . '_P_Data.tmp');
			}
		}
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_GROUPS . " WHERE `id` = {$group_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_GROUP_MEMBERS . " WHERE `group_id` = {$group_id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `group_id` = {$group_id}");
	if ($query) {
		return true;
	}
}
function searchPosts($id = 0, $q = '', $limit = 20, $type = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	if ($type == 'group') {
		$subquery = " AND `group_id` = {$id}";
	} elseif ($type == 'user') {
		$subquery = " AND `user_id` = {$id}";
	} else {
		return false;
	}
	$data = array();
	$q = secureIt($q);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `post_text` LIKE '%{$q}%' {$subquery} ORDER BY `id` DESC LIMIT {$limit}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$posts = postData($fetched_data['id']);
		if (is_array($posts)) {
			$data[] = $posts;
		}
	}
	return $data;
}
function registerShare($post_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (! isset($post_id) || empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = getUserIdFromPostId($post_id);
	$logged_user_id = secureIt($carovl['user']['user_id']);
	$post = postData($post_id);
	if (empty($user_id)) {
		return false;
	}
	$text = '';
	$type2 = '';
	if (isset($post['post_text']) && ! empty($post['post_text'])) {
		$text = substr($post['post_text'], 0, 10);
	}
	if (isset($post['post_file']) && ! empty($post['post_file'])) {
		if (strpos($post['post_file'], '_image') !== false) {
			$type2 = 'post_image';
		} elseif (strpos($post['post_file'], '_video') !== false) {
			$type2 = 'post_video';
		} elseif (strpos($post['post_file'], '_audio') !== false) {
			$type2 = 'post_audio';
		} else {
			$type2 = 'post_file';
		}
	}
	if (isPostShared($post_id, $logged_user_id)) {
		$query = "DELETE FROM " . T_POSTS . " WHERE `post_id` = {$post_id} AND `user_id` = {$logged_user_id} AND `post_share` = 1";
		$query_two = mysqli_query($sql_connect, "DELETE FROM " . T_NOTIFICATIONS . " WHERE `post_id` = {$post_id} AND `recipient_id` = {$user_id} AND `type` = 'share_post'");
		$delete_activity = deleteActivity($post_id, $logged_user_id, 'shared_post');
		$sql = mysqli_query($sql_connect, $query);
		if ($sql) {
			return 'unshare';
		}
	} else {
		$query = mysqli_query($sql_connect, "INSERT INTO " . T_POSTS . " (`user_id`, `post_id`, `time`, `post_share`) VALUES ({$logged_user_id}, {$post_id}, " . time() . ", 1)");
		$id = mysqli_insert_id($sql_connect);
		if ($query) {
			$activity_data = array(
				'post_id' => $post_id,
				'user_id' => $logged_user_id,
				'post_user_id' => $user_id,
				'activity_type' => 'shared_post'
			);
			$register_activity = registerActivity($activity_data);
			$notification_data = array(
				'recipient_id' => $user_id,
				'post_id' => $post_id,
				'type' => 'share_post',
				'text' => $text,
				'type2' => $type2,
				'url' => 'index.php?page=post&id=' . $id
			);
			registerNotification($notification_data);
			return 'share';
		}
	}
}

// -----Admin Area-----
function getAllUsers($limit = '', $type = '', $filter = array(), $after_user_id = '')
{
	global $carovl, $sql_connect;
	$data = array();
	$query = "SELECT `user_id` FROM " . T_USERS . " WHERE `type` = 'user'";
	if (isset($filter) && ! empty($filter)) {
		if (! empty($filter['query'])) {
			$query .= " AND ((`email` LIKE '%" . secureIt($filter['query']) . "%') OR (`username` LIKE '%" . $filter['query'] . "%') OR CONCAT(`first_name`, ' ', `last_name`) LIKE '%" . $filter['query'] . "%')";
		}
		if (isset($filter['status']) && $filter['status'] != 'all') {
			$query .= " AND `active` = '" . $filter['status'] . "'";
		}
	}
	if (! empty($after_user_id) && is_numeric($after_user_id) && $after_user_id > 0) {
		$query .= " AND `user_id` < " . secureIt($after_user_id);
	}
	$query .= " ORDER BY `user_id` DESC";
	if (isset($limit) && ! empty($limit)) {
		$query .= " LIMIT {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$user = userData($fetched_data['user_id']);
		$data[] = $user;
	}
	return $data;
}
function getAllPosts($posts = array('limit' => 10, 'after_post_id' => 0))
{
	global $carovl, $sql_connect;
	$data = array();
	$subquery = "";
	$limit = secureIt($posts['limit']);
	if (isset($posts['after_post_id']) && ! empty($posts['after_post_id']) && $posts['after_post_id'] > 0) {
		$after_post_id = secureIt($posts['after_post_id']);
		$subquery = " WHERE `id` < {$after_post_id}";
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " {$subquery} ORDER BY `id` DESC LIMIT {$limit}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = postData($fetched_data['id'], 'admin');
	}
	return $data;
}
function getAllArticles($articles = array())
{
	global $carovl, $sql_connect;
	$data = array();
	$subquery = "";
	$limit = secureIt($articles['limit']);
	if (isset($articles['after_article_id']) && ! empty($articles['after_article_id']) && $articles['after_article_id'] > 0) {
		$after_article_id = secureIt($articles['after_article_id']);
		$subquery = " WHERE `id` < {$after_article_id}";
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ARTICLES . " {$subquery} ORDER BY `id` DESC LIMIT {$limit}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = articleData($fetched_data['id']);
	}
	return $data;
}
function getAllEvents($events = array('limit' => 10))
{
	global $carovl, $sql_connect;
	$data = array();
	$subquery = "";
	$limit = secureIt($events['limit']);
	if (isset($events['after_event_id']) && ! empty($events['after_event_id']) && $events['after_event_id'] > 0) {
		$after_event_id = secureIt($events['after_event_id']);
		$subquery = " WHERE `id` < {$after_event_id}";
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_EVENTS . " {$subquery} ORDER BY `id` DESC LIMIT {$limit}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = eventData($fetched_data['id']);
	}
	return $data;
}
function deleteArticle($id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($id) || ! is_numeric($id) || $id < 0) {
		return false;
	}
	if (isArticleOwner($id) === false) {
		if (isAdmin() === false) {
			return false;
		}
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_ARTICLES . " WHERE `id` = {$id}");
	if ($query) {
		$query_two = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `article_id` = {$id}");
		$fetched_data = mysqli_fetch_assoc($query_two);
		$delete_posts = deletePost($fetched_data['id']);
	}
	return $query;
}
function getAllGroups($limit = '', $after_group_id = '')
{
	global $carovl, $sql_connect;
	$data = array();
	$query = "SELECT `id` FROM " . T_GROUPS;
	if (! empty($after_group_id) && is_numeric($after_group_id) && $after_group_id > 0) {
		$query .= " WHERE `id` < " . secureIt($after_group_id);
	}
	$query .= " ORDER BY `id` DESC";
	if (isset($limit) && ! empty($limit)) {
		$query .= " LIMIT {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$group = groupData($fetched_data['id']);
		$group['members'] = countGroupMembers($fetched_data['id']) . ' ' . $carovl['lang']['members'];
		$group['owner'] = userData($group['user_id']);
		$data[] = $group;
	}
	return $data;
}
function countUnseenReports()
{
	global $sql_connect;
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `reports` FROM " . T_REPORTS . " WHERE `seen` = 0");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['reports'];
}
function updateSeenReports()
{
	global $sql_connect;
	$query = mysqli_query($sql_connect, "UPDATE " . T_REPORTS . " SET `seen` = 1 WHERE `seen` = 0");
	if ($query) {
		return true;
	}
}
function getAllReports()
{
	global $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_REPORTS . " ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$fetched_data['reporter'] = userData($fetched_data['user_id']);
		$fetched_data['story'] = postData($fetched_data['post_id']);
		$data[] = $fetched_data;
	}
	return $data;
}
function deleteReport($report_id = '')
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$report_id = secureIt($report_id);
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_REPORTS . " WHERE `id` = {$report_id}");
	if ($query) {
		return true;
	}
}
function addNewAnnouncement($text)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$text = mysqli_real_escape_string($sql_connect, $text);
	if (isAdmin($user_id) === false) {
		return false;
	}
	if (empty($text)) {
		return false;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_ANNOUNCEMENT . " (`text`, `time`, `active`) VALUES ('{$text}', " . time() . ", '1')");
	if ($query) {
		return mysqli_insert_id($sql_connect);
	}
}
function getAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_ANNOUNCEMENT . " WHERE `id` = {$id} ORDER BY `id` DESC");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		$fetched_data['text'] = getMarkup($fetched_data['text']);
		$fetched_data['text'] = getEmoticons($fetched_data['text']);
		return $fetched_data;
	}
}
function getActiveAnnouncements()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$data = array();
	if (isAdmin($user_id) === false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = getAnnouncement($fetched_data['id']);
	}
	return $data;
}
function getAnnouncementViews($id)
{
	global $carovl, $sql_connect;
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = {$id}");
	$fetched_data = mysqli_fetch_assoc($query);
	$fetched_data['views'] = $fetched_data['count'] . ' ' . $carovl['lang']['views'];
	return $fetched_data;
}
function deleteAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$id = secureIt($id);
	$user_id = secureIt($carovl['user']['user_id']);
	if (isAdmin($user_id) === false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_ANNOUNCEMENT . " WHERE `id` = {$id}");
	$query .= mysqli_query($sql_connect, "DELETE FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = {$id}");
	if ($query) {
		return true;
	}
}
function getInactiveAnnouncements()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	if (isAdmin($user_id) === false) {
		return false;
	}
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '0' ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = getAnnouncement($fetched_data['id']);
	}
	return $data;
}
function disableAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$id = secureIt($id);
	$user_id = secureIt($carovl['user']['user_id']);
	if (isAdmin($user_id) === false) {
		return false;
	}
	if (isActiveAnnouncement($id) === false) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_ANNOUNCEMENT . " SET `active` = '0' WHERE `id` = {$id}");
	if ($query) {
		return true;
	}
}
function isActiveAnnouncement($id)
{
	global $sql_connect;
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_ANNOUNCEMENT . " WHERE `id` = {$id} AND `active` = '1'");
	return (sqlResult($query, 0) == 1) ? true : false;
}
function enableAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$id = secureIt($id);
	$user_id = secureIt($carovl['user']['user_id']);
	if (isAdmin($user_id) === false) {
		return false;
	}
	if (isActiveAnnouncement($id) === true) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_ANNOUNCEMENT . " SET `active` = '1' WHERE `id` = {$id}");
	if ($query) {
		return true;
	}
}
function isThereAnnouncement()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' AND `id` NOT IN (SELECT `announcement_id` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `user_id` = {$user_id})");
	$fetched_data = mysqli_fetch_assoc($query);
	return ($fetched_data['count'] > 0) ? true : false;
}
function getHomeAnnouncements()
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' AND `id` NOT IN (SELECT `announcement_id` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `user_id` = {$user_id}) ORDER BY RAND() LIMIT 1");
	$fetched_data = mysqli_fetch_assoc($query);
	$data = getAnnouncement($fetched_data['id']);
	return $data;
}
function isViewedAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$id = secureIt($id);
	$user_id = secureIt($carovl['user']['user_id']);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = {$id} AND `user_id` = {$user_id}");
	return (sqlResult($query, 0) > 0) ? true : false;
}
function viewAnnouncement($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$id = secureIt($id);
	$user_id = secureIt($carovl['user']['user_id']);
	if (isActiveAnnouncement($id) === false) {
		return false;
	}
	if (isViewedAnnouncement($id) === true) {
		return false;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_ANNOUNCEMENT_VIEWS . " (`user_id`, `announcement_id`) VALUES ({$user_id}, {$id})");
	if ($query) {
		return true;
	}
}
function viewVideo($post_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($carovl['user']['user_id']);
	if (isVideoViewed($post_id) === true) {
		return false;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_VIDEO_VIEWS . " (`user_id`, `post_id`) VALUES ({$user_id}, {$post_id})");
	if ($query) {
		return true;
	}
}
function isVideoViewed($post_id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$post_id = secureIt($post_id);
	$user_id = secureIt($carovl['user']['user_id']);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) FROM " . T_VIDEO_VIEWS . " WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}");
	return (sqlResult($query, 0) > 0) ? true : false;
}
function countVideoViews($post_id)
{
	global $carovl, $sql_connect;
	$post_id = secureIt($post_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_VIDEO_VIEWS . " WHERE `post_id` = {$post_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	$fetched_data['views'] = $fetched_data['count'] . ' ' . $carovl['lang']['views'];
	return $fetched_data;
}
function countAllData($type)
{
	global $sql_connect;
	$table = T_USERS;
	$id = 'user_id';
	if ($type == 'users') {
		$table = T_USERS;
		$id = 'user_id';
	} elseif ($type == 'groups') {
		$table = T_GROUPS;
		$id = 'id';
	} elseif ($type == 'posts') {
		$table = T_POSTS;
		$id = 'id';
	} elseif ($type == 'comments') {
		$table = T_COMMENTS;
		$id = 'id';
	} elseif ($type == 'messages') {
		$table = T_MESSAGES;
		$id = 'id';
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`$id`) AS `count` FROM {$table}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function countOnlineUsers()
{
	global $sql_connect;
	$time = time() - 60;
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) AS `count` FROM " . T_USERS . " WHERE `lastseen` > {$time}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function countUserData($type)
{
	global $sql_connect;
	$subquery = '';
	if ($type == 'males') {
		$subquery = "`gender` = 'male'";
	} elseif ($type == 'females') {
		$subquery = "`gender` = 'female'";
	} elseif ($type == 'active') {
		$subquery = "`active` = '1'";
	} elseif ($type == 'inactive') {
		$subquery = "`active` <> '1'";
	}
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) AS `count` FROM " . T_USERS . " WHERE {$subquery}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function countPostData($type)
{
	global $sql_connect;
	$table = T_LIKES;
	$id = 'id';
	if ($type == 'likes') {
		$table = T_LIKES;
		$id = 'id';
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`$id`) AS `count` FROM {$table}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function getAllOnlineUsers()
{
	global $sql_connect;
	$time = time() - 60;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT `user_id` FROM " . T_USERS . " WHERE `lastseen` > {$time}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
function getAllUsersByType($type = 'all')
{
	global $sql_connect;
	$data = array();
	$query = "SELECT `user_id` FROM " . T_USERS;
	if ($type == 'active') {
		$query .= " WHERE `active` = '1'";
	} elseif ($type == 'inactive') {
		$query .= " WHERE `active` = '0' OR `active` = '2'";
	} elseif ($type == 'all') {
		$query .= "";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['user_id']);
	}
	return $data;
}
function getLatestPosts($data = array('after_post_id' => 0, 'limit' => 10))
{
	global $sql_connect;
	$subquery = " `id` > 0";
	if (! empty($data['after_post_id']) && is_numeric($data['after_post_id']) && $data['after_post_id'] > 0) {
		$data['after_post_id'] = secureIt($data['after_post_id']);
		$subquery = " `id` < " . $data['after_post_id'] . " AND `id` <> " . $data['after_post_id'];
	}
	$query = "SELECT * FROM " . T_POSTS . " WHERE {$subquery} AND `post_type` = 'post' AND `multi_image` = '0' AND `album_name` = '' AND `group_id` = 0 AND `event_id` = 0 AND `post_share` NOT IN (1) AND `user_id` NOT IN (SELECT `user_id` FROM " . T_USERS . " WHERE `follow_privacy` = '1')";
	if (empty($data['limit']) || ! is_numeric($data['limit']) || $data['limit'] < 1) {
		$data['limit'] = 10;
	}
	$limit = secureIt($data['limit']);
	$query .= "ORDER BY `id` DESC LIMIT {$limit}";
	$data = array();
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$post_data = postData($fetched_data['id']);
		if (is_array($post_data)) {
			$data[] = $post_data;
		}
	}
	return $data;
}
function getPolicy()
{
	global $sql_connect;
	$data = array();
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_POLICY);
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$data[$fetched_data['type']] = $fetched_data['text'];
	}
	return $data;
}
function followSuggestions($limit = 4)
{
	global $carovl, $sql_connect;
	if (! is_numeric($limit)) {
		return false;
	}
	$data = array();
	$user_id = secureIt($carovl['user']['user_id']);
	$query = "SELECT `user_id` AS `follow_suggestions` FROM " . T_USERS . " WHERE `active` = '1' AND `user_id` NOT IN (SELECT `blocked` FROM " . T_BLOCKS . " WHERE `blocker` = {$user_id}) AND `user_id` NOT IN (SELECT `blocker` FROM " . T_BLOCKS . " WHERE `blocked` = {$user_id}) AND `user_id` NOT IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id}) AND `user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id}) AND `active` = '1') AND `user_id` <> {$user_id}";
	if (isset($limit)) {
		$query .= " ORDER BY RAND() LIMIT {$limit}";
	}
	$sql = mysqli_query($sql_connect, $query);
	while ($fetched_data = mysqli_fetch_assoc($sql)) {
		$data[] = userData($fetched_data['follow_suggestions']);
	}
	return $data;
}
function countUserPosts($user_id)
{
	global $sql_connect;
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	$user_id = secureIt($user_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_POSTS . " WHERE `post_type` <> 'profile_picture_deleted' AND `group_id` = 0 AND `event_id` = 0 AND `user_id` = {$user_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function isAdsActive($type)
{
	global $sql_connect;
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_ADS . " WHERE `type` = '{$type}' AND `active` = '1'");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['count'];
}
function getAds($type, $admin = true)
{
	global $sql_connect;
	$type = secureIt($type);
	$query = "SELECT `code` FROM " . T_ADS . " WHERE `type` = '{$type}'";
	if ($admin === false) {
		$query .= " AND `active` = '1'";
	}
	$sql = mysqli_query($sql_connect, $query);
	$fetched_data = mysqli_fetch_assoc($sql);
	return $fetched_data['code'];
}
function updateAdsStatus($type)
{
	global $carovl, $sql_connect;
	$type = secureIt($type);
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (isAdmin() === false && isModerator() === false) {
		return false;
	}
	if (isAdsActive($type)) {
		$query = mysqli_query($sql_connect, "UPDATE " . T_ADS . " SET `active` = '0' WHERE `type` = '{$type}'");
		return 'inactive';
	} else {
		$query = mysqli_query($sql_connect, "UPDATE " . T_ADS . " SET `active` = '1' WHERE `type` = '{$type}'");
		return 'active';
	}
}
function updateAdsText($update_data = array())
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (isAdmin() === false && isModerator() === false) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	if (empty($update_data['type'])) {
		return false;
	}
	$type = secureIt($update_data['type']);
	$update = array();
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . mysqli_real_escape_string($sql_connect, $data) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_ADS . " SET {$implode} WHERE `type` = '{$type}'");
	if ($query) {
		return true;
	}
}
// Send SMS to user's phone number
function sendSmsMessage($to, $message)
{
	global $carovl, $sql_connect;
	if (empty($to)) {
		return false;
	}
	if ($carovl['config']['sms_provider'] == 'twilio') {
		$account_sid = $carovl['config']['twilio_sms_username'];
		$auth_token = $carovl['config']['twilio_sms_password'];
		$to = secureIt($to);
		$client = new Client($account_id, $auth_token);
		try {
			$send = $client->account->messages->create($to, array(
				'from' => $carovl['config']['twilio_sms_phone_number'],
				'body' => $message
			));
			if ($send) {
				return true;
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
		return false;
	} elseif ($carovl['config']['sms_provider'] == 'bulksms') {
		$username = $carovl['config']['bulksms_username'];
		$password = $carovl['config']['bulksms_password'];
		if (empty($to)) {
			return false;
		}
		$explode = @explode('+', $to);
		if (empty($explode[1])) {
			return false;
		}
		$to = $explode[1];
		$url = $carovl['config']['eapi'] . '/submission/send_sms/2/2.0';
		$data = array(
			'username' => $username,
			'password' => $password,
			'msisdn' => $to,
			'message' => $message
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
			),
			'ssl' => array(
				"verify_peer" => false,
				'verify_peer_name' => false
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if (preg_match('/\bIN_PROGRESS\b/', $result)) {
			return true;
		} else {
			return $result;
		}
	}
	return false;
}
function confirmCode($user_id, $code)
{
	global $sql_connect;
	$user_id = secureIt($user_id);
	$code = secureIt($code);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `sms_code` = '{$code}' AND `user_id` = {$user_id} AND `active` = '0'");
	$result = sqlResult($query, 0);
	if ($result == 1) {
		$query_two = mysqli_query($sql_connect, "UPDATE " . T_USERS . " SET `active` = '1' WHERE `user_id` = {$user_id}");
		if ($query_two) {
			return true;
		}
	} else {
		return false;
	}
}
function activityData($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_ACTIVITIES . " WHERE `id` = {$id}");
	if (mysqli_num_rows($query) == 1) {
		$fetched_data = mysqli_fetch_assoc($query);
		$fetched_data['post'] = postData($fetched_data['post_id']);
		$fetched_data['user'] = userData($fetched_data['user_id']);
		return $fetched_data;
	}
}
function getActivities($data = array('after_activity_id' => 0, 'before_activity_id' => 0, 'limit' => 5))
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$get = array();
	if (empty($data['limit'])) {
		$data['limit'] = 5;
	}
	$limit = secureIt($data['limit']);
	$subquery = "`id` > 0";
	if (! empty($data['after_activity_id']) && is_numeric($data['after_activity_id']) && $data['after_activity_id'] > 0) {
		$data['after_activity_id'] = secureIt($data['after_activity_id']);
		$subquery = " `id` < " . $data['after_activity_id'] . " AND `id` <> " . $data['after_activity_id'];
	} elseif (! empty($data['before_activity_id']) && is_numeric($data['before_activity_id']) && $data['before_activity_id'] > 0) {
		$data['before_activity_id'] = secureIt($data['before_activity_id']);
		$subquery = " `id` > " . $data['before_activity_id'] . " AND `id` <> " . $data['before_activity_id'];
	}
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_ACTIVITIES . " WHERE {$subquery} AND `user_id` IN (SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `active` = '1') AND `user_id` NOT IN (SELECT `user_id` FROM " . T_USERS . " WHERE `show_activities_privacy` = '0') AND `user_id` NOT IN ({$user_id}) ORDER BY `id` DESC LIMIT {$limit}");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		if (is_array($fetched_data)) {
			$get[] = activityData($fetched_data['id']);
		}
	}
	return $get;
}

// Products
function registerProduct($product_data)
{
	global $carovl, $sql_connect;
	if (empty($product_data)) {
		return false;
	}
	if (! empty($product_data['description'])) {
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
		preg_match_all($link_regex, $product_data['description'], $matches);
		foreach ($matches[0] as $match) {
			$match_url = strip_tags($match);
			$syntax = '[a]' . urlencode($match_url) . '[/a]';
			$product_data['description'] = str_replace($match, $syntax, $product_data['description']);
		}
	}
	$fields = '`' . implode('`, `', array_keys($product_data)) . '`';
	$data = '\'' . implode('\', \'', $product_data) . '\'';
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_PRODUCTS . " ({$fields}) VALUES ({$data})");
	if ($query) {
		return mysqli_insert_id($sql_connect);
	}
	return false;
}
function registerProductMedia($id, $media)
{
	global $carovl, $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	if (empty($media)) {
		return false;
	}
	$query = mysqli_query($sql_connect, "INSERT INTO " . T_PRODUCTS_MEDIA . " (`product_id`, `image`) VALUES ({$id}, '{$media}')");
	if ($query) {
		return true;
	}
}
function productData($id = 0)
{
	global $carovl, $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_PRODUCTS . " WHERE `id` = {$id} ORDER BY `id` DESC");
	$fetched_data = mysqli_fetch_assoc($query);
	if (empty($fetched_data)) {
		return false;
	}
	$fetched_data['images'] = getProductImages($fetched_data['id']);
	$fetched_data['time_text'] = timeElapsedString($fetched_data['time']);
	$fetched_data['post_id'] = getPostIdFromProductId($fetched_data['id']);
	$fetched_data['edit_description'] = editMarkup(br2nl($fetched_data['description'], true, false, false));
	$fetched_data['description'] = getMarkup($fetched_data['description'], true, false, false);
	$fetched_data['price'] = number_format($fetched_data['price'], 0, ',', '.');
	$fetched_data['url'] = seoLink('index.php?page=product&id=' . $fetched_data['id'] . '_' . slugPost($fetched_data['name']));
	$fetched_data['author'] = userData($fetched_data['user_id']);
	return $fetched_data;
}
function getPostIdFromProductId($id)
{
	global $sql_connect;
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT `id` FROM " . T_POSTS . " WHERE `product_id` = {$id}");
	$fetched_data = mysqli_fetch_assoc($query);
	return $fetched_data['id'];
}
function getProductImages($id = 0)
{
	global $carovl, $sql_connect;
	$data = array();
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT `id`, `image`, `product_id` FROM " . T_PRODUCTS_MEDIA . " WHERE `product_id` = {$id} ORDER BY `id` DESC");
	while ($fetched_data = mysqli_fetch_assoc($query)) {
		$explode = @end(explode('.', $fetched_data['image']));
		$explode2 = @explode('.', $fetched_data['image']);
		$fetched_data['image_org'] = $explode2[0] . '_small.' . $explode;
		$fetched_data['image_org'] = getMedia($fetched_data['image_org']);
		$fetched_data['image'] = getMedia($fetched_data['image']);
		$data[] = $fetched_data;
	}
	return $data;
}
function productImageData($data = array())
{
	global $carovl, $sql_connect;
	if (! empty($data['id'])) {
		$id = secureIt($data['id']);
	}
	$order_by = '';
	if (! empty($data['after_image_id']) && is_numeric($data['after_image_id'])) {
		$data['after_image_id'] = secureIt($data['after_image_id']);
		$subquery = " `id` <> " . $data['after_image_id'] . " AND `id` < " . $data['after_image_id'];
		$order_by = "DESC";
	} elseif (! empty($data['before_image_id']) && is_numeric($data['before_image_id'])) {
		$data['before_image_id'] = secureIt($data['before_image_id']);
		$subquery = " `id` <> " . $data['before_image_id'] . " AND `id` > " . $data['before_image_id'];
		$order_by = "ASC";
	} else {
		$subquery = " `id` = {$id}";
	}
	if (! empty($data['post_id']) && is_numeric($data['post_id'])) {
		$data['post_id'] = secureIt($data['post_id']);
		$subquery .= " AND `post_id` = " . $data['post_id'];
	}
	$query = mysqli_query($sql_connect, "SELECT * FROM " . T_PRODUCTS_MEDIA . " WHERE {$subquery} ORDER BY `id` {$order_by}");
	$fetched_data = mysqli_fetch_assoc($query);
	if (! empty($fetched_data)) {
		$fetched_data['image_org'] = getMedia($fetched_data['image']);
	}
	return $fetched_data;
}
function isProductOwner($product_id = 0, $user_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($product_id)) {
		return false;
	}
	if (empty($user_id)) {
		$user_id = $carovl['user']['user_id'];
	}
	$user_id = secureIt($user_id);
	$product_id = secureIt($product_id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_PRODUCTS . " WHERE `user_id` = {$user_id} AND `id` = {$product_id}");
	$fetched_data = mysqli_fetch_assoc($query);
	return ($fetched_data['count'] > 0) ? true : false;
}
function markProductAsSold($post_id = 0, $product_id = 0)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	$user_id = secureIt($carovl['user']['user_id']);
	$post_id = secureIt($post_id);
	if (empty($user_id) || ! is_numeric($user_id) || $user_id < 1) {
		return false;
	}
	if (empty($product_id) || ! is_numeric($product_id) || $product_id < 1) {
		return false;
	}
	if (empty($post_id) || ! is_numeric($post_id) || $post_id < 1) {
		return false;
	}
	if (isPostExist($post_id) === false) {
		return false;
	}
	if (isPostOwner($post_id, $user_id) === false) {
		return false;
	}
	if (isProductSold($product_id)) {
		return false;
	}
	$query = mysqli_query($sql_connect, "UPDATE " . T_PRODUCTS . " SET `status` = '1' WHERE `id` = '{$product_id}'");
	if ($query) {
		return true;
	}
}
function isProductSold($id)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($id) || ! is_numeric($id) || $id < 1) {
		return false;
	}
	$id = secureIt($id);
	$query = mysqli_query($sql_connect, "SELECT COUNT(`id`) AS `count` FROM " . T_PRODUCTS . " WHERE `id` = {$id} AND `status` = '1'");
	$fetched_data = mysqli_fetch_assoc($query);
	return ($fetched_data['count'] == 1) ? true : false;
}
function updateProduct($product_id, $update_data)
{
	global $carovl, $sql_connect;
	if ($carovl['logged_in'] == false) {
		return false;
	}
	if (empty($product_id) || ! is_numeric($product_id) || $product_id < 0) {
		return false;
	}
	if (empty($update_data)) {
		return false;
	}
	$product_id = secureIt($product_id);
	$post_id = getPostIdFromProductId($product_id);
	if (empty($post_id)) {
		return false;
	}
	if (isPostOwner($post_id, $carovl['user']['user_id']) === false) {
		return false;
	}
	$update = array();
	if (! empty($update_data['description'])) {
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
		preg_match_all($link_regex, $update_data['description'], $matches);
		foreach ($matches[0] as $match) {
			$match_url = strip_tags($match);
			$syntax = '[a]' . urlencode($match_url) . '[/a]';
			$update_data['description'] = str_replace($match, $syntax, $update_data['description']);
		}
	}
	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . secureIt($data, 0) . '\'';
	}
	$implode = implode(', ', $update);
	$query = mysqli_query($sql_connect, "UPDATE " . T_PRODUCTS . " SET {$implode} WHERE `id` = {$product_id}");
	if ($query) {
		return true;
	}
	return false;
}
?>