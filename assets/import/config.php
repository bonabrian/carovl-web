<?php
/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

	$LoginWithConfig = array(
			"base_url" => $config['site_url'] . '/import.php',
			"providers" => array(
				// openid providers
				"OpenID" => array(
					"enabled" => true
				),
				"Yahoo" => array(
					"enabled" => true,
					"keys" => array("key" => "", "secret" => ""),
				),
				"AOL" => array(
					"enabled" => true
				),
				"Google" => array(
					"enabled" => true,
					"keys" => array("id" => $config['google_app_id'], "secret" => $config['google_app_key']),
				),
				"Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => $config['facebook_app_id'], "secret" => $config['facebook_app_key']),
					"scope" => "email",
					"trustForwarded" => false
				),
				"Twitter" => array(
					"enabled" => true,
					"keys" => array("key" => $config['twitter_app_id'], "secret" => $config['twitter_app_key']),
					"includeEmail" => true
				),
				// "LinkedIn" => array(
				// 	"enabled" => true,
				// 	"keys" => array("key" => $config['linkedinAppId'], "secret" => $config['linkedinAppKey'])
				// ),
				// "Vkontakte" => array(
				// 	"enabled" => true,
				// 	"keys" => array("id" => $config['VkontakteAppId'], "secret" => $config['VkontakteAppKey'])
				// ),
				// "Instagram" => array(
				// 	"enabled" => true,
				// 	"keys" => array("id" => $config['instagram_app_id'], "secret" => $config['instagram_app_key'])
				// ),
				
				// windows live
				"Live" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
				"Foursquare" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => false,
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			"debug_file" => "",
);
