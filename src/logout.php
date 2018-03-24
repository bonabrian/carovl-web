<?php 
if (! empty($_SESSION['user_id'])) {
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_SESSIONS . " WHERE `session_id` = '" . secureIt($_SESSION['user_id']) . "'");
}
session_destroy();
if (isset($_COOKIE['user_id'])) {
	$query = mysqli_query($sql_connect, "DELETE FROM " . T_SESSIONS . " WHERE `session_id` = '" . secureIt($_COOKIE['user_id']) . "'");
	unset($_COOKIE['user_id']);
	setcookie('user_id', null, -1);
}
header("Location: " . $carovl['config']['site_url']);
exit();
?>