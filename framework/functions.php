<?php
/**
 * Decodes HTML entities
 *
 * @param string $string
 * @return string
 */
function themex_html($string) {
	return do_shortcode(html_entity_decode($string));
}

/**
 * Removes slashes
 *
 * @param string $string
 * @return string
 */
function themex_stripslashes($string) {
	if(!is_array($string)) {
		return stripslashes(stripslashes($string));
	}
	
	return $string;	
}

/**
 * Gets string excerpt
 *
 * @param string $excerpt
 * @param string $url
 * @return string
 */
function themex_excerpt($excerpt, $url) {
	$button=' <a href="'.$url.'" title="'.__('Read More', 'lovestory').'" class="icon-circle-arrow-right"></a>';

	if(substr($excerpt, -4)=='</p>') {
		$excerpt=substr($excerpt, 0, -4).$button.'</p>';
	} else {
		$excerpt.=$button;
	}
	
	return $excerpt;	
}

/**
 * Gets string sections
 *
 * @param string $content
 * @param int $sections
 * @return string
 */
function themex_sections($content, $sections) {	
	$paragraphs=explode('</p>', $content);
	$excerpt='';

	for($j=0; $j<intval($sections); $j++) {
		if(isset($paragraphs[$j])) {
			$excerpt.=$paragraphs[$j].'</p>';
		}
	}
	
	return $excerpt;
}

/**
 * Gets page number
 *
 * @return int
 */
function themex_paged() {
	global $wp_rewrite;
	
	$paged=get_query_var('paged')?get_query_var('paged'):1;
	$paged=(get_query_var('page'))?get_query_var('page'):$paged;
	
	if($paged==1) {
		$segments=explode('/', $_SERVER['REQUEST_URI']);
		$index=array_search($wp_rewrite->pagination_base, $segments);
		
		if($index!==false && isset($segments[$index+1]) && is_numeric($segments[$index+1])) {
			$paged=$segments[$index+1];
		}
	}
	
	return $paged;
}

/**
 * Counts pages number
 *
 * @param int $posts
 * @param int $limit
 * @return int
 */
function themex_pages($posts, $limit) {
	return ceil(count($posts)/intval($limit));
}

/**
 * Sends encoded email
 *
 * @param string $recipient
 * @param string $subject
 * @param string $message
 * @return bool
 */
function themex_mail($recipient, $subject, $message, $keywords=array()) {
	$headers="MIME-Version: 1.0".PHP_EOL;
	$headers.="Content-Type: text/html; charset=UTF-8".PHP_EOL;
	
	if(!empty($keywords)) {
		foreach($keywords as $keyword => $value) {
			$message=str_replace('%'.$keyword.'%', $value, $message);
		}
	}
	
	if(wp_mail($recipient, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers)) {
		return true;
	}
	
	return false;
}

/**
 * Sets array keys
 *
 * @param array $array
 * @return array
 */
function themex_keys($array) {
	$filtered=array();
	foreach($array as $key=>$value) {
		if(isset($value['ID']) && !empty($value['ID']) && !isset($filtered[$value['ID']])) {
			$filtered[$value['ID']]=$value;
		}
	}

	return $filtered;
}

/**
 * Gets period name
 *
 * @param int $period
 * @return string
 */
function themex_period($period) {	
	switch($period) {
		case 7: 
			$period=__('week', 'lovestory');
		break;
		
		case 31: 
			$period=__('month', 'lovestory');
		break;
		
		case 365: 
			$period=__('year', 'lovestory');
		break;
		
		default:
			$period=round($period/31).' '.__('months', 'lovestory');
		break;
	}
	
	return $period;
}

/**
 * Gets array value
 *
 * @param string $key
 * @param array $array
 * @param string $default
 * @return mixed
 */
function themex_array_value($key, $array, $default='') {
	$value='';
	
	if(isset($array[$key])) {
		if(is_array($array[$key])) {
			$value=reset($array[$key]);
		} else {
			$value=$array[$key];
		}		
	} else if ($default!='') {
		$value=$default;
	}
	
	return $value;
}

/**
 * Gets current URL
 *
 * @return string
 */
function themex_url() {
	$url=@($_SERVER["HTTPS"] != 'on') ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
	$url.=($_SERVER["SERVER_PORT"]!==80) ? ":".$_SERVER["SERVER_PORT"] : "";
	$url.=$_SERVER["REQUEST_URI"];
	
	return $url;
}

/**
 * Gets static string
 *
 * @param string $key
 * @param string $type
 * @param string $default
 * @return string
 */
function themex_get_string($key, $type, $default) {
	$name=$key.'-'.$type;
	$string=$default;	
	$strings=array();
	include(THEMEX_PATH.'strings.php');

	if(isset($strings[$name])) {
		$string=$strings[$name];
	}
	
	return themex_stripslashes($string);
}

/**
 * Adds static string
 *
 * @param string $key
 * @param string $type
 * @param string $string
 * @return void
 */
function themex_add_string($key, $type, $string) {
	$name=$key.'-'.$type;
	$string=themex_stripslashes($string);
	$strings=array();
	include(THEMEX_PATH.'strings.php');
	
	if(!isset($strings[$name])) {
		$string=str_replace("'", "’", $string);
		$file=@fopen(THEMEX_PATH.'strings.php', 'a');
		
		if($file!==false) {
			fwrite($file, "\r\n".'$strings'."['".$name."']=__('".$string."', 'lovestory');");
			fclose($file);
		}
	}
}

/**
 * Removes static strings
 *
 * @return void
 */
function themex_remove_strings() {
	$file=@fopen(THEMEX_PATH.'strings.php', 'w');	
	if($file!==false) {
		fwrite($file, '<?php ');
		fclose($file);
	}
}

/**
 * Sanitizes key
 *
 * @param string $string
 * @return string
 */
function themex_sanitize_key($string) {
	$replacements=array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
 
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
 
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
 
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
 
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
 
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	
	$string=trim($string);
	$string=str_replace(array_keys($replacements), $replacements, $string);
	$string=preg_replace('/\s+/', '-', $string);
	$filtered=$string;
	
	$string=preg_replace('/[^A-Za-z0-9-]/', '', $string);
	$string=strtolower($string);
	
	if(empty($string) || $string[0]=='-') {
		$string='a'.md5($filtered);
	}
	
	return $string;
}

/**
 * Resize image
 *
 * @param string $url
 * @param int $width
 * @param int $height
 * @return array
 */
function themex_resize($url, $width, $height) {
	add_filter('image_resize_dimensions', 'themex_scale', 10, 6);

	$upload_info=wp_upload_dir();
	$upload_dir=$upload_info['basedir'];
	$upload_url=$upload_info['baseurl'];
	
	//check prefix
	$http_prefix='http://';
	$https_prefix='https://';
	
	if(!strncmp($url, $https_prefix, strlen($https_prefix))){
		$upload_url=str_replace($http_prefix, $https_prefix, $upload_url);
	} else if (!strncmp($url, $http_prefix, strlen($http_prefix))){
		$upload_url=str_replace($https_prefix, $http_prefix, $upload_url);		
	}	

	//check URL
	if (strpos($url, $upload_url)===false) {
		return false;
	}

	//define path
	$rel_path=str_replace($upload_url, '', $url);
	$img_path=$upload_dir.$rel_path;

	//check file
	if (!file_exists($img_path) or !getimagesize($img_path)) {
		return false;
	}

	//get file info
	$info=pathinfo($img_path);
	$ext=$info['extension'];
	list($orig_w, $orig_h)=getimagesize($img_path);

	//get image size
	$dims=image_resize_dimensions($orig_w, $orig_h, $width, $height, true);
	$dst_w=$dims[4];
	$dst_h=$dims[5];

	//resize image
	if(!$dims && ((($height===null && $orig_w==$width) xor ($width===null && $orig_h==$height)) xor ($height==$orig_h && $width==$orig_w))) {
		$img_url=$url;
		$dst_w=$orig_w;
		$dst_h=$orig_h;
	} else {
		$suffix=$dst_w.'x'.$dst_h;
		$dst_rel_path=str_replace('.'.$ext, '', $rel_path);
		$destfilename=$upload_dir.$dst_rel_path.'-'.$suffix.'.'.$ext;

		if(!$dims) {
			return false;
		} else if(file_exists($destfilename) && getimagesize($destfilename) && empty($_FILES)) {
			$img_url=$upload_url.$dst_rel_path.'-'.$suffix.'.'.$ext;
		} else {
			if (function_exists('wp_get_image_editor')) {
				$editor=wp_get_image_editor($img_path);
				if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, true))) {
					return false;
				}

				$resized_file=$editor->save();

				if (!is_wp_error($resized_file)) {
					$resized_rel_path=str_replace($upload_dir, '', $resized_file['path']);
					
					$img_url=$upload_url.$resized_rel_path.'?'.time();
				} else {
					return false;
				}
			} else {
				$resized_img_path=image_resize($img_path, $width, $height, true);
				
				if (!is_wp_error($resized_img_path)) {
					$resized_rel_path=str_replace($upload_dir, '', $resized_img_path);
					$img_url=$upload_url.$resized_rel_path;
				} else {
					return false;
				}
			}
		}
	}

	remove_filter('image_resize_dimensions', 'themex_scale');
	return $img_url;
}

/**
 * Scale image
 *
 * @param string $default
 * @param int $orig_w
 * @param int $orig_h
 * @param int $dest_w
 * @param int $dest_h
 * @param bool $crop
 * @return array
 */
function themex_scale($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {
	$aspect_ratio=$orig_w/$orig_h;
	$new_w=$dest_w;
	$new_h=$dest_h;

	if (!$new_w) {
		$new_w=intval($new_h*$aspect_ratio);
	}

	if (!$new_h) {
		$new_h=intval($new_w/$aspect_ratio);
	}

	$size_ratio=max($new_w/$orig_w, $new_h/$orig_h);
	$crop_w=round($new_w/$size_ratio);
	$crop_h=round($new_h/$size_ratio);

	$s_x=floor(($orig_w-$crop_w)/2);
	$s_y=floor(($orig_h-$crop_h)/2);
	$scale=array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);

	return $scale;
}