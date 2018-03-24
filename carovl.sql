-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2017 at 03:04 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carovl`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL DEFAULT '0',
  `post_id` int(255) NOT NULL DEFAULT '0',
  `activity_type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `post_id`, `activity_type`, `time`) VALUES
(2, 7, 112, 'liked_post', 1497555522),
(3, 9, 119, 'liked_post', 1497981630),
(4, 9, 119, 'commented_post', 1497981687),
(5, 7, 112, 'shared_post', 1498308731),
(16, 7, 139, 'commented_post', 1498485837),
(17, 8, 189, 'liked_post', 1499765770),
(18, 9, 193, 'liked_post', 1499855184),
(19, 9, 192, 'commented_post', 1499855189),
(20, 8, 194, 'liked_post', 1499861899),
(21, 8, 194, 'shared_post', 1499863495),
(22, 8, 187, 'liked_post', 1499866138),
(23, 8, 111, 'liked_post', 1499866208),
(24, 8, 143, 'liked_post', 1499868271),
(27, 7, 113, 'liked_post', 1499869852),
(28, 8, 198, 'liked_post', 1499963213),
(29, 8, 198, 'commented_post', 1499963218),
(32, 8, 201, 'liked_post', 1500734759);

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  `code` text,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `type`, `code`, `active`) VALUES
(1, 'header', '<div class="sidebar-footer">\n	<div class="card">\n		<div class="card-block">\n			<center>\n				<h3>Carovl</h3>\n				<h6>Built with <i class="fa fa-heart"></i> in Bandung</h6>\n			</center>\n		</div>\n	</div>\n</div>', '0'),
(2, 'sidebar', 'sidebar fuck', '0'),
(3, 'footer', '', '0'),
(4, 'post_first', '<div class="sidebar-footer mb-3">\n	<div class="card">\n		<div class="card-block">\n			<center>\n				<h3>Carovl</h3>\n				<h6>Built with <i class="fa fa-heart"></i> in Bandung</h6>\n			</center>\n		</div>\n	</div>\n</div>', '0'),
(5, 'post_second', '<div class="sidebar-footer mb-3">\n	<div class="card">\n		<div class="card-block">\n			<center>\n				<h3>Carovl</h3>\n				<h6>Built with <i class="fa fa-heart"></i> in Bandung</h6>\n			</center>\n		</div>\n	</div>\n</div>', '0'),
(6, 'post_third', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `albums_media`
--

CREATE TABLE `albums_media` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `text` text,
  `time` int(32) NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_views`
--

CREATE TABLE `announcement_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `announcement_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `article_title` varchar(100) NOT NULL DEFAULT '',
  `article_content` text NOT NULL,
  `article_source` varchar(100) NOT NULL DEFAULT '',
  `article_thumbnail` varchar(100) NOT NULL DEFAULT '',
  `article_tags` varchar(320) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  `draft` enum('0','1') NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `article_title`, `article_content`, `article_source`, `article_thumbnail`, `article_tags`, `views`, `draft`, `time`) VALUES
(13, 7, 'Difference between a coder, a programmer, a developer and a software engineer', '&lt;p&gt;Maybe you have heard about these job titles in the past, but what exactly is the difference between them? Some people claim that it mostly depends on your education and on what you have achieved; in some way, this may be true, but some others claim that sometimes the company you are working for decides if you are a programmer, a developer or other, making it merely a job title. Let’s take a closer look to this.&lt;/p&gt;&lt;h3&gt;1. Coder&lt;/h3&gt;&lt;p&gt;&lt;img src=&quot;http://4.bp.blogspot.com/-JOsuVgkEbXc/VBl5U2XIACI/AAAAAAAAAFc/oLuZP8yTY5k/s1600/code-its-fun.jpg&quot; alt=&quot;&quot; width=&quot;600&quot; height=&quot;450&quot; /&gt;&lt;/p&gt;&lt;p&gt;A Coder is a person in charge of writing the code that makes most of our apps run properly. Those who are coders have the ability to create software that can be used not only in apps but also in video games, social media platforms, and many others. Coders sometimes cannot do all the phases required in the making of a software, like designing or testing, they mostly take part only in the phase of writing the base code. In some cases, there are people who may get offended If you call them a coder.&lt;/p&gt;&lt;h3&gt;2. Programmer&lt;/h3&gt;&lt;p&gt;&lt;img src=&quot;https://students.telkomuniversity.ac.id/wp-content/uploads/2014/06/programmer.jpeg&quot; alt=&quot;&quot; width=&quot;600&quot; height=&quot;375&quot; /&gt;&lt;/p&gt;&lt;p&gt;A Programmer is a bit more specialized person. They are able to create computer software in any primary computer language, like &lt;a href=&quot;https://www.technotification.com/2016/07/java-best-programming-language.html&quot; target=&quot;_blank&quot; rel=&quot;noopener noreferrer&quot;&gt;Java&lt;/a&gt;, &lt;a href=&quot;https://www.technotification.com/2017/05/python-programming-for-hackers.html&quot; target=&quot;_blank&quot; rel=&quot;noopener noreferrer&quot;&gt;Python&lt;/a&gt;, Lisp, etc. Programmers are said to go beyond coders, they may specialize themselves in one area or may even write instructions for a wide variety of systems.&lt;/p&gt;&lt;p&gt;They also understand quite well algorithms. Programmers can be similar to Developers but the ones who implements are not the same as the ones who can design or do a well class structure within the software. They can take care of many details.&lt;/p&gt;&lt;h3&gt;3. Developer&lt;/h3&gt;&lt;p&gt;&lt;img src=&quot;http://www.interlandtech.com/images/developer.jpg&quot; alt=&quot;&quot; width=&quot;600&quot; height=&quot;259&quot; /&gt;&lt;/p&gt;&lt;p&gt;A Developer can write and create a complete computer software out of nowhere taking care of the design and other features. They are key for the development of any software applications; they are also experts in at least one programming language. Some people consider them true professionals that can take care of all the generals. Developers can sometimes be more general when it comes to the development of a software, unlike Programmers.&lt;/p&gt;&lt;h3&gt;4. Software Engineer&lt;/h3&gt;&lt;p&gt;&lt;img src=&quot;https://www.designnews.com/sites/default/files/Design%20News/coding-699318_1280.jpg&quot; alt=&quot;&quot; width=&quot;600&quot; height=&quot;410&quot; /&gt;&lt;/p&gt;&lt;p&gt;A Software Engineer is an individual that applies the principles and techniques of computer science or software engineering to everything regarding the development of a new independent software; from analyzing what the particular needs of the user are, going through the design, maintenance and testing, and even the final evaluation of the software. They are able to create software’s for any kind of system as operating systems software, network distribution, compilers and so on. They often have a college degree and can prove things theoretically.&lt;/p&gt;&lt;p&gt;Another way for understanding the differences between a Coder, a Programmer, a Developer and a Software Engineer is seeing them as a hierarchy or as stair, where the Coder can be found in the low section of the stair and the Software Engineer can be found at the top. Maybe to you all these job tittles may mean the same because you once knew a Developer that could do everything a Software Engineer can, but there are really some differences between them are worth knowing.&lt;/p&gt;&lt;p&gt;If some companies take special attention in the name of their employees or not, it is not such a big deal to worry about; what really matters is knowing what you can do and how well you can do it.&lt;/p&gt;', 'https://www.technotification.com/2017/06/coder-programmer-developer-difference.html', 'uploads/photos/2017/06/77VrV7rRNaX1yhJkzLhx_21_9b9327b0a6a8c9f1b39879a8bfa258cf_image.jpg', 'carovl,programmer', 56, '0', 1498003437);

-- --------------------------------------------------------

--
-- Table structure for table `banned_ip`
--

CREATE TABLE `banned_ip` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `id` int(11) NOT NULL,
  `blocker` int(11) NOT NULL DEFAULT '0',
  `blocked` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_media`
--

CREATE TABLE `blog_media` (
  `id` int(11) NOT NULL,
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `authors` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `tags` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `image_file_name` varchar(255) NOT NULL DEFAULT '',
  `enabled` enum('0','1') NOT NULL DEFAULT '0',
  `multi_image` enum('0','1','') NOT NULL DEFAULT '0',
  `registered` date NOT NULL DEFAULT '0000-00-00',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `id` int(11) NOT NULL,
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(255) NOT NULL DEFAULT '',
  `post_num` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `text` text,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `text`, `time`) VALUES
(2, 7, 107, 'good post', 1497506720),
(3, 9, 119, 'Indonesia tanah air beta, pusaka abadi nan jaya, Indonesia sejak dulu kala, selalu dipuja-puja bangsa. Disana tempat lahir beta, dibuai, dibesarkan Bunda, tempat berlindung di hari tua, sampai akhir menutup mata.', 1497981686),
(4, 7, 139, 'euy', 1498485509),
(5, 7, 139, 'tai', 1498485511),
(6, 7, 139, 'kenapa bug lagi?', 1498485515),
(7, 7, 139, 'cacat', 1498485517),
(8, 7, 139, 'tai wkwk', 1498485531),
(9, 7, 139, 'babi lah haha', 1498485536),
(10, 7, 139, 'eh si anying', 1498485545),
(11, 7, 139, 'tes', 1498485563),
(12, 7, 139, 'eheee', 1498485570),
(13, 7, 139, 'shit', 1498485837),
(32, 7, 146, 'tes', 1498487233),
(33, 7, 146, 'hei', 1498487235),
(34, 7, 146, 'babi', 1498487237),
(35, 7, 146, 'lah', 1498487238),
(36, 7, 146, 'kau', 1498487242),
(43, 7, 147, 'ee', 1498487401),
(44, 7, 147, 'ee', 1498487402),
(45, 7, 147, 'ee', 1498487402),
(46, 7, 147, 'ee', 1498487403),
(47, 7, 147, 'ee', 1498487403),
(62, 7, 172, 'hei bro', 1499272149),
(63, 7, 172, 'bro hei', 1499272152),
(64, 7, 172, 'when the vision around you, bring tears to your eyes, and all that surrounds you all secret or lies, i&#039;ll be your strength i&#039;ll give you hope', 1499272239),
(65, 9, 192, 'ganteng', 1499855189),
(66, 7, 198, 'hei', 1499962982),
(67, 7, 198, 'keeping faith, letting love find a way', 1499962996),
(68, 8, 198, 'ganteng ih', 1499963218),
(69, 7, 200, 'hei', 1500035853);

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `comment_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `post_id`, `comment_id`, `user_id`) VALUES
(1, 198, 66, 7),
(2, 198, 66, 8);

-- --------------------------------------------------------

--
-- Table structure for table `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `text` text,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(1000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'site_name', 'Carovl'),
(2, 'site_title', 'Carovl'),
(3, 'site_keywords', 'Carovl, Social Network, Photos, Sharing, Videos'),
(4, 'site_desc', 'Desc'),
(5, 'site_email', 'no-reply@carovl.com'),
(6, 'default_lang', 'english'),
(7, 'email_validation', '1'),
(8, 'email_notification', '0'),
(9, 'file_sharing', '1'),
(10, 'seo_link', '1'),
(11, 'cache_system', '1'),
(12, 'chat_system', '1'),
(13, 'use_seo_friendly', '1'),
(14, 'reCaptcha', '0'),
(15, 'reCaptcha_key', ''),
(16, 'user_lastseen', '1'),
(17, 'age', '1'),
(18, 'delete_account', '1'),
(19, 'profile_visit', '1'),
(20, 'max_upload', '6000000'),
(21, 'max_characters', '640'),
(22, 'message_seen', '1'),
(23, 'message_typing', '0'),
(24, 'google_map_api', 'AIzaSyAc4vh3nYfiibR_jh7eDzRn9BX4v5EQsms'),
(25, 'allowed_extension', 'jpg,png,jpeg,gif,mkv,docx,zip,rar,pdf,doc,mp3,mp4,flv,wav,txt,mov,avi,webm,wav,mpeg,tmp'),
(26, 'censored_words', 'fuck'),
(27, 'google_analytics', '<script>\n  (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n  })(window,document,''script'',''https://www.google-analytics.com/analytics.js'',''ga'');\n\n  ga(''create'', ''UA-93928802-1'', ''auto'');\n  ga(''send'', ''pageview'');\n\n</script>'),
(28, 'all_login', '1'),
(29, 'google_login', '1'),
(30, 'facebook_login', '1'),
(31, 'instagram_login', '1'),
(32, 'google_app_id', '1039658515862-vpfvo6ns8to0ji94cf9kt87lum9jgg7v.apps.googleusercontent.com'),
(33, 'google_app_key', '5Eian2y7lM-7FKrQtfxIPfYF'),
(34, 'facebook_app_id', '1245814675536762'),
(35, 'facebook_app_key', 'f453214cb591ff496f17f7ccd3aa1f0c'),
(36, 'instagram_app_id', 'a'),
(37, 'instagram_app_key', 'a'),
(38, 'profile_privacy', '1'),
(39, 'video_upload', '1'),
(40, 'audio_upload', '1'),
(41, 'smtp_or_mail', 'smtp'),
(42, 'smtp_username', 'bonabriansiagian@gmail.com'),
(43, 'smtp_host', 'smtp.gmail.com'),
(44, 'smtp_password', 'l00k@ndw4tch'),
(45, 'smtp_port', '587'),
(46, 'smtp_encryption', 'tls'),
(47, 'sms_or_email', 'mail'),
(48, 'twilio_sms_username', ''),
(49, 'twilio_sms_password', ''),
(50, 'twilio_sms_phone_number', ''),
(51, 'is_ok', '1'),
(52, 'last_backup', '15-06-2017'),
(53, 'groups', '1'),
(54, 'developers_page', '1'),
(55, 'user_registration', '1'),
(56, 'video_call', '0'),
(57, 'video_account_sid', 'ACdc5a23d3fda6acbff32639609330f9fa'),
(58, 'video_api_key_sid', 'SKb7fdad1a69186487d4cbd7bb22f39517'),
(59, 'video_api_key_secret', 'g7dbSvpBEmIOOrzrzL9ii8bEAkuOQoNU'),
(60, 'video_configuration_profile_sid', ''),
(61, 'eapi', 'https://bulksms.vsms.net/eapi'),
(62, 'stripe_id', ''),
(63, 'stripe_secret', ''),
(64, 'products', '1'),
(65, 'maintenance_mode', '0'),
(66, 'bucket_name', ''),
(67, 'amazone_s3', '0'),
(68, 'amazone_s3_key', ''),
(69, 'amazone_s3_s_key', ''),
(70, 'region', 'us-east-1'),
(71, 'order_posts_by', '1'),
(74, 'error', '1136'),
(76, 'twitter_login', '1'),
(77, 'twitter_app_id', 'abc'),
(78, 'twitter_app_key', 'abc'),
(79, 'currency', 'Rupiah'),
(80, 'smooth_loading', '0'),
(81, 'events', '1'),
(82, 'sms_provider', 'bulksms'),
(83, 'bulksms_username', 'bonabriansiagian'),
(84, 'bulksms_password', '80n4r4hm4'),
(85, 'site_phone_number', '+6281223952600'),
(86, 'desktop_app_id', 'QXZ1SK8NUOYWRUP7VIDH'),
(87, 'desktop_app_key', 'e1ed05518cc634ac66328447c55bad427ffa6643-e3QF8wWWHCcdtRI3yPkM');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email_to` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover` varchar(500) NOT NULL DEFAULT 'uploads/photos/d-cover.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `location`, `description`, `start_date`, `end_date`, `start_time`, `end_time`, `user_id`, `cover`) VALUES
(5, 'Anniversary yang ke-7', 'Bandung', 'Happy Anniv baraairkalajengking', '2017-06-10', '2017-06-11', '19:56:47', '00:56:47', 8, 'uploads/photos/2017/06/QN2fCGcirHYlX2yVUqIS_10_3f5b8ed97298bbaa79707ee96de2baf4_cover.jpg'),
(7, 'Jalan ke Garut', 'Garut', 'Deskripsi nyusul', '2017-06-15', '2017-06-16', '21:55:45', '02:55:45', 7, 'uploads/photos/d-cover.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `events_action`
--

CREATE TABLE `events_action` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `inviter_id` int(11) NOT NULL DEFAULT '0',
  `action` enum('going','interested','invited') NOT NULL DEFAULT 'going'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events_action`
--

INSERT INTO `events_action` (`id`, `event_id`, `user_id`, `inviter_id`, `action`) VALUES
(1, 5, 7, 0, 'interested');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL DEFAULT '0',
  `follower_id` int(11) NOT NULL DEFAULT '0',
  `is_typing` int(11) NOT NULL DEFAULT '0',
  `active` int(255) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `following_id`, `follower_id`, `is_typing`, `active`) VALUES
(61, 7, 9, 0, 1),
(64, 9, 8, 0, 1),
(66, 8, 9, 0, 1),
(67, 15, 8, 0, 1),
(69, 9, 15, 0, 1),
(75, 16, 8, 0, 1),
(77, 8, 17, 0, 1),
(78, 12, 8, 0, 1),
(80, 9, 7, 0, 1),
(81, 8, 15, 0, 1),
(82, 8, 7, 0, 1),
(83, 7, 8, 0, 1),
(84, 15, 7, 0, 1),
(85, 11, 7, 0, 1),
(86, 12, 7, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(32) NOT NULL DEFAULT '',
  `group_title` varchar(64) NOT NULL DEFAULT '',
  `avatar` varchar(128) NOT NULL DEFAULT 'uploads/photos/d-group.jpg',
  `cover` varchar(128) NOT NULL DEFAULT 'uploads/photos/d-cover.jpg',
  `about` varchar(512) NOT NULL DEFAULT '',
  `category` int(11) NOT NULL DEFAULT '1',
  `privacy` enum('0','1') NOT NULL DEFAULT '0',
  `join_privacy` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `registered` varchar(32) NOT NULL DEFAULT '0/0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `user_id`, `group_name`, `group_title`, `avatar`, `cover`, `about`, `category`, `privacy`, `join_privacy`, `active`, `registered`) VALUES
(4, 7, 'carovl', 'Carovl''s Group', 'uploads/photos/2017/07/D3zzzx9CYgfUoGb7feiK_14_dd345c43a3921bf6a2cb8dae6eb77a41_avatar.jpg', 'uploads/photos/2017/06/a8o3AAIt5kSX5FZEPJkP_11_29802c88d043971d0b1b3367b26af6d0_cover.jpg', 'Everything about Carovl', 1, '0', '1', '1', '6/2017'),
(5, 7, 'carovl_org', 'Carovl&#039;s Group', 'uploads/photos/d-group.jpg', 'uploads/photos/d-cover.jpg', 'About Carovl', 1, '0', '0', '1', '6/2017');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `user_id`, `group_id`, `time`, `active`) VALUES
(15, 7, 4, 1497090679, '1'),
(16, 8, 4, 1497095400, '1'),
(17, 7, 5, 1497554875, '1');

-- --------------------------------------------------------

--
-- Table structure for table `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(255) NOT NULL DEFAULT '',
  `last_trend_time` int(11) NOT NULL DEFAULT '0',
  `trend_use_num` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hashtags`
--

INSERT INTO `hashtags` (`id`, `hash`, `tag`, `last_trend_time`, `trend_use_num`) VALUES
(1, 'b2d28ef83f2b78b798fc40f9ded46a5a', 'hashtag', 1496739921, 4),
(2, '2645708d1e0cc5e9a6ebe67cd46cd42b', 'bmth', 1496569753, 7),
(3, 'd57ac45256849d9b13e2422d91580fb9', 'tags', 1495520584, 1),
(4, 'cb103bbbcf302f5138fe9ddfa5288a6d', 'carovl', 1498477410, 54),
(5, 'd2e16e6ef52a45b7468f1da56bba1953', 'lorem', 1495879552, 3),
(6, 'a425352a84b14c7acb601495bd156322', 'programmer', 1498003438, 2),
(7, '7e73d06707f5fb75ec77cc5f2bd9bb92', 'programming', 1498002281, 1),
(8, '5d7a199bcec9fce84951081ed4a8bae3', 'everywordisay', 1499131081, 0),
(9, '28b662d883b6d76fd96e4ddc5e9ba780', 'tes', 1499241312, 0);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `lang_key` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `english` text,
  `indonesian` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `lang_key`, `english`, `indonesian`) VALUES
(1, 'account', 'Account', 'Akun'),
(2, 'setting', 'Setting', 'Pengaturan'),
(3, 'logout', 'Logout', 'Keluar'),
(4, 'following', 'Following', 'Mengikuti'),
(5, 'articles', 'Articles', 'Artikel'),
(6, 'events', 'Events', 'Event'),
(7, 'followers', 'Followers', 'Pengikut'),
(8, 'activity', 'Activity', 'Aktifitas'),
(9, 'help', 'Help', 'Bantuan'),
(10, 'search_label', 'Search Carovl', 'Pencarian Carovl'),
(11, 'lastseen', 'Lastseen', 'Terakhir terlihat'),
(12, 'online', 'Online', 'Online'),
(13, 'posts', 'posts', 'pos'),
(14, 'advanced_search', 'Advanced Search', 'Pencarian Lanjutan'),
(15, 'clear', 'Clear', 'Bersihkan'),
(16, 'search', 'Search', 'Cari'),
(17, 'follow_requests', 'Follow Requests', 'Permintaan Pengikut'),
(18, 'accept_request', 'Accept Request', 'Terima Permintaan'),
(19, 'reject_request', 'Reject Request', 'Abaikan Permintaan'),
(20, 'login_label', 'Login', 'Masuk'),
(21, 'register_label', 'Register', 'Daftar'),
(22, 'page_not_found', 'Page not found', 'Halaman tidak ditemukan'),
(23, 'ooops_sorry', 'Ooops, we''re sorry, it looks like this page not found in our site.', 'Ooops, mohon maaf, sepertinya halaman ini tidak ditemukan pada situs kami.'),
(24, 'dont_worry', 'Don''t worry, we''ll get this page freshly. In the mean-time, try the menu above for what you were looking for.', 'Jangan khawatir, kami akan membuat halaman ini segar kembali. Sementara itu, coba menu di atas untuk apa yang Anda cari.'),
(25, 'reset_password', 'Reset Password', 'Atur Ulang Kata Sandi'),
(26, 'reset_password_desc', 'To receive the link for setting a new password, please specify your email address of your Carovl account.', 'Untuk mendapatkan tautan dalam mengatur ulang kata sandi baru, silahkan masukkan alamat surel Carovl Anda.'),
(27, 'reset_password_label', 'Reset Password', 'Atur Ulang Kata Sandi'),
(28, 'email_sent', 'Email has been sent successfully, please check your inbox or spam folder for the activation link.', 'Surel berhasil terkirim, mohon periksa kotak masuk atau folder spam untuk link aktivasi.'),
(29, 'failed_to_send_email', 'Failed to send email', 'Gagal mengirim surel'),
(30, 'create_new_password', 'Create New Password', 'Buat Kata Sandi Baru'),
(31, 'new_password_label', 'New Password', 'Kata Sandi Baru'),
(32, 'sent_label', 'Sent', 'Terkirim'),
(33, 'account_activation', 'Account Activation', 'Aktivasi Akun'),
(34, 'sign_in_to_carovl', 'Sign in to Carovl to connect with other peoples.', 'Masuk ke Carovl untuk terhubung ke orang-orang.'),
(35, 'forgot_password', 'I''ve forgotten something :(', 'Saya melupakan sesuatu :('),
(36, 'continue_with_facebook', 'Continue with Facebook', 'Lanjutkan dengan Facebook'),
(37, 'continue_with_google', 'Continue with Google', 'Lanjutkan dengan Google'),
(38, 'continue_with_twitter', 'Continue with Twitter', 'Lanjutkan dengan Twitter'),
(39, 'or_sign_up_with_email', 'Or sign up with email', 'Atau daftar menggunakan surel'),
(40, 'login_faq', 'Login FAQ', 'Masuk FAQ'),
(41, 'to_use_carovl', 'To use Carovl you must have cookies enabled. If you sign up with Facebook, Google or Twitter, we''ll never post to Facebook, Google or Twitter without your permission. For more info, please see {login_faq}', 'Untuk menggunakan Carovl, Anda harus mengaktifkan cookies. Jika Anda mendaftar menggunakan Facebook, Google atau Twitter, kami tidak akan posting ke Facebook, Google atau Twiiter tanpa izin dari Anda. Untuk informasi lebih lanjut, silahkan lihat {login_faq}'),
(42, 'welcome_back', 'Welcome Back :)', 'Selamat Datang Kembali :)'),
(43, 'sign_up_to_carovl', 'Sign up to Carovl to connect with other peoples.', 'Daftar ke Carovl untuk terhubung dengan orang-orang.'),
(44, 'email_label', 'Email', 'Surel'),
(45, 'username_label', 'Username', 'Nama Pengguna'),
(46, 'password_label', 'Password', 'Kata Sandi'),
(47, 'confirm_password_label', 'Confirm Password', 'Ulangi Kata Sandi'),
(48, 'successfully_joined_label', 'Successfully joined, please wait...', 'Berhasil bergabung, mohon tunggu...'),
(49, 'successfully_joined_verify_label', 'Registration successfull! We have sent you an email, please check your inbox/spam to verify your email.', 'Pendaftaran berhasil! Kami telah mengirimkan Anda surel, silahkan periksa kotak masuk/spam untuk verifikasi surel Anda.'),
(50, 'please_fill_all_the_fields', 'Please fill all the fields.', 'Mohon isi semua form.'),
(51, 'username_already_exist', 'Username already exist.', 'Nama pengguna sudah digunakan.'),
(52, 'wrong_phone_number', 'Wrong phone number.', 'Nomor telepon salah.'),
(53, 'cannot_use_this_username', 'You can''t use this username.', 'Anda tidak dapat menggunakan nama pengguna ini.'),
(54, 'username_characters_length', 'Username must be between 5 - 32 characters.', 'Nama pengguna harus antara 5 - 32 karakter.'),
(55, 'invalid_username_characters', 'Invalid username characters.', 'Karakter nama pengguna salah.'),
(56, 'email_already_exist', 'Email already exist.', 'Surel sudah digunakan.'),
(57, 'phone_number_already_used', 'Phone number already used.', 'Nomor telepon sudah digunakan.'),
(58, 'invalid_email', 'Invalid email.', 'Surel salah.'),
(59, 'password_too_short', 'Password too short.', 'Kata sandi terlalu pendek.'),
(60, 'password_mismatch', 'Password mismatch.', 'Kata sandi tidak sama.'),
(61, 'reCaptcha_error', 'Please check the re-captcha.', 'Mohon periksa kembali re-captcha.'),
(62, 'incorrect_username_or_password', 'Incorrect username or password.', 'Nama pengguna atau kata sandi salah.'),
(63, 'account_disabled_contact_admin', 'Your account has been disabled, please contact admin.', 'Akun Anda dinonaktifkan, silahkan hubungi admin.'),
(64, 'invalid_extension', 'Invalid extension.', 'Ekstensi salah.'),
(65, 'email_not_found', 'We can''t find this email.', 'Kami tidak dapat menemukan email tersebut.'),
(66, 'password_reset_request', 'Password Reset Request', 'Permintaan Atur Ulang Kata Sandi'),
(67, 'invalid_token', 'Invalid Token', 'Token Salah'),
(68, 'processing_error', 'An error found while processing your request, please try again later.', 'Kesalahan saat memproses permintaan Anda, silahkan coba lagi nanti.'),
(69, 'please_check_your_details', 'Please check your details.', 'Mohon periksa rincian Anda.'),
(70, 'please_choose_correct_date', 'Please choose correct date.', 'Mohon pilih tanggal yang benar.'),
(71, 'invalid_website', 'Invalid website.', 'Situs web salah.'),
(72, 'setting_successfully_updated', 'Setting successfully updated.', 'Pengaturan berhasil diubah.'),
(73, 'current_password_mismatch', 'Current password mismatch.', 'Kata sandi saat ini salah.'),
(74, 'you_must_fill_out_article_title', 'You must fill out article title.', 'Anda harus mengisi judul artikel.'),
(75, 'you_must_fill_out_article_content', 'You must fill out article content.', 'Anda harus mengisi konten artikel.'),
(76, 'please_fill_tags', 'Please fill the tags field.', 'Mohon isi form tag.'),
(77, 'invalid_source_url', 'Invalid source URL.', 'Sumber URL salah.'),
(78, 'please_upload_thumbnail_image', 'Please upload thumbnail image.', 'Mohon unggah gambar.'),
(79, 'please_check_your_start_time', 'Please check your start time.', 'Mohon periksa waktu mulai Anda.'),
(80, 'please_check_your_end_time', 'Please check your end time.', 'Mohon periksa waktu selesai Anda.'),
(81, 'event_successfully_published', 'Event successfully published.', 'Event berhasil dipublikasi.'),
(82, 'event_successfully_updated', 'Event successfully updated.', 'Event berhasil diubah.'),
(83, 'group_name_already_exist', 'Group name already exist.', 'Nama grup sudah digunakan.'),
(84, 'cannot_use_this_group_name', 'You can''t use this group name.', 'Anda tidak dapat menggunakan nama grup tersebut.'),
(85, 'group_name_characters_length', 'Group name must be between 5 - 32 characters.', 'Nama grup harus antara 5 - 32 karakter.'),
(86, 'invalid_group_name_characters', 'Invalid group name characters.', 'Karakter nama grup salah.'),
(87, 'group_successfully_created', 'Group successfully created.', 'Grup berhasil dibuat.'),
(88, 'language_already_exist', 'Language already exist.', 'Bahasa sudah ada.'),
(89, 'keyword_already_used', 'Keyword already exist.', 'Kata kunci sudah ada.'),
(90, 'your_confirmation_code_is', 'Your confirmation code is: ', 'Kode konfirmasi Anda adalah: '),
(91, 'failed_to_send_sms_code', 'Error while sending the SMS, please try another number or activate your account via email by logging into your account.', 'Kesalahan saat mengirim SMS, mohon coba dengan nomor lain atau aktivasi akun Anda melalui surel dengan masuk ke akun Anda.'),
(92, 'add_photo', 'Add Photo', 'Tambahkan Foto'),
(93, 'upload_your_photo', 'Upload your photo', 'Unggah foto Anda'),
(94, 'looks_nice', 'Look Nice!', 'Terlihat Bagus!'),
(95, 'cool', 'Cool! You''ll be able to add more to your profile later.', 'Bagus! Anda dapat menambah lebih banyak lagi ke profil Anda nanti.'),
(96, 'save_and_continue', 'Save and Continue', 'Simpan dan Lanjutkan'),
(97, 'skip_step', 'Skip Step', 'Lewati'),
(98, 'please_wait', 'Please wait', 'Mohon tunggu'),
(99, 'update_your_information', 'Update Your Information', 'Perbarui Informasi Anda'),
(100, 'first_name_label', 'First Name', 'Nama Depan'),
(101, 'last_name_label', 'Last Name', 'Nama Belakang'),
(102, 'current_password_label', 'Current Password', 'Kata Sandi Sekarang'),
(103, 'backup_completed', 'Backup Completed!', 'Backup Selesai!'),
(104, 'share_anything', 'Share anything...', 'Bagikan sesuatu...'),
(105, 'add_caption', 'Add a caption, if you like :)', 'Tambahkan caption, jika Anda suka :)'),
(106, 'verified_user', 'Verified User', 'Pengguna Terverifikasi'),
(107, 'maps_placeholder', 'Where are you?', 'Dimana Anda?'),
(108, 'post_label', 'Post', 'Pos'),
(109, 'like', 'Like', 'Suka'),
(110, 'note', 'note', 'catatan'),
(111, 'notes', 'notes', 'catatan'),
(112, 'no_more_posts', 'No More Post', 'Tidak Ada Lagi Pos'),
(113, 'edit_post', 'Edit Post', 'Ubah Pos'),
(114, 'edit_article', 'Edit Article', 'Ubah Artikel'),
(115, 'mark_as_sold', 'Mark as Sold', 'Tandai Terjual'),
(116, 'edit_product', 'Edit Product', 'Ubah Produk'),
(117, 'delete_post', 'Delete Post', 'Hapus Pos'),
(118, 'delete_article', 'Delete Article', 'Hapus Artikel'),
(119, 'add_photos', 'Add Photos', 'Tambahkan Foto'),
(120, 'open_post_in_new_tab', 'Open Post in New Tab', 'Buka Pos di Tab Baru'),
(121, 'open_article', 'Open Article', 'Buka Artikel'),
(122, 'unsave_post', 'Unsave Post', 'Batal Simpan Pos'),
(123, 'save_post', 'Save Post', 'Simpan Pos'),
(124, 'unreport_post', 'Unreport Post', 'Batal Laporkan Pos'),
(125, 'report_post', 'Report Post', 'Laporkan Pos'),
(126, 'say_something_nice', 'Say something nice', 'Katakan sesuatu yang bagus'),
(127, 'load_more_comments', 'Load More Comments', 'Lebih Banyak Komentar'),
(128, 'delete_label', 'Delete', 'Hapus'),
(129, 'edit_label', 'Edit', 'Ubah'),
(130, 'cancel_label', 'Cancel', 'Batal'),
(131, 'delete_post_confirmation', 'Are you sure want to delete this post?', 'Anda yakin untuk menghapus pos ini?'),
(132, 'delete_article_confirmation', 'Are you sure want to delete this article?', 'Anda yakin untuk menghapus artikel ini?'),
(133, 'view_more_posts', 'View {count} new posts', 'Lihat {count} pos baru'),
(134, 'drag_to_reposition', 'Drag to reposition', 'Geser untuk reposisi'),
(135, 'follow', 'Follow', 'Ikuti'),
(136, 'unfollow', 'Unfollow', 'Batal Ikuti'),
(137, 'requested', 'Requested', 'Permintaan Terkirim'),
(138, 'write_an_article', 'Write An Article', 'Tulis Artikel'),
(139, 'there_is_no_posts_yet', 'There is no posts yet.', 'Belum ada pos.'),
(140, 'there_is_no_articles_yet', 'There is no articles yet.', 'Belum ada artikel.'),
(141, 'there_is_no_saved_posts', 'There is no saved posts.', 'Tidak ada pos yang disimpan.'),
(142, 'have_no_posts', 'have no posts.', 'tidak ada pos.'),
(143, 'saved_posts', 'Saved Posts', 'Pos Disimpan'),
(144, 'recent_searches', 'Recent Searches', 'Pencarian Terakhir'),
(145, 'nothing_found', 'We can''t find about {query} :(', 'Kami tidak dapat menemukan {query} :('),
(146, 'have_no_articles', 'have no articles.', 'tidak ada artikel.'),
(147, 'send_a_message', 'Send a Message', 'Kirim Pesan'),
(148, 'block', 'Block', 'Blokir'),
(149, 'block_user_confirmation', 'Are you sure want to block {username}?', 'Anda yakin untuk memblokir {username}?'),
(150, 'nevermind_label', 'Nevermind', 'Lupakan'),
(151, 'block_label', 'Block', 'Blokir'),
(152, 'follows', 'follows', 'mengikuti'),
(153, 'no_post_found', 'No posts about {hashtag}', 'Tidak ada pos tentang {hashtag}'),
(154, 'comment', 'comment', 'komentar'),
(155, 'comments', 'comments', 'komentar'),
(156, 'share', 'share', 'bagikan'),
(157, 'posted_this', 'posted this', 'memposting ini'),
(158, 'profile_picture_changed_male', 'Changed his profile picture', 'Mengubah foto profile'),
(159, 'profile_picture_changed_female', 'Changed her profile picture', 'Mengubah foto profil'),
(160, 'profile_cover_picture_changed_male', 'Changed his profile cover', 'Mengubah cover'),
(161, 'profile_cover_picture_changed_female', 'Changed her profile cover', 'Mengubah cover'),
(162, 'writing_an_article', 'Writing an article', 'Menulis sebuah artikel'),
(163, 'search_posts', 'Search Posts', 'Cari Pos'),
(164, 'posts_about_query_not_found', 'Posts about {query} not found.', 'Pos tentang {query} tidak ditemukan.'),
(165, 'whats_new', 'What''s new?', 'Apa yang baru?'),
(166, 'shared_this_post', '{username} shared this post.', '{username} membagikan pos ini.'),
(167, 'hide', 'Hide', 'Sembunyikan'),
(168, 'follow_suggestions', 'Follow Suggestions', ''),
(169, 'follows_you', 'follows you', ''),
(170, 'joined', 'Joined', 'Bergabung'),
(171, 'edit_user', 'Edit User', 'Perbarui Pengguna'),
(172, 'privacy_setting', 'Privacy Setting', 'Pengaturan Privasi'),
(173, 'change_password', 'Change Password', 'Perbarui Kata Sandi'),
(174, 'social_links', 'Social Links', 'Tautan Sosial'),
(175, 'email_notification', 'Email Notification', 'Notifikasi Surel'),
(176, 'blocked_users', 'Blocked Users', 'Blok Pengguna'),
(177, 'delete_account', 'Delete Account', 'Hapus Akun'),
(178, 'phone_number_label', 'Phone Number', 'Nomor Telepon'),
(179, 'gender_label', 'Gender', 'Jenis Kelamin'),
(180, 'male', 'Male', 'Laki-laki'),
(181, 'female', 'Female', 'Perempuan'),
(182, 'bio_label', 'Bio', 'Bio'),
(183, 'website_label', 'Website', 'Situs Web'),
(184, 'day_label', 'Day', 'Hari'),
(185, 'month_label', 'Month', 'Bulan'),
(186, 'year_label', 'Year', 'Tahun'),
(187, 'user_type', 'User Type', 'Tipe Pengguna'),
(188, 'user', 'User', 'Pengguna'),
(189, 'admin', 'Admin', 'Admin'),
(190, 'moderator', 'Moderator', 'Moderator'),
(191, 'user_status', 'User Status', 'Status Pengguna'),
(192, 'inactive', 'Inactive', 'Tidak Aktif'),
(193, 'active', 'Active', 'Aktif'),
(194, 'verification', 'Verification', 'Verifikasi'),
(195, 'unverified', 'Unverified', 'Tidak Terverifikasi'),
(196, 'verified', 'Verified', 'Terverifikasi'),
(197, 'save_label', 'Save', 'Simpan'),
(198, 'remove_account_label', 'Remove Account', 'Hapus Akun'),
(199, 'account_removed', 'Account Removed :(', 'Akun Terhapus :('),
(200, 'message_privacy_label', 'Who can send me a message?', 'Siapa yang bisa mengirim saya pesan?'),
(201, 'everyone', 'Everyone', 'Semuanya'),
(202, 'people_i_follow', 'People I Follow', 'Orang yang saya ikuti'),
(203, 'follow_privacy_label', 'Confirm request when someone follows you?', 'Konfirmasi saat seseroang mengikuti Anda?'),
(204, 'no', 'No', 'Tidak'),
(205, 'yes', 'Yes', 'Ya'),
(206, 'visit_privacy_label', 'Send users a notification when I visit their profile?', 'Kirim notifikasi saat saya mengunjungi profilnya?'),
(207, 'profile_visit_help', 'If you don''t share your visit event, you won\\''t be able to see other people visiting your profile.', 'Jika Anda tidak mengaktifkan fitur ini, Anda tidak dapat mendapatkan notifikasi saat seseorang mengunjungi profil Anda.'),
(208, 'lastseen_privacy_label', 'Show last seen?', 'Tampilkan terakhir dilihat?'),
(209, 'lastseen_privacy_help', 'If you don''t share your last seen, you won\\''t be able to see other people last seen.', 'Jika Anda tidak mengaktifkan fitur ini, Anda tidak dapat melihat kapan seseorang terakhir terlihat.'),
(210, 'activities_privacy_label', 'Show my activities?', 'Tampilkan aktifitas saya?'),
(211, 'unblock', 'Unblock', 'Batalkan Blok'),
(212, 'blocked_users_not_found', 'Blocked users not found.', 'Tidak ada pengguna diblokir.'),
(213, 'address_label', 'Address', 'Alamat'),
(214, 'new_article', 'New Article', 'Artikel Baru'),
(215, 'title_label', 'Title', 'Judul'),
(216, 'tags_label', 'Tags', 'Tag'),
(217, 'source_label', 'Source', 'Sumber'),
(218, 'publish_label', 'Publish', 'Publikasi'),
(219, 'article_successfully_published', 'Article successfully published', 'Artikel berhasil dipublikasi'),
(220, 'article_successfully_edited', 'Article successfully edited', 'Artikel berhasil diperbarui'),
(221, 'read_more', 'Read More', 'Baca Lebih Banyak'),
(222, 'tags', 'Tags', 'Tag'),
(223, 'likes', 'Likes', 'Suka'),
(224, 'views', 'Views', 'Melihat'),
(225, 'write_new', 'Write New', 'Tulis Baru'),
(226, 'didnt_have_article_yet', 'didn''t have article yet :(', 'belum menulis artikel :('),
(227, 'ops', 'Ooops', 'Ooops'),
(228, 'thumbnail', 'Thumbnail', 'Thumbnail'),
(229, 'new_event', 'New Event', 'Event Baru'),
(230, 'going', 'Going', 'Datang'),
(231, 'interested', 'Interested', 'Interested'),
(232, 'invited', 'Invited', 'Diundang'),
(233, 'past', 'Past', 'Selesai'),
(234, 'name_label', 'Name', 'Nama'),
(235, 'location_label', 'Location', 'Lokasi'),
(236, 'start_date_label', 'Start Date', 'Tanggal Mulai'),
(237, 'end_date_label', 'End Date', 'Tanggal Selesai'),
(238, 'description_label', 'Description', 'Deskripsi'),
(239, 'go_label', 'Go', 'Ikut'),
(240, 'not_going_label', 'Not Going', 'Tidak Datang'),
(241, 'not_interested_label', 'Not Interested', 'Tidak Interested'),
(242, 'interested_label', 'Interested', 'Interested'),
(243, 'going_people', 'Going People', 'Orang yang Datang'),
(244, 'interested_people', 'Interested People', 'Orang yang Interested'),
(245, 'edit_event', 'Edit Event', 'Perbarui Event'),
(246, 'delete_event', 'Delete Event', 'Hapus Event'),
(247, 'invite', 'Invite', 'Undang'),
(248, 'no_result', 'No Result', 'Tidak Ada Hasil'),
(249, 'invite_your_friends', 'Invite your friends', 'Undang temanmu'),
(250, 'update_label', 'Update', 'Perbarui'),
(251, 'delete_event_confirmation', 'Are you sure want to delete this event?', 'Anda yakin untuk menghapus event ini?'),
(252, 'created_new_event', 'Created new event', 'Membuat event baru'),
(253, 'posted_on', 'Posted on', 'Diposting'),
(254, 'upcoming_events', 'Upcoming Events', 'Event Yang Akan Datang'),
(255, 'going_events', 'Going Events', 'Event Yang Diikuti'),
(256, 'interested_events', 'Interested Events', 'Event Yang Interested'),
(257, 'invited_events', 'Invited Events', 'Events Yang Diundang'),
(258, 'past_events', 'Past Events', 'Event Selesai'),
(259, 'events_not_found', 'Events not found.', 'Tidak ada event.'),
(260, 'happening_now', 'Happening Now', 'Terjadi Sekarang'),
(261, 'event_in_minutes', 'In {min_count} minutes', 'dalam {min_count} menit'),
(262, 'event_in_hours', 'In {hours_count} hours', 'dalam {hours_count} jam'),
(263, 'event_passed', 'Event Passed', 'Event Telah Selesai'),
(264, 'say_something_about_this_event', 'Say something about this event', 'Katakan sesuatu tentang event ini'),
(265, 'event_deleted', '{event_name} has been deleted', '{event_name} telah dihapus');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(2, 7, 112),
(3, 9, 119),
(7, 7, 123),
(8, 7, 139),
(10, 7, 172),
(11, 8, 189),
(12, 9, 193),
(13, 8, 194),
(14, 8, 187),
(15, 8, 111),
(16, 8, 24),
(17, 8, 143),
(20, 7, 23),
(21, 7, 113),
(22, 8, 198),
(25, 8, 201),
(26, 7, 202);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `text` text,
  `media` varchar(255) NOT NULL DEFAULT '',
  `media_file_name` varchar(200) NOT NULL DEFAULT '',
  `media_file_names` varchar(200) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `seen` int(11) NOT NULL DEFAULT '0',
  `deleted_one` enum('0','1') NOT NULL DEFAULT '0',
  `deleted_two` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_id`, `to_id`, `text`, `media`, `media_file_name`, `media_file_names`, `time`, `seen`, `deleted_one`, `deleted_two`) VALUES
(26, 8, 15, 'hai fajar', '', '', '', 1498496048, 1498496073, '0', '0'),
(27, 8, 15, 'tai maneh', '', '', '', 1498496050, 1498496073, '0', '0'),
(28, 8, 15, 'bodat', '', '', '', 1498496052, 1498496073, '0', '0'),
(29, 8, 15, 'kayak babi mukanya', '', '', '', 1498496056, 1498496073, '0', '0'),
(30, 15, 8, 'maneh juga babi', '', '', '', 1498496079, 1499131181, '0', '0'),
(31, 15, 8, 'hakan', 'uploads/photos/2017/06/7mtwyiyqdhXxMdBj9Miy_26_62c2e87eedd5b0d96acad9cbc9eb75a7_image.gif', 'giphy-downsized-large.gif', '', 1498496131, 1499131181, '0', '0'),
(32, 15, 8, 'haha', '', '', '', 1498496143, 1499131182, '0', '0'),
(33, 15, 8, '', 'uploads/photos/2017/06/CRUd3nT88anVGVBXWr42_26_4a70d6c0e0948b2230863ebd8d7c9ade_image.gif', '200w_d.gif', '', 1498496159, 1499131182, '0', '0'),
(38, 7, 8, 'hei', '', '', '', 1499277831, 1499766182, '1', '0'),
(39, 7, 10, 'tes', '', '', '', 1499277857, 0, '1', '0'),
(40, 7, 10, 'hell', '', '', '', 1499277867, 0, '1', '0'),
(41, 7, 10, 'yeah', '', '', '', 1499277870, 0, '1', '0'),
(42, 7, 10, 'bro', '', '', '', 1499277871, 0, '1', '0'),
(48, 7, 16, 'demohei', '', '', '', 1499280461, 0, '1', '0'),
(60, 7, 16, 'I hate the ending myself', 'uploads/photos/2017/07/ETr5ABB1XFtAadkvfsq8_06_18f52bb663243a698e04a2e757cf1839_image.png', 'Mobile Android PSDasasas.png', '', 1499283504, 0, '1', '0'),
(61, 7, 10, ':grimacing:', '', '', '', 1499284378, 0, '1', '0'),
(62, 7, 10, '[a]http%3A%2F%2Fwww.carovl.com[/a]', '', '', '', 1499284753, 0, '1', '0'),
(63, 7, 10, 'say that you stay :blush:', '', '', '', 1499284877, 0, '1', '0'),
(64, 7, 10, 'babi', '', '', '', 1499285339, 0, '1', '0'),
(65, 7, 10, 'say that you stay  :grimacing:', '', '', '', 1499285370, 0, '1', '0'),
(66, 7, 10, 'ah tai', '', '', '', 1499285439, 0, '1', '0'),
(67, 7, 10, 'sebuah proses yang alami  :smiley:', '', '', '', 1499285484, 0, '1', '0'),
(68, 7, 8, 'When the visions around you, bring tears to your eyes, and all that surrounds you, all secrets and lies.', '', '', '', 1500504951, 1500731659, '0', '0'),
(69, 7, 8, 'i&#039;ll be your strength', '', '', '', 1500504958, 1500731659, '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(255) NOT NULL,
  `notifier_id` int(11) NOT NULL DEFAULT '0',
  `recipient_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `seen_pop` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `type2` varchar(32) NOT NULL DEFAULT '',
  `text` text,
  `url` varchar(255) NOT NULL DEFAULT '',
  `seen` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notifier_id`, `recipient_id`, `post_id`, `group_id`, `event_id`, `seen_pop`, `type`, `type2`, `text`, `url`, `seen`, `time`) VALUES
(468, 7, 10, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1499277851),
(496, 7, 17, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1499286056),
(506, 7, 9, 0, 0, 0, 0, 'post_mention', '', '', 'index.php?page=post&id=202', 0, 1500732481),
(507, 8, 15, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=rahmanaharin', 0, 1500734681),
(510, 8, 7, 201, 0, 0, 0, 'liked_post', '', 'ku rasa in...', 'index.php?page=post&id=201', 1500734922, 1500734759),
(511, 7, 11, 0, 0, 0, 0, 'following', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1500735692),
(514, 7, 12, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1500855790),
(515, 7, 16, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1500856072),
(516, 7, 12, 0, 0, 0, 0, 'following', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1500951821),
(517, 7, 8, 0, 0, 0, 0, 'visited_profile', '', '', 'index.php?page=timeline&u=bonabrian', 0, 1500952076);

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE `policy` (
  `id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  `text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `policy`
--

INSERT INTO `policy` (`id`, `type`, `text`) VALUES
(1, 'terms_of_service', ''),
(2, 'privacy_policy', '<h4>What is Carovl?</h4>\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'),
(3, 'about', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `recipient_id` int(11) NOT NULL DEFAULT '0',
  `post_text` text,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `post_link` varchar(1000) NOT NULL DEFAULT '',
  `post_link_title` text,
  `post_link_image` varchar(100) NOT NULL DEFAULT '',
  `post_link_content` varchar(1000) NOT NULL DEFAULT '',
  `post_file` varchar(255) NOT NULL DEFAULT '',
  `post_file_name` varchar(200) NOT NULL DEFAULT '',
  `post_map` varchar(255) NOT NULL DEFAULT '',
  `post_privacy` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `post_listening` varchar(255) NOT NULL DEFAULT '',
  `post_watching` varchar(255) NOT NULL DEFAULT '',
  `post_feeling` varchar(255) NOT NULL DEFAULT '',
  `post_share` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(30) NOT NULL DEFAULT '',
  `edited` int(11) NOT NULL DEFAULT '0',
  `multi_image` enum('0','1') NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `article_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `registered` varchar(32) NOT NULL DEFAULT '0/0000',
  `album_name` varchar(52) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `post_id`, `user_id`, `recipient_id`, `post_text`, `group_id`, `post_link`, `post_link_title`, `post_link_image`, `post_link_content`, `post_file`, `post_file_name`, `post_map`, `post_privacy`, `post_listening`, `post_watching`, `post_feeling`, `post_share`, `post_type`, `edited`, `multi_image`, `product_id`, `article_id`, `event_id`, `time`, `registered`, `album_name`) VALUES
(21, 21, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/qH55F4arr9Uz49rqItXE_06_0fe49cd6b1ce7a9123ec4f4b478ec2b7_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1496700837, '6/2017', ''),
(23, 23, 8, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/t3EkPUADCm2qXpfTayHA_06_872c2cc9dba5c502e3709649738e4050_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture', 0, '0', 0, 0, 0, 1496701348, '6/2017', ''),
(24, 24, 9, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/7ycE2esJ1ObefPHW5TcF_06_f1459e450c6dab8c9d952409866b3056_avatar_full.JPG', '', '', '1', '', '', '', 0, 'profile_picture', 0, '0', 0, 0, 0, 1496701479, '6/2017', ''),
(29, 29, 7, 0, '#[1]', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1496739921, '6/2017', ''),
(31, 31, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/rVLDMSnWAiY8ETH3rC1G_07_b62faa414cc09d92665c85354c0251b0_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1496833269, '6/2017', ''),
(74, 74, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/JKGivCD9KtFJI1s643l8_10_7eed39b24b4bcf3ef3fb928dd36fd605_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497049016, '6/2017', ''),
(75, 75, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/2779LIP7213NVEAR6nU6_10_28fa9b2234a5aa5d3e2165a818dc90ed_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497089138, '6/2017', ''),
(76, 76, 7, 0, 'Ini group Carovl', 4, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497091203, '6/2017', ''),
(77, 77, 7, 0, 'Semua info tentang Carovl ada disini', 4, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497091218, '6/2017', ''),
(81, 81, 8, 0, 'hai carovl group', 4, '', '', '', '', '', '', '', '2', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497110921, '6/2017', ''),
(84, 84, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/g4KrU5psQ1l6k8ITsqvc_11_b3224009cbb8c0a7133888a05f4003db_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497115198, '6/2017', ''),
(85, 85, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/zBuwGTmNTRTwdUdjhtPT_11_2df639e7fd2376998a56ede8a3db299e_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497117711, '6/2017', ''),
(86, 86, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/St9Iu4PCOSQKFI7wQxEZ_11_ec4816a251281cea119e1a053bcb0b73_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497117735, '6/2017', ''),
(87, 87, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/Ug2VR9KrYRIwLYabtz9c_11_c9ba0f24c2cc86c4d5a44f27301528f4_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497118610, '6/2017', ''),
(88, 88, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/ZSKUZLdIQs4uIh2KiPhR_11_fa7d31ae667421ee63582764fa5fef96_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497118619, '6/2017', ''),
(89, 89, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/suTByiTNtlCnOI8GvuDi_11_35d2644251b85443023d92e974dbd46d_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497118671, '6/2017', ''),
(90, 90, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/Gsa8pohcjrLChxIntsY2_11_c0f69e382cc8ecba30cf2877d2f17ff0_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497118678, '6/2017', ''),
(91, 91, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/qO8rdfUkCM7zdL2ryXjn_11_0135274c6eb482708a3f7b3b540ee27d_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497118756, '6/2017', ''),
(93, 93, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/kiLLbqcNu4KKIphQENNK_11_79ec63604cdf845272ba846186d61212_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497120292, '6/2017', ''),
(94, 94, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/kz3QQjxV2ybKeOPvK5fu_11_e389d1f1132c022ccbc62d2c8b8d530c_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497120354, '6/2017', ''),
(95, 95, 7, 0, NULL, 4, '', NULL, '', '', 'uploads/photos/2017/06/gUryM725NbBRX9exp16b_11_cd0e0f19b601a10dea89584f675ffdb5_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497120681, '6/2017', ''),
(97, 97, 7, 0, '[a]http%3A%2F%2Fwww.carovl.com[/a]', 4, '', '', '', '', '', '', '', '2', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497124018, '6/2017', ''),
(103, 103, 7, 0, 'Post', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497210108, '6/2017', ''),
(107, 107, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/AzPMro5AT8UljUuxEofj_14_7f946b0a53295625b0b8d3a4bb0dd2d2_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497438297, '6/2017', ''),
(109, 109, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/cW7HHOjMcokNibTNKytE_15_a2be5bb29428c56d9ef8b3ac69094bbf_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1497508158, '6/2017', ''),
(110, 110, 10, 0, 'Post', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497527895, '6/2017', ''),
(111, 111, 9, 0, 'hei semua', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497528746, '6/2017', ''),
(112, 112, 8, 0, 'hai aku rahma @[7]', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497528772, '6/2017', ''),
(113, 113, 8, 0, '', 0, '', '', '', '', 'uploads/photos/2017/06/ExtdDt4JzFlgBJnPm5Tn_15_4cd4962e679a5920e21c2507c53d7fbd_image.jpg', 'oil.jpg', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497528799, '6/2017', ''),
(114, 114, 15, 0, 'hai eperiwan', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497528921, '6/2017', ''),
(115, 115, 15, 0, '', 0, '', '', '', '', 'uploads/photos/2017/06/33DkpKUaJd29YgQOss7M_15_2ba3c62fff9b26e222cb289feda9cffb_image.jpg', 'oil.jpg', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497528930, '6/2017', ''),
(116, 116, 15, 0, '', 0, '', '', '', '', '', '', 'Bandung', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497529765, '6/2017', ''),
(119, 119, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 7, 1497534961, '6/2017', ''),
(120, 120, 7, 0, 'Like hell haha', 0, '', '', '', '', 'uploads/photos/2017/06/7j8xdM5EvAvteeiCn2Y6_21_69be98f2d63649a6e2bab2158eafcd96_image.gif', 'giphy.gif', '', '0', '', '', '', 0, 'post', 1497984262, '0', 0, 0, 0, 1497984240, '6/2017', ''),
(121, 121, 7, 0, '', 0, '', '', '', '', 'uploads/photos/2017/06/2eqeKgCBqzxm6hSIuB4A_21_9ad68a8a2cc007a041d4f0b841009581_image.gif', 'giphy (1).gif', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497992409, '6/2017', ''),
(123, 123, 7, 0, '', 0, '', '', '', '', 'uploads/photos/2017/06/BbzaKKG89IpGLAe46n43_21_cd378eadb0db78ad685eba4ff4f9cf25_image.gif', 'giphy-downsized-large.gif', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1497996398, '6/2017', ''),
(137, 137, 7, 0, '#[4]#[6]', 0, '', NULL, '', '', '', '', '', '1', '', '', '', 0, 'post', 0, '0', 0, 13, 0, 1498003438, '6/2017', ''),
(139, 139, 9, 0, '', 0, '', '', '', '', 'uploads/videos/2017/06/5Drr7mstXnQuvhqdeQoK_22_31d56cba85c0e31493a0a39b3f84a46e_video.mp4', 'motion graphics [ After Effects ] 2D.mp4', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498078365, '6/2017', ''),
(142, 142, 17, 0, '', 0, '', '', '', '', 'uploads/audios/2017/06/13VuplWj3PeblZsH2wDT_22_e054d8f7c5237b04cbcf7a6bedbc6997_audio.mp3', 'Ain&#039;t No Rest For The Wicked - Cage The Elephant (mp3goo.com).mp3', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498079221, '6/2017', ''),
(143, 143, 7, 0, 'my inspiration :)', 0, '', '', '', '', 'uploads/videos/2017/06/aQXzRgmIsjSr1LByp3fu_22_5031dc83eebd4e3f71795c251521ea9d_video.mp4', 'Motion Graphic   INSPIRATION.mp4', '', '0', '', '', '', 0, 'post', 1498079646, '0', 0, 0, 0, 1498079625, '6/2017', ''),
(145, 112, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '1', '', '', '', 1, '', 0, '0', 0, 0, 0, 1498308730, '0/0000', ''),
(146, 146, 7, 0, 'Hai #[4]', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498477375, '6/2017', ''),
(147, 147, 7, 0, 'this is #[4]', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498477392, '6/2017', ''),
(152, 152, 7, 0, 'post', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492878, '6/2017', ''),
(153, 153, 7, 0, 'lagi', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492883, '6/2017', ''),
(154, 154, 7, 0, 'post lagi', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492888, '6/2017', ''),
(156, 156, 7, 0, 'tes', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492900, '6/2017', ''),
(157, 157, 7, 0, 'lagi', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492907, '6/2017', ''),
(158, 158, 7, 0, 'lagi test lagi', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492926, '6/2017', ''),
(160, 160, 7, 0, 'tai lah ih', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492955, '6/2017', ''),
(161, 161, 7, 0, 'but amazing', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498492960, '6/2017', ''),
(162, 162, 7, 0, 'ko gini ya?', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498493005, '6/2017', ''),
(163, 163, 7, 0, 'ada apa lagi nich?', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498493014, '6/2017', ''),
(168, 168, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/FXbNYc9VdZr8edh8QmHD_27_bc5d0ac35509303dece0c0e5e094a0d1_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1498496859, '6/2017', ''),
(169, 169, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/dsdxOhEpxGaRoCGIptf8_27_1d9aafac09dc3fe809aba7c7c58cf167_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1498496895, '6/2017', ''),
(170, 170, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/sllpYgO5h2Wkk65BlvFR_27_9ed8a4532e51f125fdfcecb96c0edcd0_avatar_full.JPG', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1498498015, '6/2017', ''),
(171, 171, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/06/oLjTc1qzumTYNltQBPyk_27_ca9d3a78e2424adf5c3bb581afeb90f2_cover_full.JPG', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1498498075, '6/2017', ''),
(172, 172, 7, 0, 'This is shit like you, don&#039;t you?', 0, '', '', '', '', 'uploads/photos/2017/06/la2qkx3mTAOcEPrpLT7K_27_0660c61ac8a9d5d1f09e836555ce20ac_image.jpg', '20161105_140238.jpg', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1498503475, '6/2017', ''),
(180, 180, 7, 0, '[a]http%3A%2F%2Flocalhost%2Fthecarovl%2F[/a] <br>fak you bitch', 0, 'http://localhost/thecarovl/', 'Carovl', 'uploads/photos/2017/07/xkSZjOtJAiZtPRNsgsWK_url_image.jpg', 'Desc', '', '', '', '0', '', '', '', 0, 'post', 1499857465, '0', 0, 0, 0, 1499127724, '7/2017', ''),
(181, 181, 7, 0, 'Every word I say is true, this I promise you', 0, '', '', '', '', 'uploads/photos/2017/07/cbMh9mhFUe1KE9IL8Lza_04_0d9f4481fb30c452783f2b8c579d6777_image.jpg', 'fallen_angel_by_deathbycuriousity-d3dxskd.jpg', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1499129811, '7/2017', ''),
(187, 187, 7, 0, 'Awan jingga, gelap ku rasa di dada. <br>Langit ku meresah sendu. <br>Bayang dirimu menjauh. <br> <br>Biarkanlah cinta diam dalam nada, hingga... <br> <br>Hariku berlalu bersama datangnya rindu, membawa semua mimpiku. <br>Ku tau, di hati selalu ada dirimu temani sepiku. <br> <br>Bersama senja, menyimpan rasa ini sendiri.', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1499272949, '7/2017', ''),
(188, 188, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/6TTmYKnap69dIlS3fktV_08_19375a40bd63b582abf0c952f06fdc50_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_cover_picture', 0, '0', 0, 0, 0, 1499486373, '7/2017', ''),
(189, 189, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/OKWAdadJX1nvLKbFzwXp_09_f8d1887d169456e0b82efe5ff80618ff_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1499589517, '7/2017', ''),
(190, 190, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/pPevxCmln7vAgoaeMtd1_11_e954472b8011b40ce7eefa016f38293f_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture', 0, '0', 0, 0, 0, 1499770829, '7/2017', ''),
(191, 191, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/kkcs5DWm7lifvEWDnbda_11_b626f664b1dd2cd2f938d1e95d3a93c5_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture_deleted', 0, '0', 0, 0, 0, 1499770841, '7/2017', ''),
(192, 192, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/D187zFntI3MhbB3JjE6C_11_f6ccfc4390624d907b7c5f45e407d9b1_cover_full.jpg', '', '', '1', '', '', '', 0, 'profile_cover_picture', 0, '0', 0, 0, 0, 1499770861, '7/2017', ''),
(193, 193, 7, 0, '[a]http%3A%2F%2Flocalhost%2Fthecarovl%2Fbonabrian[/a]', 0, 'http://localhost/thecarovl/bonabrian', 'Bona Brian Siagian (@bonabrian)', 'uploads/photos/2017/07/6ygb8s9CPVARmp1pNzsk_url_image.jpg', 'Ambition make you look pretty ugly.', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1499771108, '7/2017', ''),
(194, 194, 7, 0, 'Bengeutmu coy!', 0, '', '', '', '', 'uploads/photos/2017/07/evWKX6FiFcTGx5XuWGZ6_12_8b7598a92a6649f3d99067169971e178_image.jpg', 'IMG20170702151419.jpg', '', '0', '', '', '', 0, 'post', 1499857679, '0', 0, 0, 0, 1499857650, '7/2017', ''),
(195, 194, 8, 0, NULL, 0, '', NULL, '', '', '', '', '', '1', '', '', '', 1, '', 0, '0', 0, 0, 0, 1499863495, '0/0000', ''),
(198, 198, 7, 0, '[a]https%3A%2F%2Fwww.instagram.com%2Fbonabrian[/a]', 0, 'https://www.instagram.com/bonabrian', 'Bona Brian Siagian (@bonabrian) • Instagram photos and videos', 'uploads/photos/2017/07/ZMzOaHST8DXpIr32trel_url_image.jpg', '325 Followers, 181 Following, 40 Posts - See Instagram photos and videos from Bona Brian Siagian (@bonabrian)', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1499923068, '7/2017', ''),
(199, 199, 7, 0, 'Sorry I can&#039;t be perfect :(', 4, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1499968491, '7/2017', ''),
(200, 200, 7, 0, NULL, 0, '', NULL, '', '', 'uploads/photos/2017/07/bqUjxpxa2TsstaLhOAxv_14_8c9a162c3e171bdfeed5182bfe8f5157_avatar_full.jpg', '', '', '1', '', '', '', 0, 'profile_picture', 0, '0', 0, 0, 0, 1499968937, '7/2017', ''),
(201, 201, 7, 0, 'ku rasa ini semua sudah terasa basi :)', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1500732377, '7/2017', ''),
(202, 202, 7, 0, '@[9] ahhhh', 0, '', '', '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 0, 0, 0, 1500732481, '7/2017', ''),
(203, 203, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 1, 0, 0, 1500897886, '7/2017', ''),
(204, 204, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 2, 0, 0, 1500945521, '7/2017', ''),
(205, 205, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 3, 0, 0, 1500947871, '7/2017', ''),
(207, 207, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 5, 0, 0, 1500952372, '7/2017', ''),
(208, 208, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 6, 0, 0, 1501055431, '7/2017', ''),
(209, 209, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 7, 0, 0, 1501055579, '7/2017', ''),
(210, 210, 7, 0, NULL, 0, '', NULL, '', '', '', '', '', '0', '', '', '', 0, 'post', 0, '0', 8, 0, 0, 1501059742, '7/2017', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `category` int(11) NOT NULL DEFAULT '0',
  `price` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.00',
  `location` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `name`, `description`, `category`, `price`, `location`, `status`, `type`, `time`, `active`) VALUES
(1, 7, 'Carovl', 'Carovl Description', 1, '50000', 'Bandung', 0, '0', 1500897885, '1'),
(2, 7, 'Carovl', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod <br>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <br>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse <br>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non <br>proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, '250000', 'Bandung', 0, '0', 1500945521, '1'),
(3, 7, 'Carovl', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod <br>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <br>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse <br>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non <br>proident, sunt in culpa qui officia deserunt mollit anim id est laborum. <br>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod <br>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <br>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse <br>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non <br>proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, '50000000', 'Bandung', 0, '0', 1500947871, '1'),
(5, 7, 'Bring Me The Horizon', 'BMTH <br>[a]http%3A%2F%2Fbmth.com[/a]', 1, '50000', 'Bandung', 0, '0', 1500952372, '1'),
(6, 7, 'Idiot Pilot', 'The pilot is an idiot :v', 1, '200000', 'Bandung', 0, '0', 1501055431, '1'),
(7, 7, 'Pilot Idiot', 'Idiot Pilot', 1, '50000', 'Jakarta', 0, '0', 1501055579, '1'),
(8, 7, 'Retina and The Sky', 'Idiot Pilot bungggggg!', 1, '200000', 'Jakarta', 0, '0', 1501059742, '1');

-- --------------------------------------------------------

--
-- Table structure for table `products_media`
--

CREATE TABLE `products_media` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_media`
--

INSERT INTO `products_media` (`id`, `product_id`, `image`) VALUES
(1, 1, 'uploads/photos/2017/07/Z31FGlNDsIyrS3UlDfZs_24_36fca57481247b510974c0e4886f3308_image.jpg'),
(2, 1, 'uploads/photos/2017/07/b3yGWy6AbU4JWqgclVMC_24_f64388d1772d46b09385e78ea55b914b_image.jpg'),
(3, 1, 'uploads/photos/2017/07/e7gQZvTgU4VeUzuaFmda_24_dc4cf722d8f830d0b5a9cdee5c955166_image.jpg'),
(4, 2, 'uploads/photos/2017/07/HGbVyfNWHzWEC4jOvgms_25_22c6277dd6e745431c564bbf2d6dcb89_image.jpg'),
(5, 3, 'uploads/photos/2017/07/AVPUXrhpegMpZQtQgS7F_25_a89697433fbce6618608e8f76e3d3bc8_image.jpg'),
(6, 3, 'uploads/photos/2017/07/lpQlchhJdrwaQdnGzqyv_25_5e9311ec91598b32ee41fcc2c1f7b3cb_image.jpg'),
(10, 5, 'uploads/photos/2017/07/EzbNrLK5Ew8WFIY8vRhf_25_537955407d9696989e2d566370fb53eb_image.jpg'),
(11, 6, 'uploads/photos/2017/07/ekWNqZ2lQAZsWVTc7rvB_26_13137835d40619896b03af6845a22a80_image.jpg'),
(12, 6, 'uploads/photos/2017/07/Aaatp6jOiYM2wkejemWE_26_400f771f2c4466d37439af31b943b4be_image.jpg'),
(13, 6, 'uploads/photos/2017/07/ye3nswokIYjsJd4rk2JP_26_391adbeb9ca26459b5318ea83e7f61df_image.jpg'),
(14, 7, 'uploads/photos/2017/07/zOCWzofFqHwWEiICI9aK_26_d204badb177603133c172431a6f94a87_image.jpg'),
(15, 8, 'uploads/photos/2017/07/CFYXMVvXaG6Xei5HJixr_26_4efb9124b74b45db1fe7c709029f27c9_image.jpg'),
(16, 8, 'uploads/photos/2017/07/BZVvSSoGVnKSRMnUlhXR_26_59c89da70645c81163a5242aaf810c16_image.jpg'),
(17, 8, 'uploads/photos/2017/07/GtwfbmSBshM1kHeNZXkq_26_216dcfd36bd55317d5ee4de4f84e8cfc_image.jpg'),
(18, 8, 'uploads/photos/2017/07/VDgXuLp79VfyhKRN3t5v_26_e85504020b03176e7d7da570d684a97b_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profile_fields`
--

CREATE TABLE `profile_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `type` text COLLATE utf8_unicode_ci,
  `length` int(11) NOT NULL DEFAULT '0',
  `placement` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'profile',
  `registration_page` int(11) NOT NULL DEFAULT '0',
  `profile_page` int(11) NOT NULL DEFAULT '0',
  `select_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recent_searches`
--

CREATE TABLE `recent_searches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `search_id` int(11) NOT NULL DEFAULT '0',
  `search_type` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recent_searches`
--

INSERT INTO `recent_searches` (`id`, `user_id`, `search_id`, `search_type`) VALUES
(71, 9, 7, 'user'),
(150, 8, 1, 'groups'),
(180, 7, 1, 'groups'),
(183, 9, 1, 'groups'),
(187, 8, 2, 'groups'),
(188, 8, 3, 'groups'),
(190, 8, 4, 'groups'),
(191, 9, 4, 'groups'),
(192, 7, 5, 'groups'),
(194, 9, 8, 'user'),
(195, 8, 15, 'user'),
(198, 15, 9, 'user'),
(200, 15, 8, 'user'),
(234, 8, 7, 'user'),
(235, 8, 16, 'user'),
(238, 17, 9, 'user'),
(239, 17, 8, 'user'),
(240, 8, 12, 'user'),
(247, 15, 7, 'user'),
(251, 7, 9, 'user'),
(253, 7, 10, 'user'),
(267, 7, 16, 'user'),
(274, 17, 7, 'user'),
(279, 7, 17, 'user'),
(281, 7, 15, 'user'),
(283, 8, 9, 'user'),
(284, 7, 8, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `seen` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `saved_posts`
--

INSERT INTO `saved_posts` (`id`, `user_id`, `post_id`) VALUES
(1, 7, 113);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `session_id` varchar(120) NOT NULL DEFAULT '',
  `platform` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `session_id`, `platform`, `time`) VALUES
(30, 2, '2466c5d117a102c70671fca9ffc2e408c3dfadc9b7ebc0fbb80419ec6e1a761a0e8fbd00165120442652cf38361a209088302ba2b8b7f51e0', 'web', 1496075545),
(32, 2, 'cf623bedb915ef13e2c7f17b55ceccfaeb2893cf7564719030db4a9228cd0d526188af9b5246310768b1ecf6d8049bb062a356f1cc812e69e', 'web', 1496075545),
(34, 2, 'f9c6af19593298e2bd5ec8960871471f5300bb5ff2dcfe7d45186515ea08a165cc3e8068276041666c35bc9eaa4a930e006ab98bf3ef90408', 'web', 1496075545),
(74, 2, '93d29069bd541668cf4aa92961073da0b8362fe13f55ec03fa157d1f319ff7d87e9acfeb78065321927e838a450e2fe6225edfc3d12e2463', 'web', 1496075545),
(109, 2, '0703650633f0a500bc9bbb3731456a328ac863d540ee273fed9d74775754cfe91005524d962402343fe1f9c70bdf347497e1a01b6c486bdb9', 'web', 1496075545),
(136, 2, '352f99251034c059b8c970ec4d2f6e8cd161fcc162f8813fb13d591869cb6fd6b12e150855156792576908ab39c07d85c0b3693ce5d700771', 'web', 1496075545),
(149, 2, '471f47c1b76f2faaa6607ceb5db6f1cfd161aae69b2032121ac7670a99583d4b2cd818127920735676f6d7ea73f8b34354a3ecc69f872abfd', 'web', 1496075545),
(150, 2, 'fe6ff99216292d72f14b8954a53e69c1ce0307609f6f5ed4cd4ed7872f8cd896fbe512e3750379774b555f4a2901228cc826327079761e696', 'web', 1496075545),
(159, 1, '3ca30e5e1cd6f014245cf528d632ead86bd1487ab97e7557e07dda7534132278517f1307730061848439fca360bc99c315c5882c4432ae7a4', 'web', 1496074714),
(160, 1, 'ef2da05ac39d9aca3a8551694ef476b54da825f9be832f40e429290821c3bf95e11d8ffc3823242185d8c6ee0d8964e66a3225458f981522d', 'web', 1496074714),
(167, 2, '6a0176bf4866d46dc4d1df0f2b57c67e53bfd4742dffdde359f0cd5c1309c7f068315d43814643012da60b579faebe684e2a2bf90d4e50c82', 'web', 1496075545),
(168, 1, 'bcf03efc7fac0a2e9baaede925c22142f9f5ad248833ff3059e3d028a19c8a0223e381762320149732107931de60c5a7c5d526bd1d6a8a34d', 'web', 1496074714),
(171, 2, '24d9dd3213265a3c7ad9b6deb5328503a18ab3458fa36a89a82f57e4e7edf57c0672eb9571134440168881d2246abebc3aa474b51ecd7773e', 'web', 1496075545),
(173, 2, '8a5f6644b2d71729ba89e0b9e6ebc5009ae38c9f689394b646251485b43e231144d448524715169279cd013fe250ebffc853b386569ab18c0', 'web', 1496075545),
(174, 1, 'd6ddddb0d6b2305a402bc90b48363b736364ec5ddfda096ddddf36a72d5ff55159cbf23a288357204a4a587f3d0835928d30c2253f0624953', 'web', 1496074714),
(175, 1, '7a04897e0eb866366728ce7e4ae3ac674150d0fb237856f89d1ff9d9620d25cf03c8e14879478624128ce9bc954876829eeb56ff46da8e1ab', 'web', 1496074714),
(176, 1, '56d232e913940cc3b47f5b50e9799201f760dc346f08c976adf20b4eba50936c2c66456a1327582466a783b626a6d892a132dc195e5504272', 'web', 1496074714),
(177, 1, '5558b329586f7017cbca12f6db79f403a496aae93bbb777823a6fc97b75dc875602f0f7c6670464408804f94e16ba5b680e239a554a08f7d2', 'web', 1496074714),
(178, 1, '2202b27cbd76fb4cc8b83875db2b654ad06a0f41553481d9070c6f7a85fd4e119d088766159586588acaa23f71f963e96c8847585e71352d6', 'web', 1496074714),
(179, 1, 'e361b0b6066362c77bf1428a51069475f2a38d7437b27a427c794d2a25da10686d9cac8d873236762c95fd5284717784f1b6a1b907d33a62b', 'web', 1496074714),
(180, 1, '96caf53e2fa055c5cebf4c3eb16c5a97d9f942b9f45f4de4d9b42ab2547a2a2680d5b19a381700306d378765f17a856b7ba8bf1541cafb69', 'web', 1496074714),
(181, 1, '5d2b75e078a4d576add9a434fdb945df585b7209ed48e18eb5fc22fe37235719d6671b2c4962836376933b5648c59d618bbb30986c84080fe', 'web', 1496074714),
(182, 1, 'c5c59f549bc3882fb6ca7c263582c9ef395e257c9f302dd1937825829398a50d168f5f5f6012912324d42d2f5010c1c13f23492a35645d6a7', 'web', 1496074714),
(183, 1, 'c31f94b740696ab52c4e3c5868f005fc2cc763c2e83b71965df4f95f8b48faac8b2cabb2501410590500ee9106e0e4d8f769fadfdf9f2837e', 'web', 1496074714),
(184, 1, '745216d687f20abacdcfa2a700aaea8c9b973fecec0fb3ef9766728d3487e87dcefe4b879296061198252831b9fce7a49421e622c14ce0f65', 'web', 1496074714),
(187, 1, '9f2c2b291bc2f9763f9681c832a27c4956f11a06b092cb7662175523873443dbdcacb7ef345675998b80ba73857eed2a36dc7640e2310055a', 'web', 1496074714),
(189, 3, '6b56904a26a690daf49f3c8707a67fa7e4bacb4d4e1b640b6c58bc49edfa06c97dd8c7355967068145c2631d54272554b181cf21ad2171fa3', 'web', 1496075447),
(193, 3, '5da3e37eda88f6b0c1c574afb58cc11df24feebbceeaebf92729286ad3e27f62eb2f3adb280056423a322852ce0df73e204b7e67cbbef0d0a', 'web', 1496075447),
(195, 1, '5d34bb225893dda118b5bb1750622ab56d770757c5fd70ea13366c17a65851f65020d336171630859ff7a2112f8c3e3224ce8e3e26de1d932', 'web', 1496074714),
(196, 1, '5527f8f8aaf6f7f749a322476bb1c3316280f8b59d2bf577780052e355dd3dc9a89e97618043891052107931de60c5a7c5d526bd1d6a8a34d', 'web', 1496074714),
(197, 1, 'ca209d8bed52399388dbd95094aaedb3b0123a76fbc6284765e592ecd87288a04e02883e8231065539d3d9b8861c97e6bb5992d51d3232f8b', 'web', 1496074714),
(198, 1, 'c098a7bdcba69d2143e191e9538917a4709f7d553c80d7696632c3c65a6a31d3cefeea9e42030164986636b6605b2bcb9a738d0e9bbce3c43', 'web', 1496074714),
(199, 1, 'ca36199e6c6c1cf2dd4d35d2c706bc48c16117c801c5b3667bed68dfe37cbcb5a3344d77543484157ef9280fbc5317f17d480e4d4f61b3751', 'web', 1496074714),
(200, 3, '8983552cb05b6b2a479e69224149780f2bb6be10db37ac5e42b14b9e58e0dc061c83fb3091875542599cd3843754d20ec3c5885d805db8a32', 'web', 1496075447),
(202, 3, '24080a23dc4ae359d55d08d82bcbf0b0c2a14e1e94e527833209abea4707df0e2e802a8228021918ecf9902e0f61677c8de25ae60b654669', 'web', 1496075447),
(203, 1, '86f799ffce52eea2c7f4caea77a1ea0e531b58ef18289861152cdd953f72dc04d1588bda447157117305ddad049f65a2c241dbb6e6f746c54', 'web', 1496074714),
(214, 1, 'f584fdd1faaf8f63667b2fb55715ab45834e99d34e673a87af199e1791217dff8370d397921413845cf5ff72ca35f112b361de3e312c088f4', 'web', 1496074714),
(215, 1, '64c960f4a8ea80e8cc7752ea1fac8332647e0e54df2831fa655e0944e708aac20f10a4c2629530164d6f84c02e2a54908d96f410083beb6e0', 'web', 1496074714),
(218, 1, '632f42b414e50bf8491bce6443a2775ceb93b06920038c05b3366e2e6ed3a77cd5c4d83d371554904c4bf1e24f3e6f92ca9dfd9a7a1a1049c', 'web', 1496074714),
(219, 1, '8d31df80d15e072c6e72464420a9e93ba29f1c17e71cd27558330653ce175a15d9d1c00c134467230b02d46e8a3d8d9fd6028f3f2c2495864', 'web', 1496074714),
(220, 1, '69b52a0b972ce889639df44b61a937502d668e24c3cca06ec3c6a823a6522805b16031c527582465213403518ef9c1ce843a289d991f79bf5', 'web', 1496074714),
(221, 1, 'e79f42e6dfac7ea44365a5fc8df3f0526b07d9e0a5282859b4723a2ac133ef5145970264723225911ade1d98c5ab2997e867b1151a5c5028d', 'web', 1496074714),
(222, 1, '3c32b9d26fe4c1a5f2cfaf09e6f41a6bb830bd2e847171906ad34476c501d96dc19520d9213161892185e48a43c7f63acf74b1bd58827b510', 'web', 1496074714),
(223, 1, '69b6fe7c75d2afdcfffcb877663a6d06bdbcc522dbf0c6d09568b1a2f4e90c651bfa02458171657987cca4a9404acc5243f0b77c16ff4a477', 'web', 1496074714),
(232, 1, '183c421cc249cf92e0b1a18e3c43185587d9c8e1d110331f43e745423660f72c06ddece9915852864a1324603d9b1a22277809229934a36fd', 'web', 1496074714),
(233, 1, 'ff6783936ba45b374fa6bc09ece0bcfffd589e21c653ce9847433be5ad717f74e0a0ea1c970377604009c434cab57de48a31f6b669e7ba266', 'web', 1496074714),
(234, 1, '27fbe71349aaa1d895b4a12d0770facc6f5d559ae33938b36568b58747ff36ea698eb857963541666e20b21ae6508f22fc189c60a0880d0b8', 'web', 1496074714),
(235, 1, '0e97e125faf7ba51e63c761eaeafadd62e3406660aa4f4441de44471cecee9efb94f13d49834798171c336b8080f82bcc2cd2499b4c57261d', 'web', 1496074714),
(236, 1, 'b5e57d36f9540dbe40896eb7b7a1b98189e6a8835dfcf8700aa52888209d087a917a50d8540581597a883bbca3f8bc8814ff676cb0e91829a', 'web', 1496074714),
(237, 1, '89f7fe61fe3c461eb59b118e02f5a01082b39d2ec96a9301a76e7b5a406bbc68abf1bf5c591227213c404a5adbf90e09631678b13b05d9d7a', 'web', 1496074714),
(238, 1, 'cef9bb4329d9bd89537c03798f9c7b16011032e387b0cf9753f2e5124a59f6a225859d3983935546873740ea85c4ec25f00f9acbd859f861d', 'web', 1496074714),
(239, 1, 'ff137a3d330e4e62426026e3213eee2ea86cd18d4ac7176fefc49d053047c30244892dce974283854e48a900a95c8e0a3db31da9fbad6866e', 'web', 1496074714),
(241, 1, '62ab2cbdc4295a20b59bc6d1734ce454157e2701e302868b0b38ef767e3b306f8713993c5540907117806689d934e610d660caf5536fea0b2', 'web', 1496074714),
(242, 1, 'ec497bc5dfea7b79a00a7f1bd75536fe7d166e5ea6b9ab04923fa000404764ac68b983a34795735676d6081760ded88d3807d3562178ecabb', 'web', 1496074714),
(243, 1, '93e31e2551b34dbe3807504227a6bcaa42d18ae0de3104e52ddacdaaf025c2ceff29586253219943583187550749e6b8024a097630f9d4722', 'web', 1496074714),
(244, 1, '69c09601edb924d57abc06a8a995398e8616ad2fa23dd00f96fb3283b9ede0b0f5318294453152126eddc3427c5d77843c2253f1e799fe933', 'web', 1496074714),
(246, 1, 'a8f759fdaace86fa2dffd0988ff53cfe85970f0b67fa922cb1568d8e9995ecec9626a83c415608723f52b43f6e0e444510cf55c5869d8d06b', 'web', 1496074714),
(248, 3, '7021df29262a222de7c3b87732caaf31b9058d3e477e797e8be6b2595570581a976fbe1e51654730892350dee085a781753d9301fea11d51c', 'web', 1496075447),
(249, 1, '2544aa3e681a4930cbe8fdff60909db1d02761b95a878d9a8c6085a522f8d2132895eb8a19208441828ce9bc954876829eeb56ff46da8e1ab', 'web', 1496074714),
(250, 1, '78aec87f1b010764ec122aead7e51d7d6698d786464b4b937a07c9dd5251d91c6f389429660698784374939012129c174e451f0f64be3bfea', 'web', 1496074714),
(251, 1, 'bb2ba6c514a2daf96fea59dff94af52170258cf91d51800535fb40a738680b8c4b04de774638400605c5a93a042235058b1ef7b0ac1e11b67', 'web', 1496074714),
(252, 1, '5aacf0fe9b402cb4998eef4d002cc2ae19e5adda2bb88de3ff98f3c0067772f57a7075a7203396267cf9a063bb9be814a77a491171cca236d', 'web', 1496074714),
(253, 1, '73480df3e2abbec70441e3175d745cf61a413f6beaa6e91be8467d8cc01c0c7c9e7a4e26600857204926ffc0ca56636b9e73c565cf994ea5a', 'web', 1496074714),
(255, 1, '2b91f3207db61a6444e6276eeff422a2f160ea3c46aa979aa32025e9094f46866bf01e1c312717013c9504ea381ad9f92f634810069532ee5', 'web', 1496074714),
(258, 1, 'de32617fd589f592e4db002e6adfe3db260b679d634b2de705faaee6bc91424392c41ec6612277560a6b8deb7798e7532ade2a8934477d3ce', 'web', 1496074714),
(259, 1, '8a00cbb89960737c4ea774bfb7f8577103ff166e67282ded53e33354f9e0dc8482becf5c234429253081be9fdff07f3bc808f935906ef70c0', 'web', 1496074714),
(260, 1, '8ada9e82c8349a7f35e41ea7253e4fdab6c2578e3c1cdc6b0edf3576f70533ff7d8186b5231391058e10534dd65cf727692c0f9c44ba613f8', 'web', 1496074714),
(261, 1, '15fee35d31f0a58c0d4097f85ddd00612ebe7f7042b55cdf7820accf0010d08a3669f73a554796006ae95296e27d7f695f891cd26b4f37078', 'web', 1496074714),
(263, 1, '15c80fca78da4517bad21f066932ce3c3dde9658159493be29fbf61442c920fd9a991f479825575087f83c19d8adc72f08f8fde30a57eef79', 'web', 1496074714),
(264, 1, '218bf8a6ed4fe7aa820f348d37aadda576be163ca3ca2e23f275ee415ab5e24d676bedcb2406412758909a6e385b0fbc1f3885c00ae838de7', 'web', 1496074714),
(265, 1, '038d2298d21149befe0d3ce21dc7033468c08be87c502876e57bd709214854fd3fd77ddb138400607161fd33f67dbfd29138ce3f165d5e5dd', 'web', 1496074714),
(266, 1, '2f80f9419e8253c72ac5037681bf18a07ee7601078cac9c00a8b6a080ada81ee5aa7e9312685818148b2a9c176d358811a479f771a5874c1b', 'web', 1496074714),
(267, 1, 'e8253f4eeea102e2ae7224bd9978a8b99b4905d35ead0bdfd8edc76018023da2a0e0403c303331163c5383525e91474a4e5d7dcfee92c054f', 'web', 1496074714),
(268, 1, 'a15c4f1b9eaf632b111e7aea31dfd6a06af422a46898681720a19216c6645508ab4b3d0933276367190918ccd19a67747cadd8dbf8368c742', 'web', 1496074714),
(269, 1, '975bcaab7ebd89aa1fc7bd4a5c36ce4d41394c51204fe5312bf5d498df066a387f5c2323240451388bf4334a2421c544eaa17629e52029ca1', 'web', 1496074714),
(270, 1, '9fa0424ac510ace79a14ebdcbe5b002f68c59de6bd9c61805cb99fab4295a737b9ae19d86771375866fe6a8a6e6cb710584efc4af0c34ce50', 'web', 1496074714),
(271, 1, '68bd9f516f6bfceccbf425a034008529f0f377523c0885388fdd1c38b30c9f8f2c753f4a80696614527debb435021eb68b3965290b5e24c49', 'web', 1496074714),
(272, 1, '19e52ef333f149f67e59b953fda128efddc4eb9d0a0d2bc45150fad3c39391e9728a0c9655666775169b4fa3be19bdf400df34e41b93636a4', 'web', 1496074714),
(274, 6, 'f0639ed134b3d22b71f2d4af22e8d88323af05210b5b21835ed1d2e6adbe9483b26e0ff9544162326e0a0a422a9069a4cb2b91211a451da4b', 'web', 1496700577),
(282, 8, '14e4538a5cf8959992154108c66fed5a9c04c5c09618117ff71c2d60301fb4f0d53941c0483072916b97f138920c54acf5eb77d23bc318b12', 'web', 1500734872),
(296, 4, '994a0f55d8f09484a883ca300ad158f95090938237c3e69b1736c322467183b6c0d626103177354599bcb182ab31f229322cff3c8888b2cec', 'web', 1496700393),
(303, 4, '07996276af708177ba7b27a405caf46fa73c0e0e1c3ae65fefd21e9047d7299814c45ec5450737847fbd85d9451c0d7555518534bcbac00e3', 'web', 1496700393),
(304, 4, 'fd7191112596fe47daa648fd1800c16758cacbdff57f9f508f86f15ad5447d8680928312244574652e3844e186e6eb8736e9f53c0c5889527', 'web', 1496700393),
(305, 4, '10d679c1b85d048c5b52379cf7cf312a4dd4482cae2a1c4227eb00a103937ef90bca9d55190185546d26e5e36c1b0b620407eadabb6c0c5c2', 'web', 1496700393),
(308, 4, '7e37539c4a8e73d8332b9fee65e272ff948fa096fb9913940989c20bd882c2e3ee94d905621934678b691334ccf10d4ab144d672f7783c8a3', 'web', 1496700393),
(309, 4, '6823e4a61d5e816d0f8c86344ae0036df1a78306e541101e6d3a1fe805b553c575d5e3f25400119354cea2358d3cc5f8cd32397ca9bc51b94', 'web', 1496700393),
(310, 4, 'cb5c8c4477769d5a8bbda3aa965f41f16768834ad5fdc52adcf2c683167a64b0d333cb9a42550998289d9c467c2926de8ef12d1f3e006d06b', 'web', 1496700393),
(311, 4, 'bc9672f9a9b881955c1ce575aa2bdcb7bad0e15fef45dbdb0573877f0f3f76330794791158417426286ba98bcbd3466d253841907ba1fc725', 'web', 1496700393),
(312, 4, '3858e7aa523d970d27b15a66a6d557c97729eb4f5907687dabe60669ceaf431b800472ae8132324218e5d5b79456a8e2bc09e54e9e518a5f1', 'web', 1496700393),
(313, 4, '7f510d344e548ca9705ac748ec5f82acf305e158fa496f2f984eab2cde07649b263b5d2932888454824bfde45b5790f04b1d096565157f6a4', 'web', 1496700393),
(314, 4, 'cde318e4ad07c39253c2f7ab3a4aa24509aa3d3e6c98cbb3b8cf14285b05e6fe1d3f93e3838785807c67ba7c4c5c0cd4cc3e3a7146fe5c015', 'web', 1496700393),
(315, 4, 'f8afdec64a18ed948381b5a78ace944dff3c7af63df59390ad275b832a5c5d33ac98b16656168619757342f6b95854ad89e9c4088ab94adcf', 'web', 1496700393),
(316, 4, '955b705468b1a341b5df66340a7f3f7a0315eaf9030733a0dd7fa33a7843e07b9c5ae08524704318519e21d13715b9720d8c00977145f1dd8', 'web', 1496700393),
(317, 4, '6fc60685325b66302b8c170f078c0c6db6cddd0c7279e251aaeec07a223e07b46379b3e64754503038722c8f495dcee23f39d5519735e1f71', 'web', 1496700393),
(318, 4, 'ede9a9cb5ba98dc37e0ea8d57b346587f0b24c0d30c750df345a5260bfacd45984f2c8362096625434b0091f82f50ff7095647fe893580d60', 'web', 1496700393),
(320, 5, '26cf54fd3184e6bfd2770511ce3d8dd656724705790a5d95bb3548f140e0b773ee77a853688476562ba347fcc9a79fb74e95670b24848164f', 'web', 1496700485),
(323, 4, '0dc90dddbd0e7ff7ceb7c058a326a9adb815e50fe392bebbb17ae23e24d2a228bdc786e6651611328b937384a573b94c4d7cc6004c496f919', 'web', 1496700393),
(324, 4, '59be21a2176deb523ca5b0957aea93689c04da43ce63b214e883ede6df33492eeaea52868758138027bd66825e9a97424ffe5645549270832', 'web', 1496700393),
(325, 4, '9640f29fc0271a10e188801c1f7a744de769217eb93a151e5ef00cbf4701b004a4022a6c4970974396822951732be44edf818dc5a97d32ca6', 'web', 1496700393),
(326, 4, '37633656d1de26f6b0f83cb8999233b0ce497495122451518a20af5856a82d12f459c267714382595735ddec196a9ca5745c05bec0eaa4bf9', 'web', 1496700393),
(327, 4, 'bdc55278e4019d1a26c1492e782fc225063307779fd2e6ea2168be268e9baf4ad04ecdcf11119249199a2103fcf4f2c44d1f9f75553274025', 'web', 1496700393),
(328, 4, '4f6248bd12131dd7e435ffedc352b043bee4e6f575f9d5e8fb1f8a0fb6f5cbaa6fbf02be18023003462d081df1f0040acd58bcfd3c3040fc1', 'web', 1496700393),
(329, 4, '7bf4d6ff9d554a195323718f93ea74831054200b9a01a90a6d1a97a5e7a89cf89fed98016921386719ce3d416394a5c9a29756027ff8cb37a', 'web', 1496700393),
(332, 4, '21275478157c7b4fc79e8a6f87cceb75072610e26bff78a69e7e48f532ff3236b06c88748643663192118b9f689c4e8d78d34218688a7a1cf', 'web', 1496700393),
(333, 4, 'eecb296642257d958c87bed63acd55c086ecf62a1c5464f2ffa3a5a83a24a80844fd5acd5834147131438ecb8cb1f6fadfee2190700789d7b', 'web', 1496700393),
(334, 4, '587a8ca9967b39c2124496b34658ce0dbd05012d7ae4cb49d6acfa181269afc46a2cebd573524305564de166633d61c8326232568b42beef1', 'web', 1496700393),
(336, 4, '3dceadb701dcb98f1322778c1e0fb3c16aace4ea1fbb1039b5c78e21575a3eaa12cf3356239067925fe60c129a74980578dc8fb0f8fe39b39', 'web', 1496700393),
(337, 4, '70272bd830fa781a7b6872dae863f4b6d9eaf6a712198b479033fda6ea3b2d6d5e723b49820068359bb4abc56ac2093f48c7c26980ec4a4c0', 'web', 1496700393),
(338, 4, '9b6bf51267c0f663d61313101fe7f4dbaafff27b0f29fff3bdf4a700af2054ff5634d0e41955295135a378f8490c8d6af8647a753812f6e31', 'web', 1496700393),
(339, 4, '28415663771fcb8b64003181fc99dd9f83b494624647dcd6796d4215248a50209cd94832164984808022e0ee5162c13d9a7bb3bd00fb032ce', 'web', 1496700393),
(340, 4, '12224bf8d5b6eb21d76e7a698f0a53caf05c7627afc3853a6cec9846a47e8abdc9c6bb8c368570963f880d0d6a01ba52fcfe6475defc13e0f', 'web', 1496700393),
(341, 4, '839d3823de072f15a7e454ce551c0e78e177ea8b8adb375bcbbc267923c9e49a3b5055da490044487198dd5fb9c43b2d29a548f8c77e85cf9', 'web', 1496700393),
(344, 4, '1d4ef9207aa2037cf2373817e97300f1f52c2d451c0e4270d51fede1e54e587d286e3dea8315972227d38b1e9bd793d3f45e0e212a729a93c', 'web', 1496700393),
(345, 4, 'e2f88138a240e61a7986a91bee4ad2a2ef1f032df7d7f54f5e25950f024086fdabaf49dd66658528603db60c2331018b18c4166c1787072fe', 'web', 1496700393),
(346, 4, '1229211e9a2063b990568f3610186f5e116fd1af66e8bd68b7f3f67cc7cd95bb0cca4078705539279995693c15f439e3d189b06e89d145dd5', 'web', 1496700393),
(347, 4, 'e3a7f68f3a33ccfb80c8439ddfcf06ba3dc618f3228e1fe83e64589ac2a9382161a6fde470087348940392f5f32a7ade1cc201767cf83e31', 'web', 1496700393),
(348, 4, 'ab26be3a845be311263bee05f17c992674fc61755fb40be9079845c90bb897c8afb051f49920518663bd7ef30b1a12dc749b97afc9517a4f4', 'web', 1496700393),
(349, 4, '6e4ba838d8ea51323d79c8f4f178f6480319ec8702313db79754578e49ef9956abad1dde56787109363a99723ebb3af94d52b474c3b21dbe1', 'web', 1496700393),
(350, 4, '3fc67006be0e7f53566ee4703818f7c90153d99d37b569d318143467645ad63f328e63e7642930772f75dddd1e79826a219cb0bec217dc096', 'web', 1496700393),
(351, 4, '7ea3c62a698dc54eebf0491c0480b3f4e4591c1bf8ebc0075d8b61e0b4d7e7894866267c4975585937c2c48a32443ad8f805e48520f3b26a4', 'web', 1496700393),
(352, 4, 'f96ef12bb2a8789cdd0f4b0ebbe24a50c5e36a04640cda3c99ba5fd0266e6047a4aa27ad791368272a67c8c9a961b4182688768dd9ba015fe', 'web', 1496700393),
(353, 4, '78ad02fdefedf6c0d637e7d26fd08191238364b0b2184f432fe23677da8bab4669c25cd549685329805ec04f7fb3bc3281f1db83bfc1a2490', 'web', 1496700393),
(354, 4, '6d652dbf0cc9d7495aa05d834d531a2e16e259f1cd78aa0953bc98a70be5441ca59fceff60348849810fb6cfa4c990d2bad5ddef4f70e8ba2', 'web', 1496700393),
(356, 5, 'f5895a962980d12cda1238831f35976e68e395af3a35d204184ce57c28f7d69f029e15c3947808159416e5cf0acb7e553a880b7647903da6e', 'web', 1496700485),
(360, 5, 'e3d1808bd0c4b634988ce45e5ce1fd63cd0b17db3774f8d553b92b3c9fa27b9af46bd767200575086f19c44d068fecac1d6d13a80df4f8e96', 'web', 1496700485),
(361, 5, '953e814d7777a1a5ad38104512930697906cf3866c9c4c4b975c49492d993e26a5514ec5905273437a18aa23ee676d7f5ffb34cf16df3e08c', 'web', 1496700485),
(364, 5, 'b2a67dce46c86ac932434d75f16f671a976a1490da76a8c9a9e9b4a7f80138cc395bfb0595494249135adf1ae7eb5734122c84b7a9ea5cc13', 'web', 1496700485),
(365, 5, '85fa044bff39d56af41a924f4daeb55d773a5f5777dbc32dc6bbe83d739756c1acb109dc6330837676b54185ccddbef6cc10b6586179db501', 'web', 1496700485),
(366, 5, 'f338028d4dfc27ed50744b71bfcafd177d7e549df56a1f410cef1b7425eb533ce6df9d3b859483506e2c4c0b38669387a2a842e1fe391d233', 'web', 1496700485),
(370, 5, '6a00d8e5dec49feec636e87428233bf7a817a3890ffdec42ba19ba5fbf67a064d53340367887641057ce30eeb956b8bbdecfdb304b556edba', 'web', 1496700485),
(380, 5, '3fa9b5a7b96307404c6d2275681902cab21696005083abda246b95b7c920fe41c825dc7195600043430410be149e6771f60881182342452d5', 'web', 1496700485),
(381, 5, 'd8b7f6e2e9b920b99ab5a1c8a4b2784872e5682952c594e23a0de6e6d756d254e3f2efd5367919921b0bb808a5994aefa4f47d7ec6e58c984', 'web', 1496700485),
(386, 4, '01a386e53ddb513534bde87820842e78778d20b1b4adbb4aa53f49f19c6657a7c07d284133995225675c58d36157505a600e0695ed0b3a22d', 'web', 1496700393),
(387, 4, '70de243edec5dc2bb50d6361acf48afac0c846c6e0442deda3e88e149cf82bd847e9f3a2616834852c336346c777707e09cab2a3c79174d90', 'web', 1496700393),
(388, 4, '1f52c33905dfef51c44d9340b9799c6f567152f3740e5f1b1494090f52f7b7fe857e6a0e241617838979a3f14bae523dc5101c52120c535e9', 'web', 1496700393),
(389, 4, '69a337d9509781f29f93237a1cdfe02042051515978a9487090d6813c17056d423cca7f4398111979fedc604da8b0f9af74b6cfc0fab2163c', 'web', 1496700393),
(390, 4, '37603271d6a8f3a50f30645c2864e3144bdc041ba4b51e1f42c225993b248b63ae60989995198567793d9033636450402d67cd55e60b3f926', 'web', 1496700393),
(391, 4, '23e469e79aac7b8563c6897e3e95c45c695f391c5680a391c95351fc3bdde99d9c321e2b539035373adb0e2c465ae2a4b7698a8901fdbb177', 'web', 1496700393),
(392, 4, 'fb1a56addb10904153cf871e4ef8f1bbe09dfeeeada418bd4434064e8559b2ebc44167d9722981770eb7ef0469ad23a2c5782e8770da04529', 'web', 1496700393),
(393, 4, '4f2958f005a6d5a55fa9746dbc6a6f7e5392629d41ccfb92c7e64709e30885a1756309e972897677f9afa97535cf7c8789a1c50a2cd83787', 'web', 1496700393),
(394, 4, '8e165935b4d6b39f7cdd22df0045b255883db170aff3b7bd31e8470acb01d861e71d5d5950073242133beffd09a1b020d1187c6b4b264014a', 'web', 1496700393),
(395, 4, '62b1c5ef56b8a021f83f7035ab3212428ca5b970590725d1b97fbdc45d4c4ae340b145a0247694227bb4abc56ac2093f48c7c26980ec4a4c0', 'web', 1496700393),
(396, 4, 'd646661fa68c088d780e9dc664fba92f75c223f29120bc8a782338fb913a2c82cf54c4c3577175564cebd648f9146a6345d604ab093b02c73', 'web', 1496700393),
(400, 5, '5bfb1c774bb43f37088bb4efbdbcaa040d7ce8b5b404a874f6bff8998259748aa45b670c694878472ddc96fb7d590861ce4bbc4579f5fa848', 'web', 1496700485),
(401, 5, 'de5bec022ffb35bf7a7eb0eba3c2e0436571429d1a705c94cf9753ea50dc9565f8aa853a374267578a543c921889f9dcddaff0ce4ca955293', 'web', 1496700485),
(402, 4, '0ae449745ab0431bf2b2d8e9b2a5cf02e6f68db928447a5e75dc24e8ef6e0e96528bc893420925564164bf317ea19ccfd9e97853edc2389f4', 'web', 1496700393),
(419, 5, '001b360c290106807714abf532331b58e4c215ddba014413907ffe540126c1a9a258b530906005859607bc9ebe4abfcd65181bfbef6252830', 'web', 1496700485),
(435, 4, '4f1cf4334fb41557d49fef47e42e9ca11b40f4c162c9234a95fd5ce1f52b4a9755ea1e34570855034161c5c5ad51fcc884157890511b3c8b0', 'web', 1496700393),
(442, 5, '48dbc37991a99b78d01fc24a99e3d3749afab5aad7ec8961552cafc37d1a07cd6105d7d62954644098a1276c25f5efe85f0fc4020fbf5b4f8', 'web', 1496700485),
(453, 4, 'e96315857fe8a4de609b7c71d18a48ced337324f73d8344dcaa4462cfee837f658c54a42704589843f93486bfff38ca69d76d85c089569a09', 'web', 1496700393),
(454, 4, '608553e49d3cc333aa9208fbfa89349fc54d9f4189cd504462ec3f8ca1916a7df7e5cfa8243055555f8e918489f1e0a81ff11312f4d0630c1', 'web', 1496700393),
(456, 5, '3d9831736dd0b5a70a99e353b43843fc66d30b9731633e5f00f57a909347211c1c1db08c241943359a46b6b588543109f13ff64b2bf1104f0', 'web', 1496700485),
(469, 7, 'b8b64d091b521d6fcb58017a6e3b811e593f4eb4f568c8d18d6d06006b0a559c18ead1df7166883680394ea68951e3299bcdfa75a097d7c11', 'web', 1501061587),
(470, 7, '573e867fc7a9a5cb06ac7792fdb90801fd8b9d64b93f5aaf9e143f132b668a0cfff1e4c359450954814b7367a28377d4d513a4d3349861d2f', 'web', 1501061587),
(471, 7, '6c5b2adf786398fde2ee76908724d2d6e903e0c1ba274927dd7ad172115cdfb31a641ce56911892368346db44a721fa863ca38180638bad3d', 'web', 1501061587),
(475, 9, 'c7cea7d3530733601a7e23ef36dd6313f52ac458badf91470f2329755db90c8dcfb5721a768012152ba638ebf561da3b2313e5d7955c55ea9', 'web', 1500504748),
(480, 9, 'c7f6ab9fd3ce1ea71e8e309bf3f7045e0ee8465682176a4e07673fea05a474b4d62e663f755154079b04c387c8384ca083a71b8da516f65f6', 'web', 1500504748),
(489, 7, 'ee69233b7a101e2c41f07c370c735fc0baca526ef02adcd5076bdf3cdd6c06ab66e9128f297363281f29a179746902e331572c483c45e5086', 'web', 1501061587),
(493, 8, '0b0f019b62fc794e4b816d75fbaae4f5b3db2f08b3f9fbfa62def71b989d104c4af71238925103081647a8664e3a3d945c87db2d07a6590c1', 'web', 1500734872),
(500, 7, 'cae61cc3894443ed332529ea5f6f660561e9809fb82338e9c7bbea249eb0a22120e8fb946682400172109737282d2c2de4fc5534be26c9bb6', 'web', 1501061587),
(507, 7, '8bc01a4b49a51539e9698a2b0717031d6a1b1f42057cffe1f8534f460a08ab910a892fc45635036894e093aa7417fe0881bc5fbda7322a74e', 'web', 1501061587),
(508, 7, 'ad359c241a89be59fa70fb820a2394caeae4d8512c96fc73c8c4efaac7ad93b3596cb659707926432420824960f755f8721c47b6027ead6ab', 'web', 1501061587),
(536, 7, '4a07b1cdfc3e1cb32c739d060cddc01f29fc442138e444925501a5480fcc1d820c873a121946343315cce25ff8c3ce169488fe6c6f1ad3c97', 'web', 1501061587),
(537, 7, 'db28889c606f83ec2c612ab4b9fd89f0a3540482318bf80316bdf448bfb58743b38b19ae5442437068ca070cc474c02335277c16ce15a469b', 'web', 1501061587),
(539, 7, '47813460e11332c8d58d52c9aca639ed74ca9171251042a3eabdd47e589872636b6b78f89982638888a9c8ac001d3ef9e4ce39b1177295e03', 'web', 1501061587),
(547, 8, 'f0f7393ee71b8f92977d38e11f77e51b06bcdb0836f0d6444fa23cd402230cf1c6e58ef7140923393801a089759389ea9fa5f77ecc339f4be', 'web', 1500734872),
(548, 7, '802260a5bc2b6a35907642e14ebbc2dde79cddabc2c85da80d81bcc9ae0619911d4df33c1746419269bcb182ab31f229322cff3c8888b2cec', 'web', 1501061587),
(549, 7, 'dfee7f229c397d0637d530164aaa8873f85818b445b66c4430317fedef70bb83f25153c2676784939024677efb8e4aee2eaeef17b54695bbe', 'web', 1501061587),
(552, 7, '31433a3d61a3ff84edcb04c8f15d48617a22341e058bec4b750ea2d8c280a8344234db51145968966bd33f02c4e28615b5af2d24703e066d5', 'web', 1501061587),
(559, 7, '9cee960d77e5724319ff78738106da7ae1cd5e61e514a66b412b7ca08ec229305730bd397871907557eca143caaf49d9e3dc5c04961a314a0', 'web', 1501061587),
(560, 7, '104998b5682a43eaf4d7140d901d403ae48f466a63fa94e65c0b2e2aaa966f4563b1465e278700086752356ce55e0b436a9027914cb7e18a7', 'web', 1501061587),
(571, 7, 'cb97ae770322d0ed524608d8aca70dabbab6d2c86ec47427700cb2a63e631b679f65e5b37544487841ce83e5d4135b07c0b82afffbe2b3436', 'web', 1501061587),
(572, 7, '573b334c427a2f230ce0e55bb25a74a174acc41bfde39cafdc5d50511bedc3b6b875cb5e26586914085554f207d7a7d8f7817ae532f0dd828', 'web', 1501061587),
(574, 7, '9340256c37174e7f581aabacbd1afdaab785252a592df538c8df8ef068d697e4202fad9b248182508817c99c4861918e518dca75d712983eb', 'web', 1501061587),
(575, 7, '7c124c4d4cc5e150eb21729f3414ca7ce9eaaee4c1e956967c038d943a558342f09835548228624135905aa3361a00b7d9356fa6cf222396d', 'web', 1501061587),
(576, 7, '9fdb2d7ea01d99c2c0e547e4081f1566230dd9c48988cedb793edb1d1f5c8d4714a042c3375515407d8ea5f53c1b1eb087ac2e356253395d8', 'web', 1501061587),
(577, 7, '36b659fbab215841121949625dde75bf613cf69e36eca939c5d4ebce34c17b8baa200c3f579210069575425a3f433138553be468c9d1ecba7', 'web', 1501061587),
(582, 7, '5b5a42cb906355258cce4c6879f69532461ce6e000464d9a7c68b51063d161316caa236363772243905e2a0647e260c355dd2b2175edb45b8', 'web', 1501061587),
(583, 7, '81390c16eccf8ee78ac782d878b29574c706a1c23afcc596c1e1f2fd43b9ae51ab857c846274956593f2dff7862a70f97a59a1fa02c3ec110', 'web', 1501061587),
(592, 7, 'c67acae477fc5458c99aad7c301a0297a683c6c0b7a8f8c20abe50caea787b509c679d4e4286295571819020b02e926785cf3be594d957696', 'web', 1501061587),
(595, 7, '492a1704ed16d657f47cf485a381a5475f31ac11b74ef4eba4d625260771793b5d7d8efa3508572046a30e32e56fce5cf381895dfe6ca7b6f', 'web', 1501061587),
(596, 7, '1692061e5228101ecde1dd704f7f05443d87285e9ec514dc16c3449c855a8e8549b975ef393717447d79c8788088c2193f0244d8f1f36d2db', 'web', 1501061587),
(597, 7, '801888d07a44c10b3822893970f5550584ce77da9cacc670bfb5b7509bc9897b29c04d04669352213481fbfa59da2581098e841b7afc122f1', 'web', 1501061587),
(617, 7, '523ab393448916b8ebd4ecd061bbdfaeccd7d92301662952a5e1f41850da66e7845631e85090603295f0453f78909173a7ce2eb874d2a7f52', 'web', 1501061587),
(620, 7, 'b8f8df6fc68f9d4bc5abd4c73f024a4ba5b8007713759d2a556c1d90e76e4ea54d34f368200358072b7ae8fecf15b8b6c3c69eceae636d203', 'web', 1501061587),
(621, 7, '374229670f0531e0e736bad4d1b938bb6e1ab0895ebb0dba00f0ed68129c121ea78aa9819958767360baf163c24ed14b515aaf57a9de5501c', 'web', 1501061587),
(647, 15, 'd15db756e640824170b82cb2d1010846b0065b7b0b6a8fcaa2cf442531e7da86f055b7c43975965713ffebb08d23c609875d7177ee769a3e9', 'web', 1499854928),
(650, 15, '0719e7b657a00b0da7b67aa2011f205046047ff4ec5802f708add011a87e39225693a93e6862521707f9bcc33944cd0718a62665e934f7653', 'web', 1499854928),
(655, 7, '7447b02fc94bf53063abe5ea8d9c52fbbf96e73f1f8d083fdd370da7995253f193ca652d151557074735a8b95123648555736192cd3978bc1', 'web', 1501061587),
(657, 7, '27d9d1049c80c0a7ccd811ce944f6e259c78f189711d502db855e7766aa940694b513f9a817491319000c076c390a4c357313fca29e390ece', 'web', 1501061587),
(661, 7, '8b34cc308c36dbdbbd96adb770797d025af0aa2c1ee1bc863b5eb5518c388df47d3e1f03945583767a33f5792b2a9a51ddd0111b3ac6e0e76', 'web', 1501061587),
(669, 7, '369476f611f596600e25721828edc969df6e442388f9a4f65aa7808806281058f8002cb47056477867470dce917e7c4b279db989a4b9208d1', 'web', 1501061587),
(670, 7, '823f91cb6b8b954c616ffee6508d41cba06d95f92b27487eab62c459de909fd7f671bd1885457356774de5f915765ea59816e770a8e686f38', 'web', 1501061587),
(682, 7, 'be1d8b53afef3500142c3e87156b600adf2135f3fb69c273cbc3381ef37660370ad86e0b3388671871e79596878b2320cac26dd792a6c51c9', 'web', 1501061587),
(683, 7, 'a384bc1e2be353d2cccdb24605a27eb37fa1e471abc432a85826021dde3a58f05c368424947835286a7c9585703d275249f30a088cebba0ad', 'web', 1501061587),
(684, 7, '05133c32449c3657376708700054dabd6a893bdbf14012db0db3e4347ba46dc3f298b66f6069335935c5a93a042235058b1ef7b0ac1e11b67', 'web', 1501061587),
(685, 7, '2ac94b7f54c7a93f48ee40a4fe930c8a79429be019baebbb7c2e3adc14907dbeffeb6daf31325954881e793dc8317a3dbc3534ed3f242c418', 'web', 1501061587),
(686, 9, 'b862f352fae59f81684b025e4ae491feb1093631646b1af318b16072288a632877ebbbdc32299804623edd566480c510a2d22eb3f2fb04a62', 'web', 1500504748),
(687, 7, '57c2c4edc844ec02a7ba9aa98256fd5f832287ceaf31cb33814f3d855e05f4b6956b772b5219184027bb7a62681a8a0f94ab424b06d172ca3', 'web', 1501061587),
(689, 7, 'a0e4d472c20e807ad0ad98f803599e109afec9d8e500c378986b56dfd8f0b59530091294840684678853573b5a907ed8b2f4fec25a92406a2', 'web', 1501061587),
(708, 7, 'd07021d08419fec3c83a8a40fa159b30d34198e868faba108a345245050d9bed53f90129727349175dfd786998e082758be12670d856df755', 'web', 1501061587),
(709, 7, '081c1702d9d8858c7b16760bc388e3c1cfd7085f329daa423cac7dc1f72584ca4f69fc6f881266276edf0320adc8658b25ca26be5351b6c4a', 'web', 1501061587),
(710, 7, '90d5997d1974c85ca2faf0f17aa3f8720d9fd516a8626f7b8fb4ca73c813f2d0e114e2e4870958116a422e60213322845b85ae122de53269f', 'web', 1501061587),
(711, 7, '96d2041c7e9b27bcdb2a4abdec6fffd8987710af8be1111fc4c93852599234d7e3dc3c2d6975911457274a60c83145b1082be9caa91926ecf', 'web', 1501061587),
(718, 7, '551a8eee5fc053d6f1c18951b4e419509ab350c4701f7096326e42bdfb867a23100946653544650607c2c48a32443ad8f805e48520f3b26a4', 'web', 1501061587),
(735, 7, 'e607becffa053b7be17644cb28bc1da081d2fc7f5f23d72e26f9888932a93eef38d2b4e5876247829ec24a54d62ce57ba93a531b460fa8d18', 'web', 1501061587),
(736, 7, '5258f7dc2a2955970f89b90f36ad30366ad5b37631b269b209daa2995ffcce5b0c9c5ab2536621093def130d0b67eb38b7a8f4e7121ed432c', 'web', 1501061587),
(737, 7, '06a425eb05d148caf21729429a98698edcf9782718e82f4c93878577fede440621288188216796874a8acc28734d4fe90ea24353d901ae678', 'web', 1501061587),
(738, 7, '2dd64caf0714c936811e20f2d8182812db15882e0dbb94cf8ae75086793818e5dca583218284505209ed27554c893b5bad850a422c3538c15', 'web', 1501061587),
(739, 7, '8f9ad94e6379957a383a50c1066aebbd1e86d89eebf45f3939e90abe426202d221e6f5c0285237630659b7b42e9c002ce0075077cd55a1623', 'web', 1501061587),
(747, 7, '708e2e94eb71ce8a8f813aa49be9678993d671a974a44252c088cf505e472e7d4092b4ae447970920621d187a8e1a345cc07422a61c669654', 'web', 1501061587),
(748, 7, 'acfa68adb36c514855db3c1fc85608d3180411a697016bd2a941551cdf712704bdd7113a325032551d98c1545b7619bd99b817cb3169cdfde', 'web', 1501061587),
(749, 7, 'f4d60ba5eecf83b91190fd5da583ff0651e684eb0355d26674c744f5760ee937c19947d5156765407d83de59e10227072a9c034ce10029c39', 'web', 1501061587),
(750, 7, '6cf1c9a38eb2802c35dd42a4bbf0fecf14937ff9158b9b477de31c37ff96c804d0f4e2c5665988498e0f19f64f086e393ceb0cf4a8c561b51', 'web', 1501061587),
(751, 7, '594865ecbd933b626b6aeabca9eca41b9e50ec90ab158dbed7b889eb0157f2ffcab07f2d400472005f5f3b8d720f34ebebceb7765e447268b', 'web', 1501061587),
(752, 7, '65362af51d7153be39a9094f46c9bef9af809e3ca39a668444e0d9c7b4009af2bc6be90f4225802958606f35ec6c77858dfb80a385d0d1151', 'web', 1501061587),
(753, 7, '0296c60a842c5a35be6ad74409efdbe2b27a73799f52da968bf3cf74cab4afde05a927c57776421446fd9a99a5abed788d9afc9d52d54e91b', 'web', 1501061587),
(754, 7, '503dc62c90bcf47e62a9eac5c3084108303c509bd9b7f05580a1b82fe3ca01848e6ca5e7587212456019fa4fdf1c04cf73ba25aa2223769cd', 'web', 1501061587),
(755, 7, '0750df2f63d7759e93fb3e29263ebdd8b48ea2803f02432ba77d017671fe74ca6439211c133680555f169b1a771215329737c91f70b5bf05c', 'web', 1501061587),
(756, 7, '687b541dfde2257c6ec2a8fe53bbe0d56a95dcb1d80da5ba894742c84b8d14518be5667e778971354234dd9e577ac5892481bc60663ffa405', 'web', 1501061587),
(757, 7, '118e1fba468a469be362a6270f1e46a4725da495a101be20c9bbf65dd5435ef66e9f72676548394091d0832c4969f6a4cc8e8a8fffe083efb', 'web', 1501061587),
(758, 7, 'd39ee52a3baa0cd7a4f7204cf5786b32048b5d0a17cf07f79dfe68bb43a42aa5f770b2a1206705729838f14a84363d9a7ac1b06ad63fc6fb5', 'web', 1501061587),
(759, 7, '012357573653dfea2a4fe944b33e6afe211b58c35121880ef96d52442a058f98f836e9ff7583821617ecd070e606afbf07a07c32e7267051f', 'web', 1501061587),
(760, 7, '3586d1993d3a08f3d90ce957d12cfdc2ca7045f72897218363afd51b0336fb79dc80a3f5171983506f5e536083a438cec5b64a4954abc17f1', 'web', 1501061587),
(761, 7, 'a3f88ec0dcb37146dfcc333e01f88bbec2b854b51ea7b5c6f3f24b873f159fc3ffaa2c652991265197ea4e7fcdc6aff2777bd594a3754e02a', 'web', 1501061587),
(762, 7, 'dce6165204a20b72d6bce5b4a33bded78d330c9f49382c7965a3c2007b916c5cb0cf64d78099229605fde40544cff0001484ecae2466ce96e', 'web', 1501061587),
(767, 8, '248ff4569e264fa6a5978f6fb1b152822647d601b9750a093be72c63791bb6cad65891e0158664279e1e1f667ce4596e5644be6fab627c226', 'web', 1500734872),
(769, 7, '13f1fe367cbc16aa58160c748beeeedc14c226232dc110a2880c07e2a7dfea754ce37828686577690ef9280fbc5317f17d480e4d4f61b3751', 'web', 1501061587),
(770, 7, '4f538a5908ebde2e71cea4e631e4d33633680161f464ee6eb99a86083fbc4f889298b6bd1727701829715d04413f296eaf3c30c47cec3daa6', 'web', 1501061587),
(771, 7, '6218f66f2c09c607034a4a004102d6253f4bfcd988d6e4d60833a7ce42459da694d747b7810519748a7bf3f5462cc82062e41b3a2262e1a21', 'web', 1501061587),
(772, 7, '199607690ccaefa8938cd382301744f4470be84adbfa81d8859e758461fe0f40f116751a565131293193510e35bf81956996aa49093954075', 'web', 1501061587),
(773, 7, '612b2f3bd3b91ba6987d95bad451e8fe128feaa987d2454c231abff8dba9e924ee9333ed190131293c92383002f757cddd52df84e68894b5e', 'web', 1501061587),
(775, 8, '9e378147334b1dbf1983d70fbb6d8ae186a9bef050153216bc4d0b929f07773f76ee055112953016423b023b22d0bf47626029d5961328028', 'web', 1500734872),
(776, 7, 'b16c3365df73bdaeab0e7354bd1e685e946ae242f3e0b4cb62670df29e3b0f15f562fbd127067057211348e03e23b137d55d94464250a67a2', 'web', 1501061587),
(781, 11, '6583e95f541eac31dd9d4517395bd51ddc04ec72951a2294d532f14b2d8cd7e2887addb295589192729586cb449c90e249f1f09a0a4ee245a', 'web', 1499852799),
(784, 7, '3bb3aad15230bcb5d9579dd55ee08303da21d52fc0ce9bb0bd636f4464f926fc1973c63684589301285ef8e895264ae2dcab7bcd0f04d9bea', 'web', 1501061587),
(788, 15, '8902db83b9c4204756b4c6bcb647a31f4057533306a315d8557a85373cfcc41ec802f553304850260174b39525e01b7542b3ee27ac7251d2c', 'web', 1499854928),
(792, 7, '779f00b0cb1674f1fa6d412328cf781e41c87c55c3eae3f8484926aceb80bbf996f517bc651150173dae3312c4c6c7000a37ecfb7b0aeb0e4', 'web', 1501061587),
(793, 7, '16053367545002f8978395427ba129ed613922c26dcf90a1c08241abf69e8d8751d226f9177571614517da335fd0ec2f4a25ea139d5494163', 'web', 1501061587),
(794, 7, '45485bd8ed93667800312673e3e7c28bdabf6115c11a7a3f376dbe92937e52eed06df9f172976345493129bca9fb2d3cb3470e2b9cfde7f63', 'web', 1501061587),
(795, 7, 'e46dfa58c817ac716a99db512ab729bab87a92d3da2f16b7a3eb15e5ae54064f93a02c52411159939312ecfdfa8b239e076b114498ce21905', 'web', 1501061587),
(798, 7, '784d6a8a6be107c00e7e01642e9089a4a7a247bd08b948f51de6870b1b522880be56f9ef30734592097de76211cd2efdefa4b8226e6cbf649', 'web', 1501061587),
(799, 7, '34f22f2d9d40b0ece1b5baf112750441e3ff632f0eaaf270b35167fb1014e27a19144b2c9698079421e44fdf9c44d7328fecc02d677ed704d', 'web', 1501061587),
(809, 7, '62c9eff42ad3a89611bb7ad356b6dba4cb4aeefb04218e882baeb0fc6efb1a8a3e894b649624565977240b65810859cbf2a8d9f76a638c0a3', 'web', 1501061587),
(813, 7, '7dcf621e3a83eff611a8ad38a7869ae470fc2e47c7c43fa77c65b3065c0a1bd7e29aad0223328993068c86f3f9a4eefdc3180f488ac749db5', 'web', 1501061587),
(825, 7, '4883abd8b9e9935b77d3df256dda0ca8d724430ccc699ff2ce4c9f6837bc3bcedea598968606228296f5e4e86a87220e5d361ad82f1ebc335', 'web', 1501061587),
(828, 7, 'def3ed27424e026209f51350c5cc469a5886a4f60803450873b95cd79419c518b1e22ade7360839845fd2c06f558321eff612bbbe455f6fbd', 'web', 1501061587),
(829, 7, '89f7fe61fe3c461eb59b118e02f5a01082b39d2ed0a896883f76e41784ffca37549935a1591227213c404a5adbf90e09631678b13b05d9d7a', 'web', 1501061587),
(831, 7, '201707151746242839', 'desktop', 1500115585),
(832, 7, '201707151826034384', 'desktop', 1500117963),
(833, 7, '968c20e0267ce52462fc1cd923f7f847', 'desktop', 1500138639),
(834, 7, 'c104457a8d4ca1ac783417a0e8612cbd', 'desktop', 1500142266),
(835, 7, 'd643362b43de311886b1a2cc48bfcbfc', 'desktop', 1500146209),
(836, 7, '3f7b1e0df5b2d0af03ae7238dc39e804', 'desktop', 1500146610),
(837, 7, '6b00ad3fa9d7ccaa7f38003ac6e2dab0', 'desktop', 1500153734),
(838, 7, 'a2bb51b8a0ba578b600e7ff1a9829f9e', 'desktop', 1500154070),
(839, 7, 'e7db985986889bdc9deb8511cbd23fd8', 'desktop', 1500154317),
(840, 7, '172c06c3f3cd3ccb9b3bb1afb25069dc', 'desktop', 1500155843),
(841, 7, 'd7833083cf83015564ac4a1cfbe78950', 'desktop', 1500156044),
(842, 7, '446252ee80a560aefe5b46d70bd01aee', 'desktop', 1500156595),
(843, 7, '92a2c73473fa32dda80f66e7eda3d209', 'desktop', 1500205463),
(845, 7, '6b9a4fae1ce3fb57bc5d36fa9400453a', 'desktop', 1500214233),
(846, 7, 'a39510124178d7775e2a785f0a6d66c0', 'desktop', 1500228043),
(847, 7, '2ccda09151c8e3b7bc5002337f396b0f', 'desktop', 1500231706),
(848, 7, '18a1fb5ed65ad7ee9fa18f2398af3c1b', 'desktop', 1500231808),
(849, 7, '885cb7c16c468d41f336e664efccd0d9', 'desktop', 1500232468),
(850, 7, 'bedaa9b3479995b2c10033d5f056cdb7', 'desktop', 1500285831),
(851, 7, '31813663239ae4a92e2214c4011485cb', 'desktop', 1500286660),
(852, 7, '06cbb74f267032e24566549f749c866f', 'desktop', 1500295318),
(853, 7, '517aa2a2319a72069abf705266108140', 'desktop', 1500295618),
(854, 7, 'e0bfcd259e31ab8b27594870a046eced', 'desktop', 1500296340),
(855, 7, 'd4d32dd3a4ff142945361900869633cc', 'desktop', 1500372820),
(856, 7, '3c7dcf3bd0df8eeb3f4763fd7142b3c4', 'desktop', 1500399269),
(857, 7, '5814868f9f5502149878e31ee1393b9f', 'desktop', 1500399696),
(858, 7, '9060233266b35dc7df5bb146ae8d9312', 'desktop', 1500403081),
(860, 7, '79c276cbff12ac0bd34c3da9bbd8d91b', 'desktop', 1500405647),
(861, 7, '0201853a7ddff52c66d0a40890e7e6e2', 'desktop', 1500406143),
(862, 7, '5a23eebe2b552a35595cbd77a9a8b4a9', 'desktop', 1500406482),
(863, 7, '7e1cb88c4fed874d59f8a3227b58af29', 'desktop', 1500406587),
(864, 7, 'd6453352704ac19529bbc1fa007e7295', 'desktop', 1500406670),
(865, 7, '38409ebd36c0539fae0c9fd9f8f1119c', 'desktop', 1500406874),
(866, 7, '61c36de880d0e62ede370473b352524d', 'desktop', 1500407059),
(867, 7, 'a649eeb6e20b83430c5e205072843da5', 'desktop', 1500407201),
(868, 7, 'c3a3a607b013cb0d6191c5a9bffb4dfb', 'desktop', 1500407385),
(869, 7, '1a23e4515ba7947afd0b31c9c9481f06', 'desktop', 1500407453),
(870, 7, 'f05352bcab078e85725fabaad2e8bdd4', 'desktop', 1500407502),
(871, 7, 'beaaa3c04e57754916ef352864dca1e2', 'desktop', 1500407566),
(872, 7, '3965bc575000c340429c6df2f281e9cb', 'desktop', 1500407615),
(873, 7, '17bcf0090633a870ff810ef4b665147c', 'desktop', 1500407977),
(874, 7, '22e2d694c8922261b37ca48f2cc99745', 'desktop', 1500408453),
(875, 7, 'a4c1fd1e9e280295a458110f02ae8a79', 'desktop', 1500408568),
(876, 7, 'e1dbc06ad5ca007818ec765a0fc22f4f', 'desktop', 1500408906),
(877, 7, '98dd125420dc5c443c92826618571d05', 'desktop', 1500409170),
(878, 7, '458aa4200192560daa516f89e9abfd66', 'desktop', 1500409448),
(879, 7, 'e97b639f126c7e35183eda310d60ad52', 'desktop', 1500409787),
(880, 7, '36dcb6d8de0724d881998e5ce812a4fd', 'desktop', 1500414648),
(881, 7, '496f381a859c2aab0f930b7bbb53ef52', 'desktop', 1500414941),
(882, 7, '60a4e31c8743822889efc857d4412b14', 'desktop', 1500415705),
(883, 7, '6b6e66f664fcdac6812a73c8b478bd7b', 'desktop', 1500465848),
(884, 7, 'dc864644fc14b7cf8763cd8d817d2986', 'desktop', 1500467711),
(885, 8, '07d9942afb46b6d4636b0f8eac3fee68', 'desktop', 1500493866),
(886, 8, '9cc6f7611124343b8a77b4bd3e3125cc', 'desktop', 1500501672),
(888, 8, '031ae78e5e42782e396a36cf99ab0625', 'desktop', 1500502139),
(889, 9, 'dc9a6c44b1e9a2c62276a004954b474b', 'desktop', 1500502298),
(890, 7, '82cf70e039836fba7beeb9d05c049118', 'desktop', 1500502379),
(891, 7, '1461910035b914a4b12d16c57fb9961e', 'desktop', 1500502831),
(892, 7, '70f943bb821f379b9c0de06cef6eb112', 'desktop', 1500503340),
(893, 9, '51cceec4fd79950085790b026dd5b0e4', 'desktop', 1500503804),
(898, 7, '26d02f33cd41632484ba2cf5c999e841', 'desktop', 1500505177),
(899, 8, '60203e9480cf76ff6b5f1d4fa12a40e7', 'desktop', 1500505246),
(900, 7, 'a2121ec956706f31106f74996d9e4aad', 'desktop', 1500551529),
(901, 7, '4e0b07de04a9692e5577b9881e554ad7', 'desktop', 1500553634),
(902, 7, '332087d830470d296739e551297eebe1', 'desktop', 1500553808),
(903, 7, '68a1326306591bc433f5ac9a0647bdd1', 'desktop', 1500554105),
(904, 8, '312261e9618dce70c25ce33cc2760bff', 'desktop', 1500554168),
(905, 7, '71fb08f5e254d9b87a8f597ba1e4e762', 'desktop', 1500554387),
(906, 7, '74494511063d24db6fbddc0dbade8972', 'desktop', 1500555641),
(907, 7, 'cdffb3d6a262c16492aa3f95d779e97a', 'desktop', 1500556016),
(908, 7, '7ce83420c6f1161ee0c833282e185e1a', 'desktop', 1500556486),
(909, 7, '58ebfd0954980bc39e054aec6391edc2', 'desktop', 1500557890),
(910, 7, '565b2c3a7f8801b0898be947d27fb3b0', 'desktop', 1500558036),
(911, 7, '82ca28ae8ee619de709d3a9a8d785634', 'desktop', 1500558134),
(912, 7, 'c6cd865ad27d42e95bf6eddc802debbf', 'desktop', 1500558234),
(913, 7, 'a0dd09b01957974309e4f271293f807a', 'desktop', 1500558326),
(914, 7, '363c0af195a822ddd61fd075c24a2746', 'desktop', 1500558441),
(915, 7, 'b4ac677e638e8f64fcd6ac9b8b2b1e46', 'desktop', 1500558542),
(917, 7, '518777f4202d8cef3a8638a50419e0d7', 'desktop', 1500562869),
(918, 7, '30ac2e0d60e1c042fa62cd7c708faa7a', 'desktop', 1500563079),
(919, 7, 'f7f4e1fc1587fdf746cf26e20519f393', 'desktop', 1500563362),
(920, 7, 'f98e24afdc2542f8d97dce4189917c19', 'desktop', 1500564171),
(921, 7, 'e5b4dfb95a84ee9587e3595cc6990ea2', 'desktop', 1500565543),
(922, 7, 'eea9633ad6cd839460290e058084c40a', 'desktop', 1500566693),
(923, 7, 'c37f2379e3e9696c183f90051f71d40c', 'desktop', 1500567147),
(924, 7, 'f9ed0fafc37bcad0f6bfd149c418abb7', 'desktop', 1500567598),
(925, 7, '1e587619b2153e9e7cf9b6a515bbcd68', 'desktop', 1500567907),
(926, 7, '70833d9f05e45cf2e1e672162d392dd5', 'desktop', 1500570480),
(927, 7, 'bf5eb110bb04f9e5f2a69b665a009b54', 'desktop', 1500570790),
(928, 8, '7fb6b12b88f13c9f2b2199c604f9d607', 'desktop', 1500571794),
(929, 7, '35f0aa34677c771745360aff237b0074', 'desktop', 1500572751),
(930, 7, '5f31b72ebe8f1faa1114769f1e841109', 'desktop', 1500573021),
(931, 7, '33c572536bf1a097aeb2c1d0fbfa06d3', 'desktop', 1500573156),
(932, 7, 'f5358d5310ef008beb2d2e4ad4a60b12', 'desktop', 1500573373),
(933, 7, '93923d4f087bae3e69d526ed7ff22118', 'desktop', 1500575352),
(935, 8, '9ab781fdf05bbd29d9339d7c4bc6a059', 'desktop', 1500576659),
(936, 8, '7147d0fea4f0e3a2fb69171b7892f058', 'desktop', 1500656390),
(937, 8, '8e76528b639beac8bdd3e5632397f7c3', 'desktop', 1500656579),
(938, 8, '7b8d77604860bda78754e3f87aadaf74', 'desktop', 1500656790),
(939, 8, '95ab1f272c77e3fa16cdf7270e4cc6fc', 'desktop', 1500659195),
(940, 8, '6a8c1e28733df5fcec271018bddcae1f', 'desktop', 1500659379),
(941, 8, 'ea57c6762cba7dde9a10a467a7d4dc78', 'desktop', 1500660213),
(942, 7, '233ee1bb4c884178d0b01995baa95bde', 'desktop', 1500660429),
(946, 7, '0424e99a4dea426475fdb854510252f5', 'desktop', 1500731923),
(947, 8, '42597e5e438811020077ac731cbad33ca8bef212ab60fd80704b83daa03beb75bf5b356b230061848c344336196d5ec19bd54fd14befdde87', 'web', 1500734872),
(949, 7, 'fe754a5800cbd5052d6a3d240ac522e61ea6dada95de707b49704782f7a49744cbc6161a3286132815c6287be4de9ff5afeaec72d54436fcf', 'web', 1501061587),
(950, 7, '5415ef22636ad0e1c18899211f0b4af72e0494de1316b9c1100003d57bda7ba97d844bd0743787977ce1aad92b939420fc17005e5461e6f48', 'web', 1501061587),
(951, 7, 'c7e7809dab01d4299dfdfe8d27dd1db7cf2a34474184bd41080da1cee13318ecdfeaef1c527316623034260c0426cf36118803ce0df4457fd', 'web', 1501061587),
(952, 7, 'bd23b248e9a4e945b3eb983f7908312950e2ba0fbce6441b0190f04a7c4c3d04357a642596880425361bfdc160e4c099203c72258d8825340', 'web', 1501061587),
(953, 7, 'cbcf5f1093155a5065f9f4e6bc21f87f21ca79d3ed6ef635159c2995ff238434125ec10a82451714497737a7937a18cf131d9e21eda811113', 'web', 1501061587),
(954, 7, '7d4220a36b9b0d6677aea56e93947c4eec330e93fe747b2377787312bc1653ec58719bae484239366aa1b6b26d690368d6f74a35a7daa0916', 'web', 1501061587),
(956, 7, 'bd7f69bfbc71a408da772f94ae4d0c03872ad9c38405c6e0de81d598cdb78bbf3d670ce66537272130b94ce08688c6389ce7b68c52ce3f8c7', 'web', 1501061587),
(957, 7, 'ff168f040d42a66ab2a176c09d723b941b1fdaa9b64ff5e396a4ad1069c5f46e4fe2138b2504069001e0feeaff84a19bf3936e693311fa66d', 'web', 1501061587),
(958, 7, 'dd24315e2577a671dd4b02e9ea3d2e4c137972fade93721fec0c177d9ba42cb04c575b7c326361762e1f4fd6d0118b7b0797d7c1a0007b80a', 'web', 1501061587),
(959, 7, '02a2d96b8a4e25ba910018b1d3d98fb6d665daf30c2f34d08bfc86ba312e3bbdbd6c3e528065049918af95fe2ab1a54b488ef8efb3f3b0797', 'web', 1501061587),
(960, 7, 'bb2b0208eaa7e0111327bb771aa99a94b2f2615048e08b693e2009d58a1ddb821654af8817279730818c7c32f90d25fbe5c1b9ac0e8d5e475', 'web', 1501061587);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT 'upload/photos/d-avatar.jpg',
  `cover` varchar(100) NOT NULL DEFAULT 'uploads/photos/d-cover.jpg',
  `address` varchar(100) NOT NULL DEFAULT '',
  `working` varchar(32) NOT NULL DEFAULT '',
  `working_link` varchar(32) NOT NULL DEFAULT '',
  `about` text,
  `gender` varchar(32) NOT NULL DEFAULT 'male',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `country_id` int(11) NOT NULL DEFAULT '0',
  `website` varchar(50) NOT NULL DEFAULT '',
  `facebook` varchar(50) NOT NULL DEFAULT '',
  `google` varchar(50) NOT NULL DEFAULT '',
  `twitter` varchar(50) NOT NULL DEFAULT '',
  `instagram` varchar(32) NOT NULL DEFAULT '',
  `language` varchar(32) NOT NULL DEFAULT 'english',
  `email_code` varchar(32) NOT NULL DEFAULT '',
  `src` varchar(32) NOT NULL DEFAULT 'Undefined',
  `ip_address` varchar(32) NOT NULL DEFAULT '',
  `message_privacy` enum('0','1') NOT NULL DEFAULT '0',
  `follow_privacy` enum('0','1') NOT NULL DEFAULT '0',
  `show_activities_privacy` enum('0','1') NOT NULL DEFAULT '1',
  `visit_privacy` enum('0','1') NOT NULL DEFAULT '0',
  `verified` enum('0','1') NOT NULL DEFAULT '0',
  `lastseen` int(32) NOT NULL DEFAULT '0',
  `show_lastseen` enum('0','1') NOT NULL DEFAULT '1',
  `email_notification` enum('0','1') NOT NULL DEFAULT '1',
  `e_liked` enum('0','1') NOT NULL DEFAULT '1',
  `e_shared` enum('0','1') NOT NULL DEFAULT '1',
  `e_followed` enum('0','1') NOT NULL DEFAULT '1',
  `e_commented` enum('0','1') NOT NULL DEFAULT '1',
  `e_visited` enum('0','1') NOT NULL DEFAULT '1',
  `e_mentioned` enum('0','1') NOT NULL DEFAULT '1',
  `e_accepted` enum('0','1') NOT NULL DEFAULT '1',
  `e_joined_group` enum('0','1') NOT NULL DEFAULT '1',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1','2') NOT NULL DEFAULT '0',
  `admin` enum('0','1','2') NOT NULL DEFAULT '0',
  `type` varchar(12) NOT NULL DEFAULT 'user',
  `registered` varchar(32) NOT NULL DEFAULT '0/0000',
  `getstarted` enum('0','1') NOT NULL DEFAULT '0',
  `getstarted_info` enum('0','1') NOT NULL DEFAULT '0',
  `getstarted_follow` enum('0','1') NOT NULL DEFAULT '0',
  `getstarted_image` enum('0','1') NOT NULL DEFAULT '0',
  `last_email_sent` int(32) NOT NULL DEFAULT '0',
  `phone_number` varchar(32) NOT NULL DEFAULT '',
  `sms_code` int(11) NOT NULL DEFAULT '0',
  `social_login` enum('0','1') NOT NULL DEFAULT '0',
  `joined` int(11) NOT NULL DEFAULT '0',
  `timezone` varchar(50) NOT NULL DEFAULT '',
  `referrer` int(11) NOT NULL DEFAULT '0',
  `notification_sound` enum('0','1') NOT NULL DEFAULT '0',
  `order_posts_by` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `avatar`, `cover`, `address`, `working`, `working_link`, `about`, `gender`, `birthday`, `country_id`, `website`, `facebook`, `google`, `twitter`, `instagram`, `language`, `email_code`, `src`, `ip_address`, `message_privacy`, `follow_privacy`, `show_activities_privacy`, `visit_privacy`, `verified`, `lastseen`, `show_lastseen`, `email_notification`, `e_liked`, `e_shared`, `e_followed`, `e_commented`, `e_visited`, `e_mentioned`, `e_accepted`, `e_joined_group`, `status`, `active`, `admin`, `type`, `registered`, `getstarted`, `getstarted_info`, `getstarted_follow`, `getstarted_image`, `last_email_sent`, `phone_number`, `sms_code`, `social_login`, `joined`, `timezone`, `referrer`, `notification_sound`, `order_posts_by`) VALUES
(7, 'bonabrian', 'bonabriansiagian@gmail.com', '29836109d987e821c76394acbd9789c8', 'Bona Brian', 'Siagian', 'uploads/photos/2017/07/bqUjxpxa2TsstaLhOAxv_14_8c9a162c3e171bdfeed5182bfe8f5157_avatar.jpg', 'uploads/photos/2017/07/D187zFntI3MhbB3JjE6C_11_f6ccfc4390624d907b7c5f45e407d9b1_cover.jpg', 'Jalan Sekeloa Utara, Sekeloa, Bandung City, West Java, Indonesia', '', '', 'Ambition make you look pretty ugly.', 'male', '1995-10-31', 0, 'http://www.carovl.com', '', '', '', '', 'english', 'bca9ecd440a8b6329b29665429944c91', 'site', '::1', '0', '1', '0', '0', '1', 1501061586, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '1', 'user', '6/2017', '1', '1', '0', '1', 0, '', 0, '0', 1496700824, 'UTC', 0, '0', '1'),
(8, 'rahmanaharin', 'rahmanaharin16@gmail.com', '29836109d987e821c76394acbd9789c8', 'Rahma', 'Naharin', 'uploads/photos/2017/06/t3EkPUADCm2qXpfTayHA_06_872c2cc9dba5c502e3709649738e4050_avatar.jpg', 'uploads/photos/d-cover.jpg', '', '', '', '', 'female', '1996-02-16', 0, '', '', '', '', '', 'english', 'f03ad107730b777fac0309c8712e5813', 'site', '::1', '1', '1', '1', '0', '0', 1500734859, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '1', '1', '0', '1', 0, '085795220096', 70404, '0', 1496701314, 'UTC', 0, '0', '1'),
(9, 'esrasinaga', 'esrasinaga25@gmail.com', '29836109d987e821c76394acbd9789c8', 'Esra', 'Sinaga', 'uploads/photos/2017/06/7ycE2esJ1ObefPHW5TcF_06_f1459e450c6dab8c9d952409866b3056_avatar.JPG', 'uploads/photos/d-cover.jpg', '', '', '', '', 'female', '1995-04-25', 0, '', '', '', '', '', 'english', '036ef1976466e8c61b153362a78be75e', 'site', '::1', '0', '0', '1', '0', '0', 1500504753, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '1', '1', '0', '1', 0, '', 0, '0', 1496701461, 'UTC', 0, '0', '1'),
(10, 'siagian', 'siagian.brian@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/s.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', '7a0e208e798468e1456d4bf688a83308', 'site', '::1', '0', '0', '1', '0', '0', 1497527898, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '0', '0', '0', '0', 0, '', 0, '0', 1497449646, '', 0, '0', '1'),
(11, 'ronamn', 'ronamn19@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/r.png', 'uploads/photos/d-cover.jpg', '', '', '', '', 'female', '0000-00-00', 0, '', '', '', '', '', 'english', '71d8f6bc2ad0a68f2ef192a93400ff5f', 'site', '::1', '0', '0', '1', '0', '0', 1499852862, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '0', '0', '0', '0', 0, '', 0, '0', 1497449667, '', 0, '0', '1'),
(12, 'rafsanjani', 'rafsanjanifauzi@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/r.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', 'bb97002839a97e175b13f29e01e4ad65', 'site', '::1', '0', '0', '1', '0', '0', 1499852630, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '0', '0', '0', '0', 0, '', 0, '0', 1497449686, '', 0, '0', '1'),
(15, 'fajarherianto22', 'fajarherianto186@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/f.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', '6b1018bdf116531480c0bd42e00a887a', 'site', '::1', '0', '0', '1', '0', '0', 1499854928, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '0', '0', '0', '0', 0, '', 0, '0', 1497449796, '', 0, '0', '1'),
(16, 'demohei', 'demo@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/d.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', 'c348f79f1b9f3944659ad86f9bf31420', 'site', '::1', '0', '0', '1', '0', '0', 1499864022, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '0', '0', '0', '0', 0, '', 0, '0', 1497458577, '', 0, '0', '1'),
(17, 'demouser', 'demo@admin.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/d.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', '91017d590a69dc49807671a51f10ab7f', 'site', '::1', '0', '0', '1', '0', '0', 1499283159, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '1', '0', 'user', '6/2017', '1', '1', '0', '1', 0, '', 0, '0', 1498078789, '', 0, '0', '1'),
(20, 'samuel', 'samuelsianipar@gmail.com', '29836109d987e821c76394acbd9789c8', '', '', 'uploads/photos/default-avatar/s.png', 'uploads/photos/d-cover.jpg', '', '', '', NULL, 'male', '0000-00-00', 0, '', '', '', '', '', 'english', 'd8ae5776067290c4712fa454006c8ec6', 'site', '::1', '0', '0', '1', '0', '0', 1499585065, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', 'user', '7/2017', '0', '0', '0', '0', 0, '', 0, '0', 1499585065, '', 0, '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_chats`
--

CREATE TABLE `user_chats` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `conversation_user_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_chats`
--

INSERT INTO `user_chats` (`id`, `user_id`, `conversation_user_id`, `time`) VALUES
(3, 8, 15, 1498496159),
(4, 15, 8, 1498496159),
(6, 8, 7, 1500504958),
(8, 10, 7, 1499285484),
(14, 16, 7, 1499283504),
(15, 7, 8, 1500504958);

-- --------------------------------------------------------

--
-- Table structure for table `user_fields`
--

CREATE TABLE `user_fields` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_fields`
--

INSERT INTO `user_fields` (`id`, `user_id`) VALUES
(5, 1),
(7, 1),
(1, 2),
(2, 2),
(6, 2),
(3, 3),
(4, 4),
(8, 4),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `verification_requests`
--

CREATE TABLE `verification_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `seen` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `video_calls`
--

CREATE TABLE `video_calls` (
  `id` int(11) NOT NULL,
  `call_id` varchar(32) NOT NULL DEFAULT '0',
  `access_token` text,
  `call_id_2` varchar(32) NOT NULL DEFAULT '',
  `access_token_2` text,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `called` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `declined` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `video_views`
--

CREATE TABLE `video_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `video_views`
--

INSERT INTO `video_views` (`id`, `user_id`, `post_id`) VALUES
(2, 9, 139),
(7, 7, 143),
(8, 15, 139),
(9, 15, 143),
(10, 7, 139),
(11, 8, 143);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `activity_type` (`activity_type`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `albums_media`
--
ALTER TABLE `albums_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `announcement_views`
--
ALTER TABLE `announcement_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `title` (`article_title`),
  ADD KEY `article_content` (`article_content`(255)),
  ADD KEY `draft` (`draft`);

--
-- Indexes for table `banned_ip`
--
ALTER TABLE `banned_ip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip_address` (`ip_address`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blocker` (`blocker`),
  ADD KEY `blocked` (`blocked`);

--
-- Indexes for table `blog_media`
--
ALTER TABLE `blog_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_post_id` (`blog_post_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_post_id` (`blog_post_id`),
  ADD KEY `authors` (`authors`),
  ADD KEY `image` (`image`),
  ADD KEY `registered` (`registered`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_post_id` (`blog_post_id`),
  ADD KEY `tag` (`tag`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poster_id` (`user_id`),
  ADD KEY `name` (`name`),
  ADD KEY `start_date` (`start_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`);

--
-- Indexes for table `events_action`
--
ALTER TABLE `events_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `following_id` (`following_id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `privacy` (`privacy`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`group_id`);

--
-- Indexes for table `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_trend_time` (`last_trend_time`),
  ADD KEY `trend_use_num` (`trend_use_num`),
  ADD KEY `tag` (`tag`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_key` (`lang_key`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `to_id` (`to_id`),
  ADD KEY `seen` (`seen`),
  ADD KEY `time` (`time`),
  ADD KEY `deleted_one` (`deleted_one`),
  ADD KEY `deleted_two` (`deleted_two`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifier_id` (`notifier_id`),
  ADD KEY `user_id` (`recipient_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `seen` (`seen`),
  ADD KEY `time` (`time`),
  ADD KEY `group_id` (`group_id`,`seen_pop`);

--
-- Indexes for table `policy`
--
ALTER TABLE `policy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `post_file` (`post_file`),
  ADD KEY `post_share` (`post_share`),
  ADD KEY `registered` (`registered`),
  ADD KEY `time` (`time`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category` (`category`),
  ADD KEY `price` (`price`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `products_media`
--
ALTER TABLE `products_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_fields`
--
ALTER TABLE `profile_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_page` (`registration_page`),
  ADD KEY `active` (`active`),
  ADD KEY `profile_page` (`profile_page`);

--
-- Indexes for table `recent_searches`
--
ALTER TABLE `recent_searches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`search_id`),
  ADD KEY `search_type` (`search_type`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `seen` (`seen`);

--
-- Indexes for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `platform` (`platform`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `active` (`active`),
  ADD KEY `admin` (`admin`),
  ADD KEY `src` (`src`),
  ADD KEY `gender` (`gender`),
  ADD KEY `avatar` (`avatar`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `registered` (`registered`),
  ADD KEY `joined` (`joined`),
  ADD KEY `phone_number` (`phone_number`) USING BTREE,
  ADD KEY `referrer` (`referrer`);

--
-- Indexes for table `user_chats`
--
ALTER TABLE `user_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `conversation_user_id` (`conversation_user_id`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `user_fields`
--
ALTER TABLE `user_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `verification_requests`
--
ALTER TABLE `verification_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `video_calls`
--
ALTER TABLE `video_calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_id` (`to_id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `call_id` (`call_id`),
  ADD KEY `called` (`called`),
  ADD KEY `declined` (`declined`);

--
-- Indexes for table `video_views`
--
ALTER TABLE `video_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `albums_media`
--
ALTER TABLE `albums_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `announcement_views`
--
ALTER TABLE `announcement_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `banned_ip`
--
ALTER TABLE `banned_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog_media`
--
ALTER TABLE `blog_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog_tags`
--
ALTER TABLE `blog_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `events_action`
--
ALTER TABLE `events_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=518;
--
-- AUTO_INCREMENT for table `policy`
--
ALTER TABLE `policy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `products_media`
--
ALTER TABLE `products_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `profile_fields`
--
ALTER TABLE `profile_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recent_searches`
--
ALTER TABLE `recent_searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=961;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user_chats`
--
ALTER TABLE `user_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `user_fields`
--
ALTER TABLE `user_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `verification_requests`
--
ALTER TABLE `verification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `video_calls`
--
ALTER TABLE `video_calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `video_views`
--
ALTER TABLE `video_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
