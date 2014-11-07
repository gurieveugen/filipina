<?php
/**
 * Themex Facebook
 *
 * Handles Facebook data
 *
 * @class ThemexFacebook
 * @author Themex
 */
class ThemexFacebook {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {	
		if(self::isActive()) {
			//load API
			add_action('wp_footer', array(__CLASS__,'loadAPI'));
			
			//render page
			add_action('init', array(__CLASS__,'renderPage'));
			
			//login user
			add_filter('init', array(__CLASS__,'loginUser'), 90);
			
			//logout user
			add_action('wp_logout', array(__CLASS__,'logoutUser'));
		}
	}
	
	/**
	 * Loads Facebook API
     *
     * @access public
	 * @param bool $logout
     * @return void
     */
	public static function loadAPI($logout=false) {
		$out='<div id="fb-root"></div>
		<script type="text/javascript">
		window.fbAsyncInit = function() {
		FB.init({			
		appId      : "'.ThemexCore::getOption('user_facebook_id').'",
		channelUrl : "'.home_url('?facebook_channel=1').'",
		status     : true,
		cookie     : true,
		xfbml      : true,
		oauth	   : true
		});';

		if($logout) {
			$out.='FB.getLoginStatus(function(response) {
			if (response.status === "connected") {
			FB.logout(function(response) {
			window.location.href="'.home_url().'";
			});
			} else {
			window.location.href="'.home_url().'";
			}
			});';
		}

		$out.='};
		(function(d){
		var js, id = "facebook-jssdk"; if (d.getElementById(id)) {return;}
		js = d.createElement("script"); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/'.self::getLocale().'/all.js";
		d.getElementsByTagName("head")[0].appendChild(js);
		}(document));
		</script>';
		
		echo $out;
	}
	
	/**
	 * Renders Facebook page
     *
     * @access public
     * @return void
     */
	public static function renderPage() {
		if (isset($_GET['facebook_channel'])) {
			$limit=60*60*24*365;
			header('Pragma: public');
			header('Cache-Control: max-age='.$limit);
			header('Expires: '.gmdate('D, d M Y H:i:s', current_time('timestamp')+$limit).' GMT');
			echo '<script src="//connect.facebook.net/'.self::getLocale().'/all.js"></script>';
			exit;
		}
	}
	
	/**
	 * Logins Facebook user
     *
     * @access public
     * @return void
     */
	public static function loginUser() {
		if(isset($_GET['facebook_login']) && !is_user_logged_in() && isset($_COOKIE['fbsr_'.ThemexCore::getOption('user_facebook_id')])) {
			$cookie=self::decodeCookie();			
			if(isset($cookie['user_id'])) {
				$users=get_users(array(
					'meta_key' => 'facebook_id', 
					'meta_value' => $cookie['user_id'],
					'role' => 'subscriber',
				));
					
				if(!empty($users)) {
					$user=reset($users);
					wp_set_auth_cookie($user->ID, true);
					wp_redirect(get_author_posts_url($user->ID));				
					exit();
				} else {
					$profile=self::getProfile($cookie['user_id'], array(
						'code' => $cookie['code'],
					));
					
					if(isset($profile['email'])) {
						if(!isset($profile['username'])) {
							if(isset($profile['first_name'])) {
								$profile['username']=$profile['first_name'];
							} else if(isset($profile['last_name'])) {
								$profile['username']=$profile['last_name'];
							}
						}
						
						$profile['username']=sanitize_user($profile['username']);
						while(username_exists($profile['username'])) {
							$profile['username'].=rand(0,9);
						}
						
						$user=wp_create_user($profile['username'], wp_generate_password(10), $profile['email']);
						

						echo print_r($user);
						file_put_contents( 
							ABSPATH.'/wp-content/themes/lovestory/framework/classes/log.txt', 
							print_r( 
								array( 
									$user, $profile,
									'https://graph.facebook.com/'.$cookie['user_id'].'?'.http_build_query(array('code' => $cookie['code'])),
									self::getUserPicture(),
									$_COOKIE,
									ThemexCore::getOption('user_facebook_secret'),
									$cookie,
									get_user_meta($user)
								), 
								true 
							) 
						);
						ThemexUser::updateAvatar( $user, self::getUserPicture() );
						if(!is_wp_error($user)) {
							$defaults = array(
								'gender' => 'male',
								'hometown' => array( 'name' => 'Moscow, Russia' ),
							);

							$profile = array_merge( $defaults, $profile );

							$gender = $profile['gender'];
							$loc    = explode( ', ', $profile['hometown']['name'] );

							update_user_meta( $user, '_'.THEMEX_PREFIX.'gender', $gender );
							update_user_meta( $user, '_'.THEMEX_PREFIX.'country', self::getCountryCode( $loc[1] ) );
							update_user_meta( $user, '_'.THEMEX_PREFIX.'city', $loc[0] );

							wp_new_user_notification($user);
							add_user_meta($user, 'facebook_id', $profile['id'], true);							
							self::updateImage($profile['id'], $user);
						
							if(isset($profile['first_name'])) {
								update_user_meta($user, 'first_name', $profile['first_name']);
							}
							
							if(isset($profile['last_name'])) {
								update_user_meta($user, 'last_name', $profile['last_name']);
							}							
							wp_set_auth_cookie($user, true);
							wp_redirect(get_author_posts_url($user));
							
						} else {
							echo 'LOGIN USER HERE redirect';
							die();
							//self::logoutUser();
						}

						exit();
					}
				}				
			}
		}
	}

	public static function getCountryCode($country)
	{
		$countries = array(
			'AF' => __('Afghanistan', 'lovestory'),
			'AL' => __('Albania', 'lovestory'),
			'DZ' => __('Algeria', 'lovestory'),
			'AS' => __('American Samoa', 'lovestory'),
			'AD' => __('Andorra', 'lovestory'),
			'AO' => __('Angola', 'lovestory'),
			'AI' => __('Anguilla', 'lovestory'),
			'AQ' => __('Antarctica', 'lovestory'),
			'AG' => __('Antigua And Barbuda', 'lovestory'),
			'AR' => __('Argentina', 'lovestory'),
			'AM' => __('Armenia', 'lovestory'),
			'AW' => __('Aruba', 'lovestory'),
			'AU' => __('Australia', 'lovestory'),
			'AT' => __('Austria', 'lovestory'),
			'AZ' => __('Azerbaijan', 'lovestory'),
			'BS' => __('Bahamas', 'lovestory'),
			'BH' => __('Bahrain', 'lovestory'),
			'BD' => __('Bangladesh', 'lovestory'),
			'BB' => __('Barbados', 'lovestory'),
			'BY' => __('Belarus', 'lovestory'),
			'BE' => __('Belgium', 'lovestory'),
			'BZ' => __('Belize', 'lovestory'),
			'BJ' => __('Benin', 'lovestory'),
			'BM' => __('Bermuda', 'lovestory'),
			'BT' => __('Bhutan', 'lovestory'),
			'BO' => __('Bolivia', 'lovestory'),
			'BA' => __('Bosnia And Herzegovina', 'lovestory'),
			'BW' => __('Botswana', 'lovestory'),
			'BV' => __('Bouvet Island', 'lovestory'),
			'BR' => __('Brazil', 'lovestory'),
			'IO' => __('British Indian Ocean Territory', 'lovestory'),
			'BN' => __('Brunei', 'lovestory'),
			'BG' => __('Bulgaria', 'lovestory'),
			'BF' => __('Burkina Faso', 'lovestory'),
			'BI' => __('Burundi', 'lovestory'),
			'KH' => __('Cambodia', 'lovestory'),
			'CM' => __('Cameroon', 'lovestory'),
			'CA' => __('Canada', 'lovestory'),
			'CV' => __('Cape Verde', 'lovestory'),
			'KY' => __('Cayman Islands', 'lovestory'),
			'CF' => __('Central African Republic', 'lovestory'),
			'TD' => __('Chad', 'lovestory'),
			'CL' => __('Chile', 'lovestory'),
			'CN' => __('China', 'lovestory'),
			'CX' => __('Christmas Island', 'lovestory'),
			'CC' => __('Cocos (Keeling) Islands', 'lovestory'),
			'CO' => __('Columbia', 'lovestory'),
			'KM' => __('Comoros', 'lovestory'),
			'CG' => __('Congo', 'lovestory'),
			'CK' => __('Cook Islands', 'lovestory'),
			'CR' => __('Costa Rica', 'lovestory'),
			'CI' => __('Cote D\'Ivorie (Ivory Coast)', 'lovestory'),
			'HR' => __('Croatia (Hrvatska)', 'lovestory'),
			'CU' => __('Cuba', 'lovestory'),
			'CY' => __('Cyprus', 'lovestory'),
			'CZ' => __('Czech Republic', 'lovestory'),
			'CD' => __('Democratic Republic Of Congo (Zaire)', 'lovestory'),
			'DK' => __('Denmark', 'lovestory'),
			'DJ' => __('Djibouti', 'lovestory'),
			'DM' => __('Dominica', 'lovestory'),
			'DO' => __('Dominican Republic', 'lovestory'),
			'TP' => __('East Timor', 'lovestory'),
			'EC' => __('Ecuador', 'lovestory'),
			'EG' => __('Egypt', 'lovestory'),
			'SV' => __('El Salvador', 'lovestory'),
			'GQ' => __('Equatorial Guinea', 'lovestory'),
			'ER' => __('Eritrea', 'lovestory'),
			'EE' => __('Estonia', 'lovestory'),
			'ET' => __('Ethiopia', 'lovestory'),
			'FK' => __('Falkland Islands (Malvinas)', 'lovestory'),
			'FO' => __('Faroe Islands', 'lovestory'),
			'FJ' => __('Fiji', 'lovestory'),
			'FI' => __('Finland', 'lovestory'),
			'FR' => __('France', 'lovestory'),
			'FX' => __('France, Metropolitan', 'lovestory'),
			'GF' => __('French Guinea', 'lovestory'),
			'PF' => __('French Polynesia', 'lovestory'),
			'TF' => __('French Southern Territories', 'lovestory'),
			'GA' => __('Gabon', 'lovestory'),
			'GM' => __('Gambia', 'lovestory'),
			'GE' => __('Georgia', 'lovestory'),
			'DE' => __('Germany', 'lovestory'),
			'GH' => __('Ghana', 'lovestory'),
			'GI' => __('Gibraltar', 'lovestory'),
			'GR' => __('Greece', 'lovestory'),
			'GL' => __('Greenland', 'lovestory'),
			'GD' => __('Grenada', 'lovestory'),
			'GP' => __('Guadeloupe', 'lovestory'),
			'GU' => __('Guam', 'lovestory'),
			'GT' => __('Guatemala', 'lovestory'),
			'GN' => __('Guinea', 'lovestory'),
			'GW' => __('Guinea-Bissau', 'lovestory'),
			'GY' => __('Guyana', 'lovestory'),
			'HT' => __('Haiti', 'lovestory'),
			'HM' => __('Heard And McDonald Islands', 'lovestory'),
			'HN' => __('Honduras', 'lovestory'),
			'HK' => __('Hong Kong', 'lovestory'),
			'HU' => __('Hungary', 'lovestory'),
			'IS' => __('Iceland', 'lovestory'),
			'IN' => __('India', 'lovestory'),
			'ID' => __('Indonesia', 'lovestory'),
			'IR' => __('Iran', 'lovestory'),
			'IQ' => __('Iraq', 'lovestory'),
			'IE' => __('Ireland', 'lovestory'),
			'IL' => __('Israel', 'lovestory'),
			'IT' => __('Italy', 'lovestory'),
			'JM' => __('Jamaica', 'lovestory'),
			'JP' => __('Japan', 'lovestory'),
			'JO' => __('Jordan', 'lovestory'),
			'KZ' => __('Kazakhstan', 'lovestory'),
			'KE' => __('Kenya', 'lovestory'),
			'KI' => __('Kiribati', 'lovestory'),
			'KW' => __('Kuwait', 'lovestory'),
			'KG' => __('Kyrgyzstan', 'lovestory'),
			'LA' => __('Laos', 'lovestory'),
			'LV' => __('Latvia', 'lovestory'),
			'LB' => __('Lebanon', 'lovestory'),
			'LS' => __('Lesotho', 'lovestory'),
			'LR' => __('Liberia', 'lovestory'),
			'LY' => __('Libya', 'lovestory'),
			'LI' => __('Liechtenstein', 'lovestory'),
			'LT' => __('Lithuania', 'lovestory'),
			'LU' => __('Luxembourg', 'lovestory'),
			'MO' => __('Macau', 'lovestory'),
			'MK' => __('Macedonia', 'lovestory'),
			'MG' => __('Madagascar', 'lovestory'),
			'MW' => __('Malawi', 'lovestory'),
			'MY' => __('Malaysia', 'lovestory'),
			'MV' => __('Maldives', 'lovestory'),
			'ML' => __('Mali', 'lovestory'),
			'MT' => __('Malta', 'lovestory'),
			'MH' => __('Marshall Islands', 'lovestory'),
			'MQ' => __('Martinique', 'lovestory'),
			'MR' => __('Mauritania', 'lovestory'),
			'MU' => __('Mauritius', 'lovestory'),
			'YT' => __('Mayotte', 'lovestory'),
			'MX' => __('Mexico', 'lovestory'),
			'FM' => __('Micronesia', 'lovestory'),
			'MD' => __('Moldova', 'lovestory'),
			'MC' => __('Monaco', 'lovestory'),
			'MN' => __('Mongolia', 'lovestory'),
			'MS' => __('Montserrat', 'lovestory'),
			'MA' => __('Morocco', 'lovestory'),
			'MZ' => __('Mozambique', 'lovestory'),
			'MM' => __('Myanmar (Burma)', 'lovestory'),
			'NA' => __('Namibia', 'lovestory'),
			'NR' => __('Nauru', 'lovestory'),
			'NP' => __('Nepal', 'lovestory'),
			'NL' => __('Netherlands', 'lovestory'),
			'AN' => __('Netherlands Antilles', 'lovestory'),
			'NC' => __('New Caledonia', 'lovestory'),
			'NZ' => __('New Zealand', 'lovestory'),
			'NI' => __('Nicaragua', 'lovestory'),
			'NE' => __('Niger', 'lovestory'),
			'NG' => __('Nigeria', 'lovestory'),
			'NU' => __('Niue', 'lovestory'),
			'NF' => __('Norfolk Island', 'lovestory'),
			'KP' => __('North Korea', 'lovestory'),
			'MP' => __('Northern Mariana Islands', 'lovestory'),
			'NO' => __('Norway', 'lovestory'),
			'OM' => __('Oman', 'lovestory'),
			'PK' => __('Pakistan', 'lovestory'),
			'PW' => __('Palau', 'lovestory'),
			'PA' => __('Panama', 'lovestory'),
			'PG' => __('Papua New Guinea', 'lovestory'),
			'PY' => __('Paraguay', 'lovestory'),
			'PE' => __('Peru', 'lovestory'),
			'PH' => __('Philippines', 'lovestory'),
			'PN' => __('Pitcairn', 'lovestory'),
			'PL' => __('Poland', 'lovestory'),
			'PT' => __('Portugal', 'lovestory'),
			'PR' => __('Puerto Rico', 'lovestory'),
			'QA' => __('Qatar', 'lovestory'),
			'RE' => __('Reunion', 'lovestory'),
			'RO' => __('Romania', 'lovestory'),
			'RU' => __('Russia', 'lovestory'),
			'RW' => __('Rwanda', 'lovestory'),
			'SH' => __('Saint Helena', 'lovestory'),
			'KN' => __('Saint Kitts And Nevis', 'lovestory'),
			'LC' => __('Saint Lucia', 'lovestory'),
			'PM' => __('Saint Pierre And Miquelon', 'lovestory'),
			'VC' => __('Saint Vincent And The Grenadines', 'lovestory'),
			'SM' => __('San Marino', 'lovestory'),
			'ST' => __('Sao Tome And Principe', 'lovestory'),
			'SA' => __('Saudi Arabia', 'lovestory'),
			'SN' => __('Senegal', 'lovestory'),
			'SC' => __('Seychelles', 'lovestory'),
			'SL' => __('Sierra Leone', 'lovestory'),
			'SG' => __('Singapore', 'lovestory'),
			'SK' => __('Slovak Republic', 'lovestory'),
			'SI' => __('Slovenia', 'lovestory'),
			'SB' => __('Solomon Islands', 'lovestory'),
			'SO' => __('Somalia', 'lovestory'),
			'ZA' => __('South Africa', 'lovestory'),
			'GS' => __('South Georgia And South Sandwich Islands', 'lovestory'),
			'KR' => __('South Korea', 'lovestory'),
			'ES' => __('Spain', 'lovestory'),
			'LK' => __('Sri Lanka', 'lovestory'),
			'SD' => __('Sudan', 'lovestory'),
			'SR' => __('Suriname', 'lovestory'),
			'SJ' => __('Svalbard And Jan Mayen', 'lovestory'),
			'SZ' => __('Swaziland', 'lovestory'),
			'SE' => __('Sweden', 'lovestory'),
			'CH' => __('Switzerland', 'lovestory'),
			'SY' => __('Syria', 'lovestory'),
			'TW' => __('Taiwan', 'lovestory'),
			'TJ' => __('Tajikistan', 'lovestory'),
			'TZ' => __('Tanzania', 'lovestory'),
			'TH' => __('Thailand', 'lovestory'),
			'TG' => __('Togo', 'lovestory'),
			'TK' => __('Tokelau', 'lovestory'),
			'TO' => __('Tonga', 'lovestory'),
			'TT' => __('Trinidad And Tobago', 'lovestory'),
			'TN' => __('Tunisia', 'lovestory'),
			'TR' => __('Turkey', 'lovestory'),
			'TM' => __('Turkmenistan', 'lovestory'),
			'TC' => __('Turks And Caicos Islands', 'lovestory'),
			'TV' => __('Tuvalu', 'lovestory'),
			'UG' => __('Uganda', 'lovestory'),
			'UA' => __('Ukraine', 'lovestory'),
			'AE' => __('United Arab Emirates', 'lovestory'),
			'UK' => __('United Kingdom', 'lovestory'),
			'US' => __('United States', 'lovestory'),
			'UM' => __('United States Minor Outlying Islands', 'lovestory'),
			'UY' => __('Uruguay', 'lovestory'),
			'UZ' => __('Uzbekistan', 'lovestory'),
			'VU' => __('Vanuatu', 'lovestory'),
			'VA' => __('Vatican City (Holy See)', 'lovestory'),
			'VE' => __('Venezuela', 'lovestory'),
			'VN' => __('Vietnam', 'lovestory'),
			'VG' => __('Virgin Islands (British)', 'lovestory'),
			'VI' => __('Virgin Islands (US)', 'lovestory'),
			'WF' => __('Wallis And Futuna Islands', 'lovestory'),
			'EH' => __('Western Sahara', 'lovestory'),
			'WS' => __('Western Samoa', 'lovestory'),
			'YE' => __('Yemen', 'lovestory'),
			'YU' => __('Yugoslavia', 'lovestory'),
			'ZM' => __('Zambia', 'lovestory'),
			'ZW' => __('Zimbabwe', 'lovestory'),
		);
		$countries = array_flip($countries);
		return $countries[$country];
	}

	/**
	 * Get user picture
	 * @return string --- facebook graph response
	 */
	public static function getUserPicture()
	{
		$cookie=self::decodeCookie();	
		$url = sprintf(
			'https://graph.facebook.com/%s/picture?%s',
			$cookie['user_id'],
			http_build_query(
				array(
					'code'     => $cookie['code'],
					'redirect' => 'false',
					'fields'   => 'url',
					'width'    => '213',
					'height'   => '213'
				)
			)
		);
		$body = wp_remote_get( $url, array('sslverify' => false) );
		$body = json_decode($body['body']);
		return $body->data->url;
	}
	
	/**
	 * Logouts Facebook user
     *
     * @access public
     * @return void
     */
	public static function logoutUser() {
		if(isset($_COOKIE['fbsr_'.ThemexCore::getOption('user_facebook_id')])) {
			$domain = '.'.parse_url(home_url('/'), PHP_URL_HOST);
			setcookie('fbsr_'.ThemexCore::getOption('user_facebook_id'), ' ', current_time('timestamp')-31536000, '/', $domain);
			
			$out='<html><head></head><body>';
			ob_start();
			self::loadAPI(true);
			$out.=ob_get_contents();
			ob_end_clean();
			$out.='</body></html>';
			
			echo $out;
			exit();
		}
	}
	
	/**
	 * Logouts Facebook profile
     *
     * @access public
	 * @param int $ID
	 * @param array $fields
     * @return mixed
     */
	public static function getProfile($ID, $fields=array()) {
		if (!empty($fields['code'])) {
			$request='https://graph.facebook.com/oauth/access_token?client_id='.ThemexCore::getOption('user_facebook_id').'&redirect_uri=&client_secret='.ThemexCore::getOption('user_facebook_secret').'&code='.$fields['code'];	
			$response=wp_remote_get($request, array('sslverify' => false));
			
			if (!is_wp_error($response) && wp_remote_retrieve_response_code($response)==200) {
				parse_str($response['body'], $response);
				$fields['access_token']=$response['access_token'];
			} else {
				return false;
			}
		}

		$url='https://graph.facebook.com/'.$ID.'?'.http_build_query($fields);
		$response=wp_remote_get($url, $fields);

		if (!is_wp_error($response) && $response) {
			$response=json_decode($response['body'], true);
			return $response;
		}
		return false;
	}
	
	/**
	 * Decode Facebook cookie
     *
     * @access public
     * @return array
     */
	public static function decodeCookie() {
		$cookie = array();		
		if(list($encoded_sign, $payload)=explode('.', $_COOKIE['fbsr_'.ThemexCore::getOption('user_facebook_id')], 2)){
			$sign=base64_decode(strtr($encoded_sign, '-_', '+/')); 
			if (hash_hmac('sha256', $payload, ThemexCore::getOption('user_facebook_secret'), true)==$sign){
				$cookie=json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
			}
		}
		
		return $cookie;
	}
	
	/**
	 * Gets Facebook locale
     *
     * @access public
     * @return string
     */
	public static function getLocale() {
		$locale = get_locale();
		$locales = array(
			'ca_ES', 'cs_CZ', 'cy_GB', 'da_DK', 'de_DE', 'eu_ES', 'en_PI', 'en_UD', 'ck_US', 'en_US', 'es_LA', 'es_CL', 'es_CO', 'es_ES', 'es_MX',
			'es_VE', 'fb_FI', 'fi_FI', 'fr_FR', 'gl_ES', 'hu_HU', 'it_IT', 'ja_JP', 'ko_KR', 'nb_NO', 'nn_NO', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT',
			'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'th_TH', 'tr_TR', 'ku_TR', 'zh_CN', 'zh_HK', 'zh_TW', 'fb_LT', 'af_ZA', 'sq_AL', 'hy_AM',
			'az_AZ', 'be_BY', 'bn_IN', 'bs_BA', 'bg_BG', 'hr_HR', 'nl_BE', 'en_GB', 'eo_EO', 'et_EE', 'fo_FO', 'fr_CA', 'ka_GE', 'el_GR', 'gu_IN',
			'hi_IN', 'is_IS', 'id_ID', 'ga_IE', 'jv_ID', 'kn_IN', 'kk_KZ', 'la_VA', 'lv_LV', 'li_NL', 'lt_LT', 'mk_MK', 'mg_MG', 'ms_MY', 'mt_MT',
			'mr_IN', 'mn_MN', 'ne_NP', 'pa_IN', 'rm_CH', 'sa_IN', 'sr_RS', 'so_SO', 'sw_KE', 'tl_PH', 'ta_IN', 'tt_RU', 'te_IN', 'ml_IN', 'uk_UA',
			'uz_UZ', 'vi_VN', 'xh_ZA', 'zu_ZA', 'km_KH', 'tg_TJ', 'ar_AR', 'he_IL', 'ur_PK', 'fa_IR', 'sy_SY', 'yi_DE', 'gn_PY', 'qu_PE', 'ay_BO',
			'se_NO', 'ps_AF', 'tl_ST',
		);
		
		$locale = str_replace('-', '_', $locale);
		if(strlen($locale)==2) {
			$locale = strtolower($locale).'_'.strtoupper($locale);
		}
		
		if (!in_array($locale, $locales)) {
			$locale='en_US';
		}
		
		return $locale;
	}
	
	/**
	 * Updates Facebook image
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function updateImage($ID, $user) {
		require_once(ABSPATH.'wp-admin/includes/image.php');
		
		$attachment=array('ID' => 0);
		$url='https://graph.facebook.com/'.intval($ID).'/picture?type=large';
		$image=@file_get_contents($url);
		
		if($image!==false && !empty($image)) {
			$uploads=wp_upload_dir();
			$filename=wp_unique_filename($uploads['path'], 'image-1.jpg');
			$filepath=$uploads['path'].'/'.$filename;
			
			$contents=file_put_contents($filepath, $image);
			if($contents!==false) {
			
				//upload image
				$attachment=array(
					'guid' => $uploads['url'].'/'.$filename,
					'post_mime_type' => 'image/jpg',
					'post_title' => sanitize_title(current(explode('.', $filename))),
					'post_content' => '',
					'post_status' => 'inherit',
					'post_author' => $user,
				);
				
				//add image
				$attachment['ID']=wp_insert_attachment($attachment, $attachment['guid'], 0);
				update_post_meta($attachment['ID'], '_wp_attached_file', substr($uploads['subdir'], 1).'/'.$filename);
				
				//add thumbnails
				$metadata=wp_generate_attachment_metadata($attachment['ID'], $filepath);
				wp_update_attachment_metadata($attachment['ID'], $metadata);				
			}
		}
		
		//update image
		if(isset($attachment['ID']) && $attachment['ID']!=0) {
			ThemexCore::updateUserMeta($user, 'avatar', $attachment['ID']);
		}
	}
	
	/**
	 * Checks plugin activity
     *
     * @access public
     * @return bool
     */
	public static function isActive() {
		if(ThemexCore::checkOption('user_facebook')) {
			return true;
		}
		
		return false;
	}
}