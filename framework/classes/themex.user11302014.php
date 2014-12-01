<?php
/**
 * Themex User
 *
 * Handles users data
 *
 * @class ThemexUser
 * @author Themex
 */
 
class ThemexUser {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//refresh module data
		add_action('wp', array(__CLASS__, 'refresh'), 1);
		
		//update user actions
		add_action('wp', array(__CLASS__, 'updateUser'), 99);
		add_action('wp_ajax_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('wp_ajax_nopriv_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('template_redirect', array(__CLASS__, 'activateUser'));
		
		//remove user action
		add_action('delete_user', array(__CLASS__, 'removeUser'));
		
		//add messages filter
		add_filter('comments_clauses', array( __CLASS__, 'filterMessages'));
		
		//add avatar filter
		add_filter('get_avatar', array(__CLASS__, 'getAvatar'), 10, 5);
		
		//render gifts template
		add_action('wp_footer', array(__CLASS__,'renderGifts'));
		
		//filter user statuses
		add_action('wp', array(__CLASS__, 'filterStatuses'));
		
		//membership actions
		add_action('template_redirect', array(__CLASS__, 'filterMembership'));
		add_filter('save_post',  array(__CLASS__, 'saveMembership'));
		
		//render admin profile
		add_filter('show_user_profile', array(__CLASS__,'renderAdminProfile'));
		add_filter('edit_user_profile', array(__CLASS__,'renderAdminProfile'));
		
		//update admin profile
		add_action('edit_user_profile_update', array(__CLASS__,'updateAdminProfile'));
		add_action('personal_options_update', array(__CLASS__,'updateAdminProfile'));
		
		//add admin columns
		add_filter('manage_users_columns', array(__CLASS__, 'addAdminColumns'));
		add_action('manage_users_custom_column',  array(__CLASS__, 'renderAdminColumns'), 10, 3);
		add_filter('manage_users_sortable_columns', array(__CLASS__, 'updateAdminColumns'));
		add_action('pre_user_query', array(__CLASS__, 'filterAdminColumns'));
		
		//render admin toolbar
		add_filter('show_admin_bar', array(__CLASS__,'renderToolbar'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public	 
     * @return void
     */
	public static function refresh() {
		$ID=get_current_user_id();
		self::$data['user']=self::getUser($ID, true);

		$user=0;
		if(($var=get_query_var('author')) || ($var=get_query_var('message')) || ($var=get_query_var('chat'))) {
			$user=intval($var);
		}
		
		if($user!=0) {
			self::$data['active_user']=self::getUser($user, true);
		} else {
			self::$data['active_user']=self::$data['user'];
		}
	}
	
	/**
	 * Gets user data
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getUser($ID, $extended=false) {
		$data=get_userdata($ID);
		if($data!=false) {
			$user['login']=$data->user_login;
			$user['email']=$data->user_email;
		}
	
		$user['ID']=$ID;		
		$user['status']=self::getStatus($ID);
		
		$user['profile_url']=get_author_posts_url($ID);		
		$user['message_url']=ThemexCore::getUrl('message', $ID);
		$user['chat_url']=ThemexCore::getUrl('chat', $ID);
		$user['profile']=self::getProfile($ID);
		
		if($extended) {
			$user['memberships_url']=ThemexCore::getUrl('memberships', $ID);
			$user['settings_url']=ThemexCore::getUrl('settings', $ID);
			$user['messages_url']=ThemexCore::getUrl('messages', $ID);
		
			$user['favorites']=themex_keys(ThemexCore::getUserMeta($ID, 'favorites', array()));
			$user['ignores']=themex_keys(ThemexCore::getUserMeta($ID, 'ignores', array()));
			$user['photos']=themex_keys(ThemexCore::getUserMeta($ID, 'photos', array()));
			$user['gifts']=ThemexCore::getUserMeta($ID, 'gifts', array());
			$user['membership']=self::getMembership($ID, $user['profile']['gender']);
			$user['settings']=self::getSettings($ID);
		}
		
		return $user;
	}
	
	/**
	 * Gets users data
     *
     * @access public
	 * @param array $args
     * @return array
     */
	public static function getUsers($args=array()) {
		
		global $wpdb;
		$wpdb->query('SET SQL_BIG_SELECTS=1');
	
		$args['exclude']=self::$data['user']['ID'];
		$args['orderby']='registered';
		$args['order']='DESC';
		$args['role']='subscriber';
		
		$order=ThemexCore::getOption('user_order', 'date');
		if($order=='name') {
			$args['orderby']='display_name';
			$args['order']='ASC';
		}
		
		if(!ThemexCore::checkOption('user_name')) {
			$args['meta_query']=array(
				array(
					'key' => 'first_name',
					'value' => '',
					'compare' => '!=',
				),
			);
		}
		// NOEL MODIFICATION
			/********** SEARCH BY NAME ****************/
			//echo print_r($_GET);
			if(isset($_GET['firstname']) && isset($_GET['lastname']) && $_GET['firstname']!='' && !empty($_GET['lastname'])):
					$args['meta_query']=array(
						array(
							'key' => 'first_name',
							'value' => sanitize_title($_GET['firstname']),
							'compare' => '==',
						),
						array(
								'key' => 'last_name',
								'value' => sanitize_title($_GET['lastname']),
								'compare' => '==',
							),
					);
			
			else:				
					if(isset($_GET['firstname']) && !empty($_GET['firstname'])) {
						$args['meta_query']=array(
							array(
								'key' => 'first_name',
								'value' => sanitize_title($_GET['firstname']),
								'compare' => '==',
							),
						);
					}
					if(isset($_GET['lastname']) && !empty($_GET['lastname'])) {
						$args['meta_query']=array(
							array(
								'key' => 'last_name',
								'value' => sanitize_title($_GET['lastname']),
								'compare' => '==',
							),
						);
					}
			endif;
			/********** END SEARCH BY NAME ****************/
			/********** ADVANCED SEARCH ****************/
			if(isset($_GET['description']) && $_GET['description']!='') {
				//echo print_r($_GET);
				$args['meta_query']=array(
					array(
						'key' => 'description',
						'value' => sanitize_title($_GET['description']),
						'compare' => '==',
					),
				);
			}
			if(isset($_GET['wants-to-meet']) && $_GET['wants-to-meet']!='') {
				$args['meta_query']=array(
					array(
						'key' => '_'.THEMEX_PREFIX.'wants-to-meet',
						'value' => sanitize_title($_GET['wants-to-meet']),
						'compare' => '==',
					),
				);
			}
			if(isset($_GET['height-in-cm'])&& $_GET['height-in-cm']!='') {			
				$args['meta_query']=array(
					array(
						'key' => '_'.THEMEX_PREFIX.'height-in-cm',
						'value' => sanitize_title($_GET['height-in-cm']),
						'compare' => '==',
					),
				);
			}
			if(isset($_GET['weight-in-kilos'])&& $_GET['weight-in-kilos']!='') {
				$args['meta_query']=array(
					array(
						'key' => '_'.THEMEX_PREFIX.'weight-in-kilos',
						'value' => sanitize_title($_GET['weight-in-kilos']),
						'compare' => '==',
					),
				);
			}	
			//echo print_r($args);
			/********** END ADVANCED SEARCH ****************/	
		// END NOEL MODIFICATION
		
		if(self::isUserFilter()) {
			if(isset($_GET['gender'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'seeking',
					'value' => sanitize_title($_GET['gender']),
				);
			}
			
			if(isset($_GET['seeking'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'gender',
					'value' => sanitize_title($_GET['seeking']),
				);
			}
			
			if(isset($_GET['country']) && !empty($_GET['country'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'country',
					'value' => sanitize_title($_GET['country']),
				);
			}
			
			if(isset($_GET['city']) && !empty($_GET['city'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'city',
					'value' => sanitize_text_field($_GET['city']),
				);
			}
			
			if(isset($_GET['age_from'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'age',
					'type' => 'NUMERIC',
					'value' => intval($_GET['age_from']),
					'compare' => '>=',
				);
			}
			
			if(isset($_GET['age_to'])) {
				$args['meta_query'][]=array(
					'key' => '_'.THEMEX_PREFIX.'age',
					'type' => 'NUMERIC',
					'value' => intval($_GET['age_to']),
					'compare' => '<=',
				);
			}
			
			if(isset(ThemexForm::$data['profile']) && is_array(ThemexForm::$data['profile'])) {
				foreach(ThemexForm::$data['profile'] as $field) {
					if(isset($field['search'])) {
						$name=themex_sanitize_key($field['name']);
						if(isset($_GET[$name]) && !empty($_GET[$name])) {
							if(in_array($field['type'], array('text', 'textarea'))) {
								$args['meta_query'][]=array(
									'key' => '_'.THEMEX_PREFIX.$name,
									'value' => sanitize_text_field($_GET[$name]),
									'compare' => 'LIKE',
								);
							} else {
								$args['meta_query'][]=array(
									'key' => '_'.THEMEX_PREFIX.$name,
									'value' => sanitize_text_field($_GET[$name]),
								);
							}						
						}
					}
				}
			}
		}
		
		if($order=='status' && isset($_SESSION['users']) && !empty($_SESSION['users'])) {
			$online=$_SESSION['users'];
			if(isset($online[self::$data['user']['ID']])) {
				unset($online[self::$data['user']['ID']]);
			}
			
			$online=array_keys($online);			
			if(!empty($online) && isset($args['number']) && isset($args['offset'])) {
				$number=$args['number'];
				$args['number']=null;
				
				$offset=$args['offset'];
				$args['offset']=null;
				
				$args['exclude']=array_merge(array(self::$data['user']['ID']), $online);
				$users=get_users($args);
				
				$args['include']=$online;
				$users=array_slice(array_merge(get_users($args), $users), $offset, $number);
			} else {
				$users=get_users($args);
			}
		} else {
			$users=get_users($args);
		}
		
		return $users;
	}
	
	/**
	 * Removes user
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function removeUser($ID) {
		global $wpdb;
		
		$search='s:2:"ID";s:'.strlen(strval($ID)).':"'.$ID.'";';
		$replace='s:2:"ID";s:1:"0";';
		$wpdb->query($wpdb->prepare("
			UPDATE {$wpdb->usermeta}
			SET meta_value = REPLACE(meta_value, %s, %s) 
			WHERE meta_key = '_".THEMEX_PREFIX."favorites' 
			AND meta_value LIKE %s
		", $search, $replace, '%'.$search.'%'));
		
		$wpdb->query($wpdb->prepare("
			DELETE FROM {$wpdb->comments}
			WHERE (user_id = %d
			OR comment_parent = %d)
			AND comment_type = 'message'
		", $ID, $ID));
	}
	
	/**
	 * Updates user data
     *
     * @access public	 
     * @return void
     */
	public static function updateUser() {
	
		
		$data=$_POST;		
		if(isset($_POST['data'])) {
			parse_str($_POST['data'], $data);
			$data['nonce']=$_POST['nonce'];
			check_ajax_referer(THEMEX_PREFIX.'nonce', 'nonce');
			self::refresh();
		}
				
		if(isset($data['user_action'])) {
			$redirect=false;
			
			switch(sanitize_title($data['user_action'])) {
				case 'register_user':
					self::registerUser($data);
				break;
				
				case 'login_user':
					self::loginUser($data);
				break;
				
				case 'reset_password':
					self::resetPassword($data);
				break;
			
				case 'update_profile':
					self::updateProfile(self::$data['user']['ID'], $data);
					$redirect=true;
				break;
				
				case 'update_avatar':
					self::updateAvatar(self::$data['user']['ID'], $_FILES['user_avatar']);
				break;
				
				case 'add_photo':
					self::addPhoto(self::$data['user']['ID'], $_FILES['user_photo']);
				break;
				
				case 'remove_photo':
					self::removePhoto(self::$data['user']['ID'], $data['user_photo']);
					$redirect=true;
				break;
				
				case 'feature_photo':
					self::featurePhoto(self::$data['user']['ID'], $data['user_photo']);
				break;
				
				case 'unfeature_photo':
					self::featurePhoto(self::$data['user']['ID'], $data['user_photo'], false);
				break;
				
				case 'add_favorite':
					self::addFavorite(self::$data['user']['ID'], $data['user_favorite']);
				break;
				
				case 'remove_favorite':
					self::removeFavorite(self::$data['user']['ID'], $data['user_favorite']);
					$redirect=true;
				break;
				
				case 'ignore_user':
					self::ignoreUser(self::$data['user']['ID'], $data['user_ignore']);
					$redirect=true;
				break;
				
				case 'unignore_user':
					self::unignoreUser(self::$data['user']['ID'], $data['user_ignore']);
					$redirect=true;
				break;
				
				case 'add_gift':
					self::addGift(self::$data['user']['ID'], $data['user_recipient'], $data['user_gift']);
				break;
				
				case 'update_settings':
					self::updateSettings(self::$data['user']['ID'], $data);
				break;
				
				case 'add_membership':
					self::addMembership(self::$data['user']['ID'], $data['user_membership']);
					$redirect=true;
				break;
				
				case 'add_message':
					self::addMessage(self::$data['user']['ID'], $data['user_recipient'], $data['user_message']);
				break;
				
				case 'update_status':
					self::updateStatus(self::$data['user']['ID']);
				break;
				
				case 'update_chat':
					self::updateChat(self::$data['user']['ID'], $data['user_recipient'], $data['user_message']);
				break;
			}
			
			if($redirect) {
				wp_redirect(themex_url());
				exit();
			}
		}
	}
	
	/**
	 * Registers user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function registerUser($data) {
		if(ThemexCore::checkOption('user_captcha')) {
			session_start();
			if(isset($data['captcha']) && isset($_SESSION['captcha'])) {
				$posted_code=md5($data['captcha']);
				$session_code=$_SESSION['captcha'];
				
				if($session_code!=$posted_code) {
					ThemexInterface::$messages[]=__('Verification code is incorrect', 'lovestory');
				}
			} else {
				ThemexInterface::$messages[]=__('Verification code is empty', 'lovestory');
			}
		}
		
		if(empty($data['user_email']) || empty($data['user_login']) || empty($data['user_password']) || empty($data['user_password_repeat'])) {
			ThemexInterface::$messages[]=__('Please fill in all fields', 'lovestory');
		} else {
			if(!is_email($data['user_email'])) {
				ThemexInterface::$messages[]=__('Invalid email address', 'lovestory');
			} else if(email_exists($data['user_email'])) {
				ThemexInterface::$messages[]=__('This email is already in use', 'lovestory');
			}
			
			if(!validate_username($data['user_login']) || is_email($data['user_login']) || preg_match('/\s/', $data['user_login'])) {
				ThemexInterface::$messages[]=__('Invalid character used in username', 'lovestory');
			} else	if(username_exists($data['user_login'])) {
				ThemexInterface::$messages[]=__('This username is already taken', 'lovestory');
			}
			
			if(strlen($data['user_password'])<4) {
				ThemexInterface::$messages[]=__('Password must be at least 4 characters long', 'lovestory');
			} else if(strlen($data['user_password'])>16) {
				ThemexInterface::$messages[]=__('Password must be not more than 16 characters long', 'lovestory');
			} else if(preg_match("/^([a-zA-Z0-9]{1,20})$/", $data['user_password'])==0) {
				ThemexInterface::$messages[]=__('Password contains invalid characters', 'lovestory');
			} else if($data['user_password']!=$data['user_password_repeat']) {
				ThemexInterface::$messages[]=__('Passwords do not match', 'lovestory');
			}
		}
		
		if(empty(ThemexInterface::$messages)){
			$user=wp_create_user($data['user_login'], $data['user_password'], $data['user_email']);			
			$message=ThemexCore::getOption('email_registration', 'Hi, %username%! Welcome to '.get_bloginfo('name').'.');
			wp_new_user_notification($user);
			
			$keywords=array(
				'username' => $data['user_login'],
				'password' => $data['user_password'],
			);
			
			if(ThemexCore::checkOption('user_activation')) {
				ThemexInterface::$messages[]=__('Registration complete! Check your mailbox to activate the account', 'lovestory');
				$subject=__('Account Confirmation', 'lovestory');
				$activation_key=md5(uniqid(rand(), 1));
				
				$user=new WP_User($user);
				$user->remove_role(get_option('default_role'));
				$user->add_role('inactive');
				ThemexCore::updateUserMeta($user->ID, 'activation_key', $activation_key);
				
				if(strpos($message, '%link%')===false) {
					$message.=' Click this link to activate your account %link%';
				}

				$link=ThemexCore::getURL('register');
				if(intval(substr($link, -1))==1) {
					$link.='&';
				} else {
					$link.='?';
				}
				
				$keywords['link']=$link.'activate='.urlencode($activation_key).'&user='.$user->ID;
			} else {
				wp_signon($data, false);
				$subject=__('Registration Complete', 'lovestory');
				ThemexInterface::$messages[]='<a href="'.get_author_posts_url($user).'" class="redirect"></a>';
			}
			
			themex_mail($data['user_email'], $subject, $message, $keywords);
			ThemexInterface::renderMessages(true);
		} else {
			ThemexInterface::renderMessages();
		}
					
		die();
	}
	
	/**
	 * Activates user
     *
     * @access public
     * @return void
     */
	public static function activateUser() {
		if(isset($_GET['activate']) && isset($_GET['user']) && intval($_GET['user'])!=0) {
			$users=get_users(array(
				'meta_key' => '_'.THEMEX_PREFIX.'activation_key',
				'meta_value' => sanitize_text_field($_GET['activate']),
				'include' => intval($_GET['user']),
			));
			
			if(!empty($users)) {
				$user=reset($users);
				$user=new WP_User($user->ID);
				$user->remove_role('inactive');
				$user->add_role(get_option('default_role'));
				wp_set_auth_cookie($user->ID, true);
				ThemexCore::updateUserMeta($user->ID, 'activation_key', '');				
				
				wp_redirect(get_author_posts_url($user->ID));
				exit();
			}
		}
	}
	
	/**
	 * Logins user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function loginUser($data) {
		$data['remember']=true;		
		$user=wp_signon($data, false);
		
		if(is_wp_error($user) || empty($data['user_login']) || empty($data['user_password'])){
			ThemexInterface::$messages[]=__('Invalid username or password', 'lovestory');
		} else {
			$role=array_shift($user->roles);
			if($role=='inactive') {
				ThemexInterface::$messages[]=__('This account is not activated', 'lovestory');
			}
		}
		
		if(empty(ThemexInterface::$messages)) {
			ThemexInterface::$messages[]='<a href="'.get_author_posts_url($user->ID).'" class="redirect"></a>';
		} else {
			wp_logout();
		}
			
		ThemexInterface::renderMessages();
		die();
	}
	
	/**
	 * Resets password
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function resetPassword($data) {
		global $wpdb, $wp_hasher;
		
		if(email_exists(sanitize_email($data['user_email']))) {
			$user=get_user_by('email', sanitize_email($data['user_email']));
			do_action('lostpassword_post');
			
			$login=$user->user_login;
			$email=$user->user_email;
			
			do_action('retrieve_password', $login);
			$allow=apply_filters('allow_password_reset', true, $user->ID);
			
			if(!$allow || is_wp_error($allow)) {
				ThemexInterface::$messages[]=__('Password recovery not allowed', 'lovestory');
			} else {
				$key=wp_generate_password(20, false);
				do_action('retrieve_password_key', $login, $key);
				
				if(empty($wp_hasher)){
					require_once ABSPATH.'wp-includes/class-phpass.php';
					$wp_hasher=new PasswordHash(8, true);
				}

				$hashed=$wp_hasher->HashPassword($key);
				$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $login));
				
				$link=network_site_url('wp-login.php?action=rp&key='.$key.'&login='.rawurlencode($login), 'login');				
				$message=ThemexCore::getOption('email_password', 'Hi, %username%! To reset your password, visit the following link: %link%.');
				$keywords=array(
					'username' => $user->user_login,
					'link' => $link,
				);
				
				if(themex_mail($email, __('Password Recovery', 'lovestory'), $message, $keywords)) {
					ThemexInterface::$messages[]=__('Password reset link is sent', 'lovestory');
				} else {
					ThemexInterface::$messages[]=__('Error sending email', 'lovestory');
				}
			}
		} else {
			ThemexInterface::$messages[]=__('Invalid email address', 'lovestory');		
		}
		
		ThemexInterface::renderMessages();		
		die();
	}
	
	/**
	 * Gets user profile
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getProfile($ID) {
		global $wp_embed;
		
		$profile=array();
		$meta=get_user_meta($ID);
		
		$profile['name']=themex_array_value('first_name', $meta);
		$profile['nickname']=themex_array_value('nickname', $meta);
		
		if(ThemexCore::checkOption('user_name') || empty($profile['name'])) {
			$profile['first_name']=$profile['nickname'];
			$profile['last_name']='';
			$profile['full_name']=$profile['first_name'];
		} else {
			$profile['first_name']=$profile['name'];
			$profile['last_name']=themex_array_value('last_name', $meta);
			$profile['full_name']=trim($profile['first_name'].' '.$profile['last_name']);
		}
		
		if(!ThemexCore::checkOption('user_location')) {
			$profile['country']=themex_array_value('_'.THEMEX_PREFIX.'country', $meta);
			$profile['city']=themex_array_value('_'.THEMEX_PREFIX.'city', $meta);
		}
		
		$profile['description']=themex_array_value('description', $meta);	
		$profile['description']=str_replace('+',' ',$profile['description']);
		$profile['description']=htmlentities($profile['description']);	
		$profile['gender']=themex_array_value('_'.THEMEX_PREFIX.'gender', $meta);
		$profile['seeking']=themex_array_value('_'.THEMEX_PREFIX.'seeking', $meta);
		$profile['seeking']=str_replace('+',' ',$profile['seeking']);
		$profile['age']=themex_array_value('_'.THEMEX_PREFIX.'age', $meta);
		
		if(isset(ThemexForm::$data['profile']) && is_array(ThemexForm::$data['profile'])) {
			foreach(ThemexForm::$data['profile'] as $field) {
				$name=themex_sanitize_key($field['name']);				
				if(!isset($profile[$name])) {
					$profile[$name]=themex_array_value('_'.THEMEX_PREFIX.$name, $meta);
				}
			}
		}
		
		return $profile;
	}
	
	/**
	 * Updates user profile
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateProfile($ID, $data) {
	
		$filters=self::getFilters();
		$fields=array(
			array(
				'name' => 'gender',
				'type' => 'text',
			),
			
			array(
				'name' => 'seeking',
				'type' => 'text',
			),
			
			array(
				'name' => 'age',
				'type' => 'number',
			),
			
			array(
				'name' => 'country',
				'type' => 'select',
			),
			
			array(
				'name' => 'city',
				'type' => 'name',
			),
		);
		
		if(isset(ThemexForm::$data['profile'])) {
			$fields=array_merge($fields, ThemexForm::$data['profile']);
		}

		foreach($fields as $field) {
			$name=themex_sanitize_key($field['name']);
			if(isset($data[$name])) {
				if($field['type']=='number') {
					$data[$name]=intval($data[$name]);
				} else if($field['type']=='name') {
					$data[$name]=ucwords(strtolower(trim($data[$name])));
				} else {
					$data[$name]=sanitize_text_field($data[$name]);
				}
				
				ThemexCore::updateUserMeta($ID, $name, $data[$name]);
			}
		}
		
		//first name
		if(isset($data['first_name'])) {	
			update_user_meta($ID, 'first_name', sanitize_text_field($data['first_name']));
		}
		
		//last name
		if(isset($data['last_name'])) {	
			update_user_meta($ID, 'last_name', sanitize_text_field($data['last_name']));
		}
		
		//description
		if(isset($data['description'])) {
			$data['description']=trim(preg_replace($filters, '', $data['description']));
			$data['description']=str_replace('+',' ',$data['description']);
			$data['description']=wp_kses($data['description'], array(
				'strong' => array(),
				'em' => array(),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'p' => array(),
				'br' => array(),
			));			

			update_user_meta($ID, 'description', $data['description']);
		}
		
		//date
		if(isset($data['update'])) {
			ThemexCore::updateUserMeta($ID, 'updated', date('Y-m-d'));
		}
	}
	
	/**
	 * Filters default avatar
     *
     * @access public
	 * @param string $avatar
	 * @param int $user_id
	 * @param int $size
	 * @param string $default
	 * @param string $alt
     * @return string
     */
	public static function getAvatar($avatar, $user, $size, $default, $alt) {
		if(isset($user->user_id)) {
			$user=$user->user_id;
		}
		
		$avatar_id=ThemexCore::getUserMeta($user, 'avatar');
		$default=wp_get_attachment_image_src( $avatar_id, 'preview');
		$image=THEME_URI.'images/avatar.png';
		
		if(isset($default[0])) {
			$image=themex_resize($default[0], $size, $size, true, true);
		}
		
		return '<img src="'.$image.'" class="avatar" width="'.$size.'" alt="'.$alt.'" />';
	}
	
	/**
	 * Updates user avatar
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function updateAvatar($ID, $file) {
		wp_delete_attachment(ThemexCore::getUserMeta($ID, 'avatar'));
		$attachment=ThemexCore::uploadImage($file);
		
		if(isset($attachment['ID']) && $attachment['ID']!=0) {
			ThemexCore::updateUserMeta($ID, 'avatar', $attachment['ID']);
		}
	}
	
	/**
	 * Gets user excerpt
     *
     * @access public
	 * @param int $ID
     * @return string
     */
	public static function getExcerpt($profile) {
		$out='';
		if(!empty($profile['age'])) {
			$out.=$profile['age'].' '.__('years old', 'lovestory');
		}
		
		if(!empty($profile['gender'])) {
			$out.=' '.themex_array_value($profile['gender'], ThemexCore::$components['genders']);
		}
		
		if(!empty($profile['country']) || !empty($profile['city'])) {
			$out.=' '.__('from', 'lovestory').' ';
			
			if(!empty($profile['city'])) {
				$out.=$profile['city'].', ';
			}
			
			if(!empty($profile['country'])) {
				$out.=themex_array_value($profile['country'], ThemexCore::$components['countries']);
			}
		}
		
		return $out;
	}
	
	/**
	 * Checks featured photo
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function isFeaturedPhoto($ID) {
		if(isset(self::$data['user']['photos'][$ID]) && self::$data['user']['photos'][$ID]['featured']) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Features photo
     *
     * @access public
	 * @param int $ID
	 * @param bool $feature
     * @return void
     */
	public static function featurePhoto($ID, $attachment, $feature=true) {
		if(isset(self::$data['user']['photos'][$attachment])) {
			self::$data['user']['photos'][$attachment]['featured']=$feature;
			ThemexCore::updateUserMeta($ID, 'photos', self::$data['user']['photos']);
		}		
	}
	
	/**
	 * Sorts user photos
     *
     * @access public
	 * @param array $photos
     * @return array
     */
	public static function sortPhotos($photos) {
		$photos=array_reverse($photos);
		usort($photos, array(__CLASS__, 'comparePhotos'));
		return $photos;
	}
	
	/**
	 * Compares user photos
     *
     * @access public
	 * @param array $a
	 * @param array $b
     * @return int
     */
	public static function comparePhotos($a, $b) {
		$out=0;
		
		if($a['featured']<$b['featured']) {
			$out=1;
		} else {
			$out=-1;
		}
		
		return $out;
	}
	
	/**
	 * Adds user photo
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function addPhoto($ID, $file) {
		$attachment=ThemexCore::uploadImage($file);
		
		if(self::$data['user']['membership']['photos']>0 && $attachment['ID']!=0) {
			array_unshift(self::$data['user']['photos'], array(
				'ID' => $attachment['ID'],
				'date' => current_time('timestamp'),
				'featured' => '0',
			));			
			
			self::updateMembership($ID, 'photos', -1);
			ThemexCore::updateUserMeta($ID, 'photos', self::$data['user']['photos']);
			wp_redirect(themex_url());
		}
	}
	
	/**
	 * Removes user photo
     *
     * @access public
	 * @param int $ID
	 * @param int $attachment
     * @return void
     */
	public static function removePhoto($ID, $attachment) {
		if(isset(self::$data['user']['photos'][$attachment])) {
			unset(self::$data['user']['photos'][$attachment]);
			wp_delete_attachment($attachment);
			ThemexCore::updateUserMeta($ID, 'photos', self::$data['user']['photos']);
		}
	}
	
	/**
	 * Checks user favorite
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function isFavorite($ID) {
		if(isset(self::$data['user']['favorites'][$ID])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Adds user favorite
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function addFavorite($ID, $user) {		
		if(!isset(self::$data['user']['favorites'][$user])) {
			array_unshift(self::$data['user']['favorites'], array(
				'ID' => $user,
				'date' => current_time('timestamp'),
			));
			Notifications::addFavorites( $user );
			ThemexCore::updateUserMeta($ID, 'favorites', self::$data['user']['favorites']);
		}		
	}
	
	/**
	 * Removes user favorite
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function removeFavorite($ID, $user) {
		if(isset(self::$data['user']['favorites'][$user])) {
			unset(self::$data['user']['favorites'][$user]);
			ThemexCore::updateUserMeta($ID, 'favorites', self::$data['user']['favorites']);
		}	
	}
	
	/**
	 * Renders gifts template
     *
     * @access public
     * @return void
     */
	public static function renderGifts() {
		if(!ThemexCore::checkOption('user_gifts')) {
			get_template_part('template', 'gifts');
		}		
	}
	
	/**
	 * Adds user gift
     *
     * @access public
	 * @param int $ID
	 * @param int $user
	 * @param int $gift
     * @return void
     */
	public static function addGift($ID, $user, $gift) {
		if(self::$data['user']['membership']['gifts']>0) {
			$gifts=ThemexCore::getUserMeta($user, 'gifts', array());	
			array_unshift($gifts, array(
				'ID' => $gift,
				'sender' => $ID,
				'date' => current_time('timestamp'),
			));			
			
			ThemexCore::updateUserMeta($user, 'gifts', $gifts);
			self::updateMembership($ID, 'gifts', -1);
			
			if(!ThemexCore::checkOption('user_notice')) {
				$recipient=self::getUser($user, true);
				if(in_array($recipient['settings']['notices'], array(1, 3))) {
					$message=ThemexCore::getOption('email_gift', 'Hi, %username%! You have received a new gift from %sender% %link%.');
					$keywords=array(
						'username' => $recipient['login'],
						'sender' => self::$data['user']['profile']['full_name'],
						'link' => $recipient['profile_url'],
					);
				
					themex_mail($recipient['email'], __('New Gift', 'lovestory'), $message, $keywords);
				}
			}
			
			ThemexInterface::$messages[]=__('Selected gift has been successfully sent', 'lovestory');
			ThemexInterface::renderMessages(true);
		} else {
			ThemexInterface::$messages[]=__('You have exceeded the number of gifts', 'lovestory');
			ThemexInterface::renderMessages();
		}
		
		die();
	}
	
	/**
	 * Ignores user
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function ignoreUser($ID, $user) {
		if(!isset(self::$data['user']['ignores'][$user])) {
			array_unshift(self::$data['user']['ignores'], array(
				'ID' => $user,
				'date' => current_time('timestamp'),
			));

			ThemexCore::updateUserMeta($ID, 'ignores', self::$data['user']['ignores']);
		}
	}
	
	/**
	 * Unignores user
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function unignoreUser($ID, $user) {
		if(isset(self::$data['user']['ignores'][$user])) {
			unset(self::$data['user']['ignores'][$user]);
			ThemexCore::updateUserMeta($ID, 'ignores', self::$data['user']['ignores']);
		}
	}
	
	/**
	 * Checks ignored user
     *
     * @access public
	 * @param int $ID
	 * @param bool $current
     * @return bool
     */
	public static function isIgnored($ID, $current=true) {
		$ignores=self::$data['user']['ignores'];
		if(!$current) {
			$ignores=self::$data['active_user']['ignores'];
		}
		
		if(isset($ignores[$ID])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets user membership
     *
     * @access public
	 * @param int $ID
	 * @param string $gender
     * @return array
     */
	public static function getMembership($ID, $gender) {
		global $wpdb;
		$filter=ThemexCore::getOption('user_membership');
		
		if($filter=='none' || ($filter=='woman' && $gender=='woman')) {
			$membership=array(
				'ID' => -1,
				'name' => __('Free', 'lovestory'),
				'date' => current_time('timestamp')+60,
				'messages' => 1,
				'photos' => 1,
				'gifts' => 1,
				'chat' => 1,
			);
		} else {
			$membership=ThemexCore::getUserMeta($ID, 'membership');
			$subscriptions=get_user_meta($ID, $wpdb->prefix.'woocommerce_subscriptions', true);
			
			if(is_array($subscriptions) && !empty($subscriptions) && !empty($membership)) {
				$product=intval(ThemexCore::getPostMeta($membership['ID'], 'product'));				
				if($product!=0) {
					foreach($subscriptions as $key=>$subscription) {
						if($subscription['product_id']==$product) {
							$time=strtotime($subscription['expiry_date']);

							if($time!==false) {
								$membership['date']=$time;
							} else {
								$time=wp_next_scheduled('scheduled_subscription_payment', array(
									'user_id' => $ID,
									'subscription_key' => $key,
								));
								
								if($time!=false) {
									$membership['date']=$time+84600;
								}
							}
							
							break;
						}
					}
				}
			}
			
			if(is_array($membership) && !isset($membership['chat'])) {
				$membership['chat']=1;
			}
		}
				
		if(empty($membership) || (isset($membership['date']) && $membership['date']<current_time('timestamp'))) {
			$date=intval(ThemexCore::getOption('user_membership_date', 31))*86400+current_time('timestamp');
			$messages=intval(ThemexCore::getOption('user_membership_messages', 100));
			$photos=intval(ThemexCore::getOption('user_membership_photos', 10));
			$gifts=intval(ThemexCore::getOption('user_membership_gifts', 5));
			$chat=intval(ThemexCore::getOption('user_membership_chat'));
			
			if(isset($membership['date'])) {
				$messages=0;
				$photos=0;
				$gifts=0;
				$chat=0;
			}
			
			$membership=array(
				'ID' => 0,
				'name' => __('Free', 'lovestory'),
				'date' => $date,
				'messages' => $messages,
				'photos' => $photos,
				'gifts' => $gifts,
				'chat' => $chat,
			);
			
			ThemexCore::updateUserMeta($ID, 'membership', $membership);
		}
		
		return $membership;
	}
	
	/**
	 * Adds user membership
     *
     * @access public
	 * @param int $ID
	 * @param int $membership
	 * @param bool $checkout
     * @return void
     */
	public static function addMembership($ID, $membership, $checkout=true) {
	
		if($checkout && ThemexWoo::isActive()) {
			$product=intval(ThemexCore::getPostMeta($membership, 'product'));			
			if($product!=0) {
				ThemexWoo::addProduct($product);
			}
		} else {
			$title=get_the_title($membership);
			$date=intval(ThemexCore::getPostMeta($membership, 'period'))*86400+current_time('timestamp');
			$messages=intval(ThemexCore::getPostMeta($membership, 'messages'));
			$photos=intval(ThemexCore::getPostMeta($membership, 'photos'));
			$gifts=intval(ThemexCore::getPostMeta($membership, 'gifts'));
			$chat=intval(ThemexCore::getPostMeta($membership, 'chat'));
			
			$membership=array(
				'ID' => $membership,
				'name' => $title,
				'date' => $date,
				'messages' => $messages,
				'photos' => $photos,
				'gifts' => $gifts,
				'chat' => $chat,
			);
			
			ThemexCore::updateUserMeta($ID, 'membership', $membership);			
		}
	}
	
	/**
	 * Removes user membership
     *
     * @access public
	 * @param int $ID
	 * @param int $membership
     * @return void
     */
	public static function removeMembership($ID) {
	
		$date=intval(ThemexCore::getOption('user_membership_date', 31))*86400+current_time('timestamp');
		$membership=array(
			'ID' => 0,
			'name' => __('Free', 'lovestory'),
			'date' => $date,
			'messages' => 0,
			'photos' => 0,
			'gifts' => 0,
			'chat' => 0,
		);
		
		ThemexCore::updateUserMeta($ID, 'membership', $membership);
	}
	
	/**
	 * Updates user membership
     *
     * @access public
	 * @param int $ID
	 * @param string $name
	 * @param int $value
     * @return array
     */
	public static function updateMembership($ID, $name, $value) {
		self::$data['user']['membership'][$name]=intval(self::$data['user']['membership'][$name])+$value;		
		ThemexCore::updateUserMeta($ID, 'membership', self::$data['user']['membership']);
	}
	
	/**
	 * Filters user membership
     *
     * @access public
     * @return void
     */
	public static function filterMembership() {
		if(get_query_var('chat') && !self::$data['user']['membership']['chat']) {
			wp_redirect(SITE_URL);
			exit();
		}
		
		if(!is_user_logged_in() && get_query_var('author') && ThemexCore::checkOption('user_guest')) {
			wp_redirect(ThemexCore::getURL('register'));
			exit();
		}
	}
	
	/**
	 * Saves membership users
     *
	 * @param int $ID
     * @access public
     * @return void
     */
	public static function saveMembership($ID) {
		if(current_user_can('edit_posts') && isset($_POST['post_type']) && $_POST['post_type']=='membership') {
			if(isset($_POST['add_user']) && isset($_POST['add_user_id'])) {
				self::addMembership(intval($_POST['add_user_id']), $ID, false);
			} else if(isset($_POST['remove_user']) && isset($_POST['remove_user_id'])) {
				self::removeMembership(intval($_POST['remove_user_id']));
			}
		}
	}
	
	/**
	 * Gets user settings
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getSettings($ID) {
		$settings=ThemexCore::getUserMeta($ID, 'settings', array(
			'favorites' => ThemexCore::getOption('user_settings_favorites', 1),
			'photos' => ThemexCore::getOption('user_settings_photos', 1),
			'gifts' => ThemexCore::getOption('user_settings_gifts', 1),
			'notices' => 1,
		));
		
		return $settings;
	}
	
	/**
	 * Updates user settings
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateSettings($ID, $data) {
	
		//favorites
		if(isset($data['user_favorites'])) {
			self::$data['user']['settings']['favorites']=intval($data['user_favorites']);
		}
		
		//photos
		if(isset($data['user_photos'])) {
			self::$data['user']['settings']['photos']=intval($data['user_photos']);
		}
		
		//gifts
		if(isset($data['user_gifts'])) {
			self::$data['user']['settings']['gifts']=intval($data['user_gifts']);
		}
		
		//notices
		if(isset($data['user_notices'])) {
			self::$data['user']['settings']['notices']=intval($data['user_notices']);
		}
		
		//password
		if(isset($data['user_password']) && !empty($data['user_password'])) {
			if(strlen($data['user_password'])<4) {
				ThemexInterface::$messages[]=__('Password must be at least 4 characters long', 'lovestory');
			} else if(strlen($data['user_password'])>16) {
				ThemexInterface::$messages[]=__('Password must be not more than 16 characters long', 'lovestory');
			} else if(preg_match("/^([a-zA-Z0-9]{1,20})$/", $data['user_password'])==0) {
				ThemexInterface::$messages[]=__('Password contains invalid characters', 'lovestory');
			} else if($data['user_password']!=$data['user_password_repeat']) {
				ThemexInterface::$messages[]=__('Passwords do not match', 'lovestory');
			} else {
				wp_set_password($data['user_password'], $ID);
				wp_set_auth_cookie($ID, true);
				ThemexInterface::$messages[]=__('Password has been successfully changed', 'lovestory');
				$_POST['success']=true;
			}
		}

		ThemexCore::updateUserMeta($ID, 'settings', self::$data['user']['settings']);
		if(empty(ThemexInterface::$messages)) {
			wp_redirect(themex_url());
			exit();
		}
	}
	
	/**
	 * Checks user access
     *
     * @access public
	 * @param int $ID
	 * @param string $type
     * @return bool
     */
	public static function checkAccess($ID, $user, $type) {
		$settings=self::getSettings($user);
		$access=false;
		
		if(isset($settings[$type])) {
			$level=intval($settings[$type]);
			if($ID==$user || $level==1 || ($level==2 && self::isFavorite($user))) {
				$access=true;
			}
		}
		
		return $access;
	}
	
	/**
	 * Gets user recipients
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getRecipients($ID) {
		global $wpdb;
		$recipients=array();
		
		$messages=$wpdb->get_results($wpdb->prepare("
			SELECT c.user_id as user_id, c.comment_karma as comment_karma, c.comment_parent as comment_parent FROM {$wpdb->comments} c			
			WHERE (c.user_id = %d
			OR c.comment_parent = %d)
			AND c.comment_type = 'message'
			ORDER BY c.comment_date DESC
		", $ID, $ID));
		
		foreach($messages as $message) {
			if(!isset($recipients[$message->user_id])) {
				$recipients[$message->user_id]=array(
					'ID' => $message->user_id,
					'unread' => 0,
				);
			}
			
			if(!isset($recipients[$message->comment_parent])) {
				$recipients[$message->comment_parent]=array(
					'ID' => $message->comment_parent,
					'unread' => 0,
				);
			}
			
			if($message->comment_karma==0) {
				$recipients[$message->user_id]['unread']++;			
			}
		}
		//echo print_r($recipients);
		unset($recipients[$ID]);
		$recipients=array_reverse($recipients);
		usort($recipients, array(__CLASS__, 'compareRecipients'));

		return $recipients;
	}
	
	/**
	 * Compares user recipients
     *
     * @access public
	 * @param array $a
	 * @param array $b
     * @return int
     */
	public static function compareRecipients($a, $b) {
		$out=0;
		
		if($a['unread']<$b['unread']) {
			$out=1;
		} else {
			$out=-1;
		}
		
		return $out;
	}
	
	/**
	 * Gets user messages
     *
     * @access public
	 * @param int $ID
	 * @param int $user
	 * @param int $page
     * @return array
     */
	public static function getMessages($ID, $user, $page=null) {
		global $wpdb;
		
		$offset=0;
		$number=999999;
		if(!is_null($page)) {
			$offset=(intval($page)-1)*5;
			$number=5;
		}
	
		$messages=$wpdb->get_results($wpdb->prepare("
			SELECT c.comment_ID as comment_ID, c.user_id as user_id, 
			c.comment_date as comment_date, c.comment_content as comment_content FROM {$wpdb->comments} c
			WHERE ((c.comment_parent = %d
			AND c.user_id = %d)
			OR (c.user_id = %d
			AND c.comment_parent = %d))
			AND c.comment_type = 'message'
			ORDER BY c.comment_date DESC
			LIMIT %d, %d
		", $ID, $user, $ID, $user, $offset, $number));
		
		if(!empty($messages)) {
			$wpdb->query($wpdb->prepare("
			UPDATE {$wpdb->comments} c
			SET c.comment_karma = '1'
			WHERE c.comment_parent = %d
			AND c.user_id = %d
			AND c.comment_type = 'message'
		", $ID, $user));
		}
		
		$messages=array_reverse($messages);
		return $messages;
	}
	
	/**
	 * Filters user messages
     *
     * @access public
	 * @param mixed $query
     * @return mixed
     */
	public static function filterMessages($query) {
		if(isset($query['where'])) {
			$query['where'].=" AND comment_type <> 'message'";
		}

        return $query;
	}
	
	/**
	 * Counts unread messages
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function countMessages($ID) {
		global $wpdb;
	
		$messages=$wpdb->get_results( $wpdb->prepare("
			SELECT c.user_id as user_id FROM {$wpdb->comments} c			
			WHERE c.comment_parent = %d
			AND c.comment_type = 'message'
			AND c.comment_karma = '0'
			ORDER BY c.comment_date DESC
		", $ID, $ID));
		
		$number=count($messages);
		if($number==0) {
			$number='';
		}
		
		return $number;
	}
	
	/**
	 * Adds user message
     *
     * @access public
	 * @param int $ID
	 * @param int $user
	 * @param string $message
     * @return void
     */
	public static function addMessage($ID, $user, $message) {
		if(self::$data['user']['membership']['messages']>0) {
			$filters=self::getFilters();
			$message=trim(preg_replace($filters, '', $message));
			
			if(!empty($message)) {
				if(!self::isIgnored($ID, false)) {
					$sender=self::$data['user'];
					$message=wp_insert_comment(array(
						'comment_post_ID' => 0,
						'comment_karma' => 0,
						'comment_type' => 'message',
						'comment_parent' => $user,
						'user_id' => $sender['ID'],
						'comment_author' => $sender['login'],
						'comment_author_email' => $sender['email'],
						'comment_content' => wp_kses($message, array(
							'strong' => array(),
							'em' => array(),
							'a' => array(
								'href' => array(),
								'title' => array(),
								'target' => array(),
							),
							'p' => array(),
							'br' => array(),
						)),
					));
					
					if(!ThemexCore::checkOption('user_notice')) {
						$recipient=self::$data['active_user'];
						if(in_array($recipient['settings']['notices'], array(1, 2))) {
							$message=ThemexCore::getOption('email_message', 'Hi, %username%! You have received a new message from %sender% %link%.');
							$keywords=array(
								'username' => $recipient['login'],
								'sender' => self::$data['user']['profile']['full_name'],
								'link' => self::$data['user']['message_url'],
							);
						
							themex_mail($recipient['email'], __('New Message', 'lovestory'), $message, $keywords);
						}
					}
					
					self::updateMembership($ID, 'messages', -1);
				} else {
					ThemexInterface::$messages[]=__("You've been added to the ignore list", 'lovestory');
				}
			} else {
				ThemexInterface::$messages[]=__('Message field must not be empty', 'lovestory');
			}			
		} else {
			ThemexInterface::$messages[]=__('You have exceeded the number of messages', 'lovestory');
		}
		
		if(empty(ThemexInterface::$messages)) {
			wp_redirect(themex_url());
			exit();
		}
	}
	
	/**
	 * Gets message filters
     *
     * @access public
     * @return array
     */
	public static function getFilters() {
		$filters=explode(',', ThemexCore::getOption('user_message_filters'));
		$filters=array_merge($filters, array(self::$data['user']['email']));
		
		foreach($filters as &$filter) {
			$filter='/\b'.preg_quote(trim($filter), '/').'\b/';
		}
		
		return $filters;
	}
	
	/**
	 * Updates user chat
     *
     * @access public
	 * @param int $ID
	 * @param int $user
	 * @param string $message
     * @return void
     */
	public static function updateChat($ID, $user, $message) {
		$out='';
		
		if(!isset($_SESSION['filters'])) {
			$_SESSION['filters']=self::getFilters();
		}
		
		if(!empty($_SESSION['messages'][$ID][$user]) && !self::isIgnored($user)) {
			foreach($_SESSION['messages'][$ID][$user] as $key=>$chat) {
				$GLOBALS['chat']=$chat;
				$GLOBALS['chat']['author']=$user;
			
				ob_start();
				get_template_part('content', 'chat');
				$out.=ob_get_contents();
				ob_end_clean();
				
				unset($_SESSION['messages'][$ID][$user][$key]);
			}
		}
		
		if(!empty($message)) {
			$message=trim(preg_replace($_SESSION['filters'], '', sanitize_text_field($message)));
			if(!empty($message)) {
				$chat=array(
					'time' => current_time('timestamp'),
					'content' => $message,
				);
				
				if(isset($_SESSION['messages'][$user][$ID]) && count($_SESSION['messages'][$user][$ID])>2) {
					array_shift($_SESSION['messages'][$user][$ID]);
				}
				
				$_SESSION['messages'][$user][$ID][]=$chat;
				$GLOBALS['chat']=$chat;
				$GLOBALS['chat']['author']=$ID;
				
				ob_start();			
				get_template_part('content', 'chat');
				$out.=ob_get_contents();
				ob_end_clean();	
			}
		}
		
		echo $out;
		die();
	}
		
	/**
	 * Gets user status
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getStatus($ID) {
		$status['name']=__('Offline', 'lovestory');
		$status['value']='offline';
	
		if(isset($_SESSION['users'][$ID]) || in_array($ID, array(313596,310983,314670,310110,316774,313683,316721,314259,314151,314219,307451,318218,306521,312776,312505,312966,316190,314248,310252,313555,309834,309423,308408,308905,313153,312081,307441,307462,312189,308858,314411,311614,314288,309435,316846,309839,312879,311063,311477,316527,308048,311638,309316,307617,313031,315348,311528,307420,309281,313684,307283,308804,313680,307873,313366,312047,313066,315827,309261,316425,309896,308372,315356,315980,310520,308357,318986,309267,316016,309468,316753,315891,315954,311708,315890,312095,312799,312437,308930,313061,309300,314591,314280,317069,306492,313333,315938,308413,316211,314898,314678,311626,411161,411162,411163,411164,411165,411166,411167,411168,411169,411170,411171,411172,411173,411174,411175,411176,411177,411178,411179,411180,411181,411183,411184,411185,411186,411187,307269,309363,311528,312217,312345,313031,313069,314280,315980,411188,411189,411190,411191,411192,411193,411194,411195,411196))) {
			$status['name']=__('Online', 'lovestory');
			$status['value']='online';
		}
		
		return $status;
	}
	
	/**
	 * Updates user status
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function updateStatus($ID) {
		if(isset($_SESSION['messages'][$ID]) && !empty($_SESSION['messages'][$ID])) {
			foreach($_SESSION['messages'][$ID] as $user=>$messages) {
				if(!empty($messages) && !self::isIgnored($user)) {
					$latest=count($messages)-1;	
					if(!isset($messages[$latest]['read'])) {
						$_SESSION['messages'][$ID][$user][$latest]['read']=true;
						
						$name=get_user_meta($user, 'first_name', true);
						if(ThemexCore::checkOption('user_name') || empty($name)) {
							$name=get_user_meta($user, 'nickname', true);
						} else {
							$name=trim($name.' '.get_user_meta($user, 'last_name', true));
						}
				
						$out=__('New chat message from', 'lovestory');
						$out.=' <a href="'.ThemexCore::getURL('chat', $user).'">'.$name.'</a>';
						ThemexInterface::$messages[]=$out;
					}
				}				
			}
			
			ThemexInterface::renderMessages(true);
		}
	
		if(!isset($_SESSION['users'][$ID])) {
			$_SESSION['users'][$ID]=time()+120;
		}
		
		die();
	}
	
	/**
	 * Filters user statuses
     *
     * @access public
     * @return void
     */
	public static function filterStatuses() {
		$limit=time();		
		if(isset($_SESSION['users']) && (!isset($_SESSION['time']) || $_SESSION['time']<$limit)) {	
			$_SESSION['time']=$limit+150;
			foreach($_SESSION['users'] as $ID=>$time) {
				if($time<$limit) {
					unset($_SESSION['users'][$ID]);
				}
			}
		}
	}
	
	/**
	 * Renders admin profile
     *
     * @access public
	 * @param mixed $user
     * @return void
     */
	public static function renderAdminProfile($user) {
		$profile=self::getProfile($user->ID);
		$out='<table class="form-table themex-profile"><tbody>';
		
		if(current_user_can('edit_users')) {
		
			//profile image
			$out.='<tr><th><label for="avatar">'.__('Photo', 'academy').'</label></th>';
			$out.='<td><div class="themex-image-uploader">';
			$out.=get_avatar($user->ID);
			$out.=ThemexInterface::renderOption(array(
				'id' => 'avatar',
				'type' => 'uploader',
				'value' => '',
				'wrap' => false,				
			));
			$out.='</div></td></tr>';
		}
		
		//default fields
		if(!ThemexCore::checkOption('user_gender')) {
			$out.='<tr><th><label for="gender">'.__('Gender', 'lovestory').'</label></th><td>';
			$out.=ThemexInterface::renderOption(array(
				'id' => 'gender',
				'type' => 'select',
				'value' => !empty($profile['gender'])?$profile['gender']:'man',
				'options' => ThemexCore::$components['genders'],
				'wrap' => false,
			));
			$out.='</td></tr>';
			
			$out.='<tr><th><label for="seeking">'.__('Seeking', 'lovestory').'</label></th><td>';
			$out.=ThemexInterface::renderOption(array(
				'id' => 'seeking',
				'type' => 'select',
				'value' => !empty($profile['seeking'])?$profile['seeking']:'woman',
				'options' => ThemexCore::$components['genders'],
				'wrap' => false,
			));
			$out.='</td></tr>';
		}
		
		if(!ThemexCore::checkOption('user_age')) {
			$out.='<tr><th><label for="age">'.__('Age', 'lovestory').'</label></th><td>';
			$out.=ThemexInterface::renderOption(array(
				'id' => 'age',
				'type' => 'select_age',
				'value' => $profile['age'],
				'wrap' => false,
			));
			$out.='</td></tr>';
		}
		
		if(!ThemexCore::checkOption('user_location')) {
			$out.='<tr><th><label for="country">'.__('Country', 'lovestory').'</label></th><td>';
			$out.=ThemexInterface::renderOption(array(
				'id' => 'country',
				'type' => 'select',
				'options' => array_merge(array('0' => '&ndash;'), ThemexCore::$components['countries']),
				'value' => !empty($profile['country'])?$profile['country']:null,
				'wrap' => false,
			));
			$out.='</td></tr>';
			
			$out.='<tr><th><label for="city">'.__('City', 'lovestory').'</label></th><td>';
			$out.='<input type="text" name="city" size="50" value="'.$profile['city'].'" />';
			$out.='</td></tr>';
		}
		
		//custom fields
		ob_start();
		ThemexForm::renderData('profile', array(
			'edit' =>  true,
			'before_title' => '<tr><th><label>',
			'after_title' => '</th></label>',
			'before_content' => '<td>',
			'after_content' => '</td></tr>',
		), $profile);
		$out.=ob_get_contents();
		ob_end_clean();
		
		//profile text
		$out.='<tr><th><label for="description">'.__('Profile Text', 'lovestory').'</label></th><td>';
		ob_start();
		ThemexInterface::renderEditor('description', wpautop($profile['description']));
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</td></tr>';
		
		$out.='</tbody></table>';		
		echo $out;
	}
	
	/**
	 * Updates admin profile
     *
	 * @param mixed $user
     * @access public	 
     * @return void
     */
	public static function updateAdminProfile($user) {
		global $wpdb;
	
		//custom fields
		self::updateProfile($user, $_POST);
		
		if(current_user_can('edit_users')) {
			
			//profile image
			if(isset($_POST['avatar']) && !empty($_POST['avatar'])) {
				$url=esc_url($_POST['avatar']);
				$avatar=$wpdb->get_var("SELECT ID FROM ".$wpdb->posts." WHERE guid = '".$url."'");
				
				if(!empty($avatar)) {
					ThemexCore::updateUserMeta($user, 'avatar', $avatar);
				}
			}
		}
	}
	
	/**
	 * Adds admin columns
     *
	 * @param array $columns
     * @access public	 
     * @return array
     */
	function addAdminColumns($columns) {
		$columns['updated']=__('Updated', 'lovestory');
		return $columns;
	}
	
	/**
	 * Renders admin columns
     *
	 * @param string $value
	 * @param string $column
	 * @param int $user
     * @access public	 
     * @return string
     */
	function renderAdminColumns($value, $column, $user) {
		if($column=='updated') {
			$data=get_userdata($user);
			$registered=date('Y-m-d', strtotime($data->user_registered));
			$updated=ThemexCore::getUserMeta($user, 'updated', $registered);
			
			return $updated;
		}
		
		return $value;
	}
	
	/**
	 * Updates admin columns
     *
	 * @param array $columns
     * @access public	 
     * @return array
     */
	function updateAdminColumns($columns) {
		$custom=array(
			'updated' => 'updated',
		);
		
		return wp_parse_args($custom, $columns);
	}
	
	/**
	 * Filters admin columns
     *
	 * @param mixed $query
     * @access public	 
     * @return array
     */
	function filterAdminColumns($query) {
		global $wpdb, $pagenow;

		if($pagenow=='users.php') {
			$vars=$query->query_vars;
			if($vars['orderby']=='updated') {
				$order='DESC';
				if($vars['order']=='DESC') {
					$order='ASC';
				}
				
				$query->query_from.=" LEFT JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='_".THEMEX_PREFIX."updated')"; 
				$query->query_orderby=" ORDER BY m1.meta_value ".$order;
			}
		}
		else
		{
			$order='ASC';
			$order_value='';
			/* noel mod */
			if(isset($_GET['themex_user_order']) && !empty($_GET['themex_user_order'])):
				switch($_GET['themex_user_order']){
					case 'name':
						$order_value='first_name';
						break;
					case 'status':
						$order_value='status';
						break;
					default:
						$order_value='_'.THEMEX_PREFIX.'updated';
						break;
				}		
			$query->query_from.=" LEFT JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='".$order_value."')"; 
			$query->query_orderby=" ORDER BY m1.meta_value ".$order;		
			endif;
		}
	}
	
	/**
	 * Renders user toolbar
     *
     * @access public
     * @return bool
     */
	public static function renderToolbar() {
		if(current_user_can('edit_posts') && get_user_option('show_admin_bar_front', get_current_user_id())!='false') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks profile page
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function isProfile() {
		if(is_user_logged_in() && self::$data['active_user']['ID']==self::$data['user']['ID']) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks user filter
     *
     * @access public
     * @return bool
     */
	public static function isUserFilter() {
		if(isset($_GET['s']) && empty($_GET['s'])) {
			return true;
		}
		
		return false;
 	}
}