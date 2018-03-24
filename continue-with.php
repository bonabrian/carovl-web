<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

require_once('assets/initialize.php');
require_once('assets/import/config.php');
require_once('assets/import/hybridauth/hybridauth/Hybrid/Auth.php');
require_once('assets/import/hybridauth/hybridauth/Hybrid/Endpoint.php');
require_once('assets/import/hybridauth/vendor/autoload.php');

$types = array(
	'Google',
	'Facebook',
	'Twitter'
);
if (isset($_GET['provider']) && in_array($_GET['provider'], $types)) {
	$provider = secureIt($_GET['provider']);
	try {
		$hybridauth   = new Hybrid_Auth($LoginWithConfig);
        $authProvider = $hybridauth->authenticate($provider);
        $user_profile = $authProvider->getUserProfile();
		if ($user_profile && isset($user_profile->identifier)) {
			$name = $user_profile->firstName;
			if ($provider == 'Google') {
                $notfound_email     = 'go_';
                $notfound_email_com = '@google.com';
            } else if ($provider == 'Facebook') {
                $notfound_email     = 'fa_';
                $notfound_email_com = '@facebook.com';
            } else if ($provider == 'Twitter') {
                $notfound_email     = 'tw_';
                $notfound_email_com = '@twitter.com';
            }
			$user_name = $notfound_email . $user_profile->identifier;
            $user_email = $user_name . $notfound_email_com;
			if (! empty($user_profile->email)) {
                $user_email = $user_profile->email;
            }
			if (isEmailExist($user_email) === true) {
				setLoginWithSession($user_email);
				header("Location: " . $config['site_url']);
				exit();
			} else {
				$str = md5(microtime());
				$id = substr($str, 0, 9);
				$user_unique_id = (isUsernameExist($id) === false) ? $id : 'u_' . $id;
				$social_url   = substr($user_profile->profileURL, strrpos($user_profile->profileURL, '/') + 1);
				$imported_image = importImageFromLogin($user_profile->photoURL, 1);
				$re_data      = array(
                    'username' => secureIt($user_uniq_id, 0),
                    'email' => secureIt($user_email, 0),
                    'password' => secureIt($user_email, 0),
                    'email_code' => secureIt(md5($user_uniq_id), 0),
                    'first_name' => secureIt($name),
                    'last_name' => secureIt($user_profile->lastName),
                    'avatar' => secureIt($imported_image),
                    'src' => secureIt($provider),
                    'getstarted_image' => 1,
                    'lastseen' => time(),
                    'social_login' => '1',
                    'active' => '1'
                );
                if ($provider == 'Google') {
                    $re_data['about']  = secureIt($user_profile->description);
                    $re_data['google'] = secureIt($social_url);
                }
                if ($provider == 'Facebook') {
                	$fa_social_url       = @explode('/', $user_profile->profileURL);
                    $re_data['facebook'] = secureIt($fa_social_url[4]);
                    $re_data['gender'] = 'male';
                    if (!empty($user_profile->gender)) {
                        if ($user_profile->gender == 'male') {
                            $re_data['gender'] = 'male';
                        } else if ($user_profile->gender == 'female') {
                            $re_data['gender'] = 'female';
                        }
                    }
                }
                if ($provider == 'Twitter') {
                	$re_data['twitter'] = secureIt($social_url);
                }
                if (registerUser($re_data) === true) {
                	setLoginWithSession($user_email);
                    $user_id = userIdFromEmail($user_email);
                    if (! empty($user_profile->photoURL)) {
                    	$explode = @end(explode('.', $imported_image));
                    	$explode2 = @explode('.', $imported_image);
                    	$last_file = $explode2[0] . '_full.' . $explode;
                    	$compress = compressImage($imported_image, $last_file, 50);
                    	if ($compress) {
                    		$query = mysqli_query($sql_connect, "INSERT INTO " . T_POSTS . " (`user_id`, `postFile`, `time`, `postType`) VALUES ('$user_id', '" . secureIt($last_file) . "', '" . secureIt(time()) . "', 'profile_picture_deleted')");
                    		$sql_id = mysqli_insert_id($sql_connect);
                            $sql_id = secureIt($sql_id);
                            $update_query = mysqli_query($sql_connect, "UPDATE " . T_POSTS . " SET `post_id` = {$sql_id} WHERE `id` = {$sql_id}");
                            resizeCropImage($carovl['profile_picture_width_crop'], $carovl['profile_picture_height_crop'], $imported_image, $imported_image, $carovl['profile_picture_image_quality']);
                    	}
                    }
                    $carovl['user'] = $re_data;
                    header("Location: " . seoLink('index.php?page=getstarted'));
                    exit();
                }
			}
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		switch ($e->getCode()) {
            case 0:
                echo "Unspecified error.";
                break;
            case 1:
                echo "Hybridauth configuration error.";
                break;
            case 2:
                echo "Provider not properly configured.";
                break;
            case 3:
                echo "Unknown or disabled provider.";
                break;
            case 4:
                echo "Missing provider application credentials.";
                break;
            case 5:
                echo "Authentication failed The user has canceled the authentication or the provider refused the connection.";
                break;
            case 6:
                echo "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
                break;
            case 7:
                echo "User not connected to the provider.";
                break;
            case 8:
                echo "Provider does not support this feature.";
                break;
        }
        echo " an error found while processing your request!";
        echo " <b><a href='" . seoLink('index.php?page=welcome') . "'>Try again<a></b>";
	}
} else {
	header("Location: " . seoLink('index.php?page=welcome'));
	exit();
}
?>