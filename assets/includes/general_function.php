<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------

// Load Page
function loadPage($page_url = '')
{
	global $carovl;
	$page = './views/layout/' . $page_url . '.phtml';
	$page_content = '';
	ob_start();
	require($page);
	$page_content = ob_get_contents();
	ob_end_clean();
	return $page_content;
}
function getDominantColor($image)
{
    $rTotal = 0;
    $gTotal = 0;
    $bTotal = 0;
    $total = 0;
    $src_img = @getimagesize($image);
    $mime = $src_img['mime'];
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            break;
        default:
            return false;
            break;
    }
    $i = @$image_create($image);
    for ($x=0; $x < imagesx($i); $x += 10) { 
        for ($y=0; $y < imagesy($i); $y += 10) { 
            $rgb = imagecolorat($i, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $rTotal += $r;
            $gTotal += $g;
            $bTotal += $b;
            $total++;
        }
    }
    $average = array(
        'r' => round($rTotal/$total),
        'g' => round($gTotal/$total),
        'b' => round($bTotal/$total)
    );
    $hex_color = array();
    $hex_color['background'] = fromRGBToHexa($average['r'], $average['g'], $average['b']);
    $brightness = $average['r'] * 0.299 + $average['g'] * 0.587 + $average['b'] * 0.0114;
    if ($brightness > 160) {
        $hex_color['color'] = '#26C281';
    } else {
        $hex_color['color'] = '#f2f2f2';
    }
    return $hex_color;
}
function fromRGBToHexa($r, $g, $b)
{
    $r = dechex($r);
    if (strlen($r) < 2) {
        $r = '0' . $r;
    }
    $g = dechex($g);
    if (strlen($g) < 2) {
        $g = '0' . $g;
    }
    $b = dechex($b);
    if (strlen($b) < 2) {
        $b = '0' . $b;
    }

    return '#' . $r . $g . $b;
}
function urlSlug($str, $options = array())
{
	// Make sure string in UTF-8
	$str = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false
	);

	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
		// Latin
		'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ő' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ű' => 'U',
        'Ý' => 'Y',
        'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'd',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ő' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ű' => 'u',
        'ý' => 'y',
        'þ' => 'th',
        'ÿ' => 'y',

        // Latin Symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A',
        'Β' => 'B',
        'Γ' => 'G',
        'Δ' => 'D',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'H',
        'Θ' => '8',
        'Ι' => 'I',
        'Κ' => 'K',
        'Λ' => 'L',
        'Μ' => 'M',
        'Ν' => 'N',
        'Ξ' => '3',
        'Ο' => 'O',
        'Π' => 'P',
        'Ρ' => 'R',
        'Σ' => 'S',
        'Τ' => 'T',
        'Υ' => 'Y',
        'Φ' => 'F',
        'Χ' => 'X',
        'Ψ' => 'PS',
        'Ω' => 'W',
        'Ά' => 'A',
        'Έ' => 'E',
        'Ί' => 'I',
        'Ό' => 'O',
        'Ύ' => 'Y',
        'Ή' => 'H',
        'Ώ' => 'W',
        'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a',
        'β' => 'b',
        'γ' => 'g',
        'δ' => 'd',
        'ε' => 'e',
        'ζ' => 'z',
        'η' => 'h',
        'θ' => '8',
        'ι' => 'i',
        'κ' => 'k',
        'λ' => 'l',
        'μ' => 'm',
        'ν' => 'n',
        'ξ' => '3',
        'ο' => 'o',
        'π' => 'p',
        'ρ' => 'r',
        'σ' => 's',
        'τ' => 't',
        'υ' => 'y',
        'φ' => 'f',
        'χ' => 'x',
        'ψ' => 'ps',
        'ω' => 'w',
        'ά' => 'a',
        'έ' => 'e',
        'ί' => 'i',
        'ό' => 'o',
        'ύ' => 'y',
        'ή' => 'h',
        'ώ' => 'w',
        'ς' => 's',
        'ϊ' => 'i',
        'ΰ' => 'y',
        'ϋ' => 'y',
        'ΐ' => 'i',

        // Turkish
        'Ş' => 'S',
        'İ' => 'I',
        'Ç' => 'C',
        'Ü' => 'U',
        'Ö' => 'O',
        'Ğ' => 'G',
        'ş' => 's',
        'ı' => 'i',
        'ç' => 'c',
        'ü' => 'u',
        'ö' => 'o',
        'ğ' => 'g',

        // Russian
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sh',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye',
        'І' => 'I',
        'Ї' => 'Yi',
        'Ґ' => 'G',
        'є' => 'ye',
        'і' => 'i',
        'ї' => 'yi',
        'ґ' => 'g',

        // Czech
        'Č' => 'C',
        'Ď' => 'D',
        'Ě' => 'E',
        'Ň' => 'N',
        'Ř' => 'R',
        'Š' => 'S',
        'Ť' => 'T',
        'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c',
        'ď' => 'd',
        'ě' => 'e',
        'ň' => 'n',
        'ř' => 'r',
        'š' => 's',
        'ť' => 't',
        'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A',
        'Ć' => 'C',
        'Ę' => 'e',
        'Ł' => 'L',
        'Ń' => 'N',
        'Ó' => 'o',
        'Ś' => 'S',
        'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a',
        'ć' => 'c',
        'ę' => 'e',
        'ł' => 'l',
        'ń' => 'n',
        'ó' => 'o',
        'ś' => 's',
        'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A',
        'Č' => 'C',
        'Ē' => 'E',
        'Ģ' => 'G',
        'Ī' => 'i',
        'Ķ' => 'k',
        'Ļ' => 'L',
        'Ņ' => 'N',
        'Š' => 'S',
        'Ū' => 'u',
        'Ž' => 'Z',
        'ā' => 'a',
        'č' => 'c',
        'ē' => 'e',
        'ģ' => 'g',
        'ī' => 'i',
        'ķ' => 'k',
        'ļ' => 'l',
        'ņ' => 'n',
        'š' => 's',
        'ū' => 'u',
        'ž' => 'z'
	);
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}

	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
// Check If User is Logged in
function isLogged()
{
	if (isset($_SESSION['user_id']) && ! empty($_SESSION['user_id'])) {
		$id = getUserIdFromSessionId($_SESSION['user_id']);
		if (is_numeric($id) && ! empty($id)) {
			return true;
		}
	} elseif (! empty($_COOKIE['user_id']) && ! empty($_COOKIE['user_id'])) {
		$id = getUserIdFromSessionId($_COOKIE['user_id']);
		if (is_numeric($id) && ! empty($id)) {
			return true;
		}
	} else {
		return false;
	}
}
function isLink($string)
{
    global $site_url;
    return $site_url . '/' . $string;
}
// Create SEO Link
function seoLink($query = '')
{
	global $carovl, $config;
    if ($carovl['config']['seo_link'] == 1) {
        $query = preg_replace(array(
            '/^index\.php\?page=welcome&page=reset-password&user_id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=welcome&last_url=(.*)$/i',
            '/^index\.php\?page=admincp&tab=([^\/]+)$/i',
            '/^index\.php\?page=([^\/]+)&query=(.*)$/i',
            '/^index\.php\?page=register&redirect_to=(.*)$/i',
            '/^index\.php\?page=login&redirect_to=(.*)$/i',
            '/^index\.php\?page=([^\/]+)&u=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=timeline&u=([A-Za-z0-9_]+)&type=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?page=timeline&u=([A-Za-z0-9_]+)&ref=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?page=setting&tab=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?page=setting&user=([A-Za-z0-9_]+)&tab=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?page=post&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=post&id=([A-Za-z0-9_]+)&ref=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=new-article$/i',
            '/^index\.php\?page=edit-article&id=([^\/]+)$/i',
            '/^index\.php\?page=article&id=([^\/]+)$/i',
            '/^index\.php\?page=events$/i',
            '/^index\.php\?page=new-event$/i',
            '/^index\.php\?page=view-event&id=(\d+)$/i',
            '/^index\.php\?page=edit-event&id=(\d+)$/i',
            '/^index\.php\?page=events-going$/i',
            '/^index\.php\?page=events-interested$/i',
            '/^index\.php\?page=events-invited$/i',
            '/^index\.php\?page=events-past$/i',
            '/^index\.php\?page=groups$/i',
            '/^index\.php\?page=create-group$/i',
            '/^index\.php\?page=group-setting&group=([A-Za-z0-9_]+)&tab=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?page=group-setting&group=([^\/]+)$/i',
            '/^index\.php\?page=([^\/]+)&type=([^\/]+)$/i',
            '/^index\.php\?page=([^\/]+)&hash=([^\/]+)$/i',
            '/^index\.php\?page=product&id=([^\/]+)$/i',
            '/^index\.php\?page=edit-product&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=welcome$/i',
            '/^index\.php\?page=home$/i',
            '/^index\.php\?page=([^\/]+)$/i'
        ), array(
            $config['site_url'] . '/reset-password/$1',
            $config['site_url'] . '/welcome/?last_url=$1',
            $config['site_url'] . '/admincp/$1',
            $config['site_url'] . '/search/$2',
            $config['site_url'] . '/register/?redirect_to=$1',
            $config['site_url'] . '/login/?redirect_to=$1',
            $config['site_url'] . '/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/$1?ref=$2',
            $config['site_url'] . '/setting/$1',
            $config['site_url'] . '/setting/$1/$2',
            $config['site_url'] . '/post/$1',
            $config['site_url'] . '/post/$1?ref=$2',
            $config['site_url'] . '/new-article/',
            $config['site_url'] . '/edit-article/$1',
            $config['site_url'] . '/article/$1',
            $config['site_url'] . '/events/',
            $config['site_url'] . '/events/new-event/',
            $config['site_url'] . '/events/$1/',
            $config['site_url'] . '/events/edit-event/$1/',
            $config['site_url'] . '/events/going/',
            $config['site_url'] . '/events/interested/',
            $config['site_url'] . '/events/invited/',
            $config['site_url'] . '/events/past/',
            $config['site_url'] . '/groups/',
            $config['site_url'] . '/groups/create-group/',
            $config['site_url'] . '/group-setting/$1/$2',
            $config['site_url'] . '/group-setting/$1',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/product/$1',
            $config['site_url'] . '/edit-product/$1',
            $config['site_url'],
            $config['site_url'],
            $config['site_url'] . '/$1'
        ), $query);
    } else {
        $query = $config['site_url'] . '/' . $query;
    }
	return $query;
}
// Secure Post Data
function secureIt($string, $censored_words = 1, $br = true)
{
	global $sql_connect;
	$string = trim($string);
	$string = mysqli_real_escape_string($sql_connect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
	$string = stripslashes($string);
	$string = str_replace('&amp;#', '&#', $string);
	if ($censored_words == 1) {
		global $config;
		$censored_words = @explode(",", $config['censored_words']);
		foreach ($censored_words as $censored_word) {
			$censored_word = trim($censored_word);
			$string = str_replace($censored_word, '*****', $string);
		}
	}
	return $string;
}
// Return Value From Query
function sqlResult($res, $row = 0, $col = 0)
{
	$numrows = mysqli_num_rows($res);
	if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
		mysqli_data_seek($res, $row);
		$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
		if (isset($resrow[$col])) {
			return $resrow[$col];
		}
	}
	return false;
}
// Get User IP Address
function getIpAddress()
{
	if (! empty($_SERVER['HTTP_CLIENT_IP']) && validateIp($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($ip_list as $ip) {
                    if (validateIp($ip)) {
                        return $ip;
                    }
                }
        } else {
            if (validateIp($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
    }
    if (! empty($_SERVER['HTTP_X_FORWARDED']) && validateIp($_SERVER['HTTP_X_FORWARDED'])) {
        return $_SERVER['HTTP_X_FORWARDED'];
    }
    if (! empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validateIp($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    if (! empty($_SERVER['HTTP_FORWARDED_FOR']) && validateIp($_SERVER['HTTP_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (! empty($_SERVER['HTTP_FORWARDED']) && validateIp($_SERVER['HTTP_FORWARDED'])) {
        return $_SERVER['HTTP_FORWARDED'];
    }
    return $_SERVER['REMOTE_ADDR'];
}
// Generate Random String
function generateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumber = true, $usespecial = false)
{
	$charset = '';
	if ($uselower) {
		$charset .= 'abcdefghijklmnopqrstuvwxyz';
	}
	if ($useupper) {
		$charset .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
	if ($usenumber) {
		$charset .= '123456789';
	}
	if ($usespecial) {
		$charset .= '~@#$%^*()_+-={}|][';
	}
	if ($minlength > $maxlength) {
		$length = mt_rand($maxlength, $minlength);
	} else {
		$length = mt_rand($minlength, $maxlength);
	}
	$key = '';
	for ($i=0; $i < $length; $i++) { 
		$key .= $charset[(mt_rand(0, strlen($charset) - 1))];
	}
	return $key;
}
// Compress Image
function compressImage($source_url, $destination_url, $quality)
{
    $imgsize = getimagesize($source_url);
    $finfof = $imgsize['mime'];
    $image_c = 'imagejpeg';
    if ($finfof == 'image/jpeg') {
        $image = @imagecreatefromjpeg($source_url);
    } elseif ($finfof == 'image/gif') {
        $image = @imagecreatefromgif($source_url);
    } elseif ($finfof == 'image/png') {
        $image = @imagecreatefrompng($source_url);
    } else {
        $image = @imagecreatefromjpeg($source_url);
    }
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($source_url);
        if (! empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = @imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = @imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = @imagerotate($image, 90, 0);
                    break;
            }
        }
    }
    @imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}
// Resize and Crop Image
function resizeCropImage($max_width, $max_height, $source_file, $dst_dir, $quality = 80)
{
	$imgsize = @getimagesize($source_file);
	$width = $imgsize[0];
	$height = $imgsize[1];
	$mime = $imgsize['mime'];
	$image = "imagejpeg";
	switch ($mime) {
		case 'image/gif':
			$image_create = "imagecreatefromgif";
			break;
		case 'image/png':
			$image_create = "imagecreatefrompng";
			break;
		case 'image/jpeg':
			$image_create = "imagecreatefromjpeg";
			break;
		default:
			return false;
			break;
	}
	$dst_img = @imagecreatetruecolor($max_width, $max_height);
	$src_img = @$image_create($source_file);
	if (function_exists('exif_read_data')) {
		$exif = @exif_read_data($source_file);
		$another_image = false;
		if (! empty($exif['Orientation'])) {
			switch ($exif['Orientation']) {
				case 3:
					$src_img = @imagerotate($src_img, 180, 0);
					@imagejpeg($src_img, $dst_dir, $quality);
					$another_image = true;
					break;
				case 6:
					$src_img = @imagerotate($src_img, -90, 0);
					@imagejpeg($src_img, $dst_dir, $quality);
					$another_image = true;
					break;
				case 8:
					$src_img = @imagerotate($src_img, 90, 0);
					@imagejpeg($src_img, $dst_dir, $quality);
					$another_image = true;
					break;
			}
		}
		if ($another_image == true) {
			$imgsize = @getimagesize($dst_dir);
			if ($width > 0 && $height > 0) {
				$width = $imgsize[0];
				$height = $imgsize[1];
			}
		}
	}
	@$width_new = $height * $max_width / $max_height;
	@$height_new = $width * $max_height / $max_width;
	if ($width_new > $width) {
		$h_point = (($height - $height_new) / 2);
		@imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	} else {
		$w_point = (($width - $width_new) / 2);
		@imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	}
	@imagejpeg($dst_img, $dst_dir, $quality);
	if ($dst_img) {
		@imagedestroy($dst_img);
	}
	if ($src_img) {
		@imagedestroy($src_img);
	}
	return true;
}
// Time to String Conversion
function timeElapsedString($ptime)
{
	global $carovl;
	$etime = time() - $ptime;
	if ($etime < 1) {
		return '0 seconds';
	}
	$a = array(
		365 * 24 * 60 * 60 => $carovl['lang']['year'],
		30 * 24 * 60 * 60 =>  $carovl['lang']['month'],
		24 * 60 * 60 => $carovl['lang']['day'],
		60 * 60 => $carovl['lang']['hour'],
		60 => $carovl['lang']['minute'],
		1 => $carovl['lang']['second']
	);
	$a_plural = array(
		$carovl['lang']['year'] => $carovl['lang']['years'],
		$carovl['lang']['month'] => $carovl['lang']['months'],
		$carovl['lang']['day'] => $carovl['lang']['days'],
		$carovl['lang']['hour'] => $carovl['lang']['hours'],
		$carovl['lang']['minute'] => $carovl['lang']['minutes'],
		$carovl['lang']['second'] => $carovl['lang']['seconds']
	);
	foreach ($a as $secs => $str) {
		$d = $etime / $secs;
		if ($d >= 1) {
			$r = round($d);
			$time_ago = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
			return $time_ago;
		}
	}
}
function br2nl($st)
{
    $breaks = array(
        "\r\n",
        "\r",
        "\n"
    );
    $st = str_replace($breaks, "", $st);
    $st_no_lb = preg_replace("/\r|\n/", "", $st);
    return preg_replace('/<br(\s+)?\/?>/i', "\r", $st_no_lb);
}
function sizeFormat($bytes)
{
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;
    if (($bytes >= 0) && ($bytes < $kb)) {
        return $bytes . ' B';
    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
        return ceil($bytes / $kb) . ' KB';
    } elseif (($bytes >= $mb) && ($bytes < $gb)) {
        return ceil($bytes / $mb) . ' MB';
    } elseif (($bytes >= $gb) && ($bytes < $tb)) {
        return ceil($bytes / $gb) . ' GB';
    } elseif ($bytes >= $tb) {
        return ceil($bytes / $tb) . ' TB';
    } else {
        return $bytes . ' B';
    }
}
function maxSizeFileUpload()
{
    $max_upload = returnBytes(ini_get('upload_max_filesize'));
    $max_post = returnBytes(ini_get('post_max_size'));
    $memory_limit = returnBytes(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit);
}
function returnBytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}
function createNewBackup($db_host, $db_user, $db_pass, $db_name, $tables = false, $backup_name = false)
{
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $mysqli->select_db($db_name);
    $mysqli->query("SET NAMES 'utf-8'");
    $query_tables = $mysqli->query("SHOW TABLES");
    while ($row = $query_tables->fetch_row()) {
        $target_tables[] = $row[0];
    }
    if ($tables !== false) {
        $target_tables = array_intersect($target_tables, $tables);
    }
    $content = "-- phpMyAdmin SQL Dump
                -- http://www.phpmyadmin.net
                --
                -- Host Connection Info: " . $mysqli->host_info . "
                -- Generation Time: " . date('F d, Y \a\t H:i A ( e )') . "
                -- Server version: " . mysqli_get_server_info($mysqli) . "
                -- PHP Version: " . PHP_VERSION . "
                --\n
                SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
                SET time_zone = \"+00:00\";\n
                /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
                /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
                /*!40101 SET NAMES utf8mb4 */;\n\n";
    foreach ($target_tables as $table) {
        $result = $mysqli->query("SELECT * FROM " . $table);
        $fields_amount = $result->field_count;
        $rows_num = $mysqli->affected_rows;
        $res = $mysqli->query("SHOW CREATE TABLE " . $table);
        $tableMLine = $res->fetch_row();
        $content = (! isset($content) ? '' : $content) . "
        -- ---------------------------------------------------------
        --
        -- Table structure for table : `{$table}`
        --
        -- ---------------------------------------------------------\n" . $tableMLine[1] . ";\n";
        for ($i = 0, $st_counter = 0; $i < $fields_amount; $i ++, $st_counter = 0) { 
            while ($row = $result->fetch_row()) {
                if ($st_counter % 100 == 0 || $st_counter == 0) {
                    $content .= "\n--
                                -- Dumping data for table `{$table}`
                                --\n\nINSERT INTO " . $table . " VALUES";
                }
                $content .= "\n(";
                for ($j = 0; $j < $fields_amount; $j++) { 
                    $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                    if (isset($row[$j])) {
                        $content .= '"' . $row[$j] . '"';
                    } else {
                        $content .= '""';
                    }
                    if ($j < ($fields_amount - 1)) {
                        $content .= ',';
                    }
                }
                $content .= ")";
                if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                    $content .= ";\n";
                } else {
                    $content .= ",";
                }
                $st_counter = $st_counter + 1;
            }
        }
        $content .= "";
    }
    $content .= "
                /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
                /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
                /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
    if (! file_exists('app_backups/' . date('d-m-Y'))) {
        @mkdir('app_backups/' . date('d-m-Y'), 0777, true);
    }
    if (! file_exists('app_backups/' . date('d-m-Y') . '/' .  time())) {
        mkdir('app_backups/' . date('d-m-Y') . '/' . time(), 0777, true);
    }
    if (! file_exists('app_backups/' . date('d-m-Y') . '/' . time() . '/index.html')) {
        $f = @fopen('app_backups/' . date('d-m-Y') . '/' . time() . '/index.html', 'a+');
        @fwrite($f, "");
        @fclose($f);
    }
    if (! file_exists("app_backups/.htaccess")) {
        $f = @fopen('app_backups/.htaccess', 'a+');
        @fwrite($f, "deny from all\nOption -Indexes");
        @fclose($f);
    }
    if (! file_exists('app_backups/' . date('d-m-Y') . '/index.html')) {
        $f = @fopen('app_backups/' . date('d-m-Y') . '/index.html', 'a+');
        @fwrite($f, "");
        @fclose($f);
    }
    if (! file_exists('app_backups/index.html')) {
        $f = @fopen('app_backups/index.html', 'a+');
        @fwrite($f, "");
        @fclose($f);
    }
    $folder_name = 'app_backups/' . date('d-m-Y') . '/' . time();
    $put = @file_put_contents($folder_name . '/' . 'SQL-Backup-' . time() . '-' . date('d-m-Y') . '.sql', $content);
    if ($put) {
        $rootPath = realpath('./');
        $zip = new ZipArchive();
        $open = $zip->open($folder_name . '/Files-Backup-' . time() . '-' . date('d-m-Y') . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($open !== true) {
            return false;
        }
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file) {
            if (! preg_match('/\bapp_backups\b/', $file)) {
                if (! $file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        $zip->close();
        $mysqli->query("UPDATE " . T_CONFIG . " SET `value` = '" . date('d-m-Y') . "' WHERE `name` = 'last_backup'");
        $mysqli->close();
        return true;
    } else {
        return false;
    }
}
function folderSize($dir)
{
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
    foreach ($dir_array as $key => $filename) {
        if ($filename != '..' && $filename != '.' && $filename != '.htaccess') {
            if (is_dir($dir . '/' . $filename)) {
                $new_foldersize = folderSize($dir . '/' . $filename);
                $count_size = $count_size + $new_foldersize;
            } elseif (is_file($dir . '/' . $filename)) {
                $count_size = $count_size + filesize($dir . '/' . $filename);
                $count++;
            }
        }
    }
    return $count_size;
}
function clearCache()
{
    $path = 'cache';
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if (strripos($file, '.tmp') !== false) {
                @unlink($path . '/' . $file);
            }
        }
    }
}
?>