<?php
/**
 * Themex Core
 *
 * Inits modules and components
 *
 * @class ThemexCore
 * @author Themex
 */

class ThemexCore {
	
	/** @var array Contains an array of modules. */
	public static $modules;

	/** @var array Contains an array of components. */
	public static $components;
	
	/** @var array Contains an array of options. */
	public static $options;
	
	/**
	 * Inits modules and components, adds edit actions
     *
     * @access public
	 * @param array $config
     * @return void
     */
	public function __construct($config) {

		//set modules
		self::$modules=$config['modules'];
		
		//set components
		self::$components=$config['components'];

		//set options
		self::$options=$config['options'];		

		//init modules
		$this->initModules();

		//init components
		$this->initComponents();

		//save options action
		add_action('wp_ajax_themex_save_options', array(__CLASS__,'saveOptions'));
		
		//reset options action
		add_action('wp_ajax_themex_reset_options', array(__CLASS__,'resetOptions'));

		//save posts action
		add_action('save_post', array(__CLASS__,'savePost'));
		
		//init session action
		add_action('init', array(__CLASS__, 'startSession'));
		
		//add editor styles
		add_filter('tiny_mce_before_init', array($this,'addEditorStyles'));
		
		//activation hook
		add_action('init', array(__CLASS__, 'activate'));		
	}
	
	/**
	 * Checks PHP version and redirects to the options page
     *
     * @access public
     * @return void
     */
	public static function activate() {
		global $pagenow;

		if ($pagenow=='themes.php' && isset($_GET['activated'])) {
			if(version_compare( PHP_VERSION, '5', '<')) {
				switch_theme('twentyten', 'twentyten');
				die();
			}
			
			flush_rewrite_rules();
			wp_redirect(admin_url('admin.php?page=theme-options'));
		}
	}
	
	/**
	 * Requires and inits modules
     *
     * @access public
     * @return void
     */
	public function initModules() {
		
		foreach(self::$modules as $module) {
		
			//require class
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $module))), 1);
			require_once(THEMEX_PATH.'classes/'.$file.'.php');
			
			//init module
			if(method_exists($module, 'init')) {
				call_user_func(array($module, 'init'));
			}
		}
	}
	
	/**
	 * Adds actions to init components
     *
     * @access public
     * @return void
     */
	public function initComponents() {
		
		//add supports
		add_action('after_setup_theme', array($this, 'supports'));
		
		//add rewrite rules
		add_action('after_setup_theme', array($this, 'rewrite_rules'));
		
		//add user roles
		add_action('init', array($this, 'user_roles'));
		
		//register custom menus
		add_action('init', array($this, 'custom_menus'));
		
		//add image sizes
		add_action('init', array($this, 'image_sizes'));
		
		//enqueue admin scripts and styles
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'admin_styles'), 99);
		
		//enqueue user scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'user_scripts'));	
		add_action('wp_enqueue_scripts', array($this, 'user_styles'), 99);	
		
		//register sidebars and widgets
		add_action('widgets_init', array($this, 'widget_areas'));
		add_action('widgets_init', array($this, 'widgets'));
		
		//register custom post types
		add_action('init', array($this, 'post_types'));
		
		//register taxonomies
		add_action('init', array($this, 'taxonomies'));	

		//add meta boxes
		add_action('admin_menu', array($this, 'meta_boxes'));		
	}
	
	/**
	 * Inits component on action
     *
     * @access public
     * @return void
     */
	public function __call($component, $atts)	{
	
		if(isset(self::$components[$component])) {
			foreach(self::$components[$component] as $item) {
			
				switch($component) {
				
					case 'supports':
						add_theme_support($item);
					break;
					
					case 'rewrite_rules':
						self::rewriteRule($item);
					break;
				
					case 'user_roles':
						add_role($item['role'], $item['name'], $item['capabilities']);
					break;
					
					case 'custom_menus':
						register_nav_menu( $item['slug'], $item['name'] );
					break;
					
					case 'image_sizes':
						add_image_size($item['name'], $item['width'], $item['height'], $item['crop']);
					break;					
					
					case 'admin_scripts':					
						self::enqueueScript($item);
					break;					
					
					case 'admin_styles':
						self::enqueueStyle($item);
					break;
					
					case 'user_scripts':					
						self::enqueueScript($item);
					break;
					
					case 'user_styles':
						self::enqueueStyle($item);
					break;
					
					case 'widgets':
						self::registerWidget($item);
					break;
					
					case 'widget_areas':
						register_sidebar($item);
					break;
					
					case 'post_types':
						register_post_type($item['id'], $item);
					break;
					
					case 'taxonomies':
						register_taxonomy($item['taxonomy'], $item['object_type'], $item['settings']);
					break;
					
					case 'meta_boxes':
						add_meta_box($item['id'], $item['title'], array('ThemexInterface','renderMetabox'), $item['page'], $item['context'], $item['priority']);
					break;			
					
				}
			}
		}
	}
	
	/**
	 * Saves theme options
     *
     * @access public
     * @return void
     */
	public static function saveOptions() {
	
		parse_str($_POST['options'], $options);
			
		//save options
		if(current_user_can('manage_options')) {
			themex_remove_strings();
			
			foreach(self::$options as $option) {		
				if(isset($option['id'])) {
				
					$option['index']=$option['id'];
					if($option['type']!='module') {
						$option['index']=THEMEX_PREFIX.$option['id'];
					}
			
					if(!isset($options[$option['index']])) {
						$options[$option['index']]='false';
					}
					
					self::updateOption($option['id'], themex_stripslashes($options[$option['index']]));

					if($option['type']=='module' && method_exists($option['id'], 'saveSettings')) {					
						call_user_func(array($option['id'], 'saveSettings'), $options[$option['index']]);
					}
				}
			}
		}
		
		_e('All changes have been saved','lovestory');
		die();		
	}
	
	/**
	 * Resets theme options
     *
     * @access public
     * @return void
     */
	public static function resetOptions() {	
	
		if(current_user_can('manage_options')) {		
			//delete options
			foreach(self::$options as $option) {
				if(isset($option['id'])) {
					self::deleteOption($option['id']);
				}
			}
			
			//delete modules
			foreach(self::$modules as $module) {
				self::deleteOption($module);
			}
			
			//delete strings
			themex_remove_strings();
		}
		
		_e('All options have been reset','lovestory');
		die();
	}
	
	/**
	 * Starts session
     *
     * @access public
     * @return void
     */
	public static function startSession() {
		if(is_user_logged_in()) {
			session_id(str_replace('_', '-', THEMEX_PREFIX.'cache'));
			session_name(str_replace('_', '-', THEMEX_PREFIX.'cache'));
			session_start();
		}		
	}
	
	
	/**
	 * Saves post meta
     *
     * @access public
     * @return void
     */
	public static function savePost($ID) {
		
		global $post;

		//check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $ID;
		}

		//verify nonce
		if (isset($_POST['themex_nonce']) && !wp_verify_nonce($_POST['themex_nonce'], $ID)) {
			return $ID;
		}
		
		//check permissions
		if (isset($_POST['post_type']) && $_POST['post_type']=='page') {
			if (!current_user_can('edit_page', $ID)) {
				return $ID;
			}
		} else if (!current_user_can('edit_post', $ID)) {
			return $ID;
		}
		
		//save post meta
		if(isset(self::$components['meta_boxes'])) {
			foreach(self::$components['meta_boxes'] as $meta_box) {
				if($meta_box['page']==get_post_type()) {
					foreach ($meta_box['options'] as $option) {
						if(isset($_POST['_'.THEMEX_PREFIX.$option['id']])) {
						
							self::updatePostMeta($ID, $option['id'], themex_stripslashes($_POST['_'.THEMEX_PREFIX.$option['id']]));
							
						}								
					}
				}
			}
		}				
	}
	
	/**
	 * Enqueues script
     *
     * @access public
	 * @param array $atts
     * @return void
     */
	public static function enqueueScript($atts) {

		if(isset($atts['uri'])) {
		
			if(isset($atts['deps'])) {
				wp_enqueue_script($atts['name'], $atts['uri'], $atts['deps']);	
			} else {
				wp_enqueue_script($atts['name'], $atts['uri']);
			}
			
		} else {
			wp_enqueue_script($atts['name']);
		}
	}
	
	/**
	 * Enqueues style
     *
     * @access public
	 * @param array $atts
     * @return void
     */
	public static function enqueueStyle($atts) {
		if(isset($atts['uri'])) {
			wp_enqueue_style($atts['name'], $atts['uri']);
		} else {
			wp_enqueue_style($atts['name']);
		}
	}
	
	/**
	 * Registers widget
     *
     * @access public
	 * @param string $name
     * @return void
     */
	public static function registerWidget($name) {
		
		if(class_exists($name)) {
			unregister_widget($name);
		} else {
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $name))), 1);
			require_once(THEMEX_PATH.'classes/widgets/'.$file.'.php');
			register_widget($name);
		}
	}
	
	/**
	 * Gets prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param mixed $default
     * @return mixed
     */
	public static function getOption($ID, $default='') {
		$option=get_option(THEMEX_PREFIX.$ID);
		if(($option===false || $option=='') && !empty($default)) {
			return $default;
		}
		
		return $option;
	}
	
	/**
	 * Updates prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param string $value
     * @return bool
     */
	public static function updateOption($ID, $value) {
		return update_option(THEMEX_PREFIX.$ID, $value);
	}
	
	/**
	 * Deletes prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function deleteOption($ID) {
		return delete_option(THEMEX_PREFIX.$ID);
	}
	
	/**
	 * Checks prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function checkOption($ID) {
		$option=self::getOption($ID);		
		if($option=='true') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getPostMeta($ID, $key, $default='') {
		$meta=get_post_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		
		if($meta=='' && (!empty($default) || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updatePostMeta($ID, $key, $value) {
		return update_post_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
	}
	
	/**
	 * Gets prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getUserMeta($ID, $key, $default='') {
		$meta=get_user_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		if(empty($meta) && (!empty($default) || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updateUserMeta($ID, $key, $value) {
		return update_user_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
	}
	
	/**
	 * Gets prefixed comment meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getCommentMeta($ID, $key, $default='') {
		$meta=get_comment_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		if(empty($meta) && (!empty($default) || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed comment meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updateCommentMeta($ID, $key, $value) {
		return update_comment_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
	}
	
	/**
	 * Rewrites URL rule
     *
     * @access public
	 * @param array $rule
     * @return void
     */
	public static function rewriteRule($rule) {
		global $wp_rewrite;
		global $wp;
		
		$wp->add_query_var($rule['name']);
		
		if(isset($rule['replace']) && $rule['replace']) {
			$wp_rewrite->$rule['rule']=$rule['rewrite'];
		} else {			
			add_rewrite_rule($rule['rule'], $rule['rewrite'], $rule['position']);
		}		
	}
	
	/**
	 * Checks active rule
     *
     * @access public
	 * @param string $rule
     * @return bool
     */
	public static function isRewriteRule($rule) {
		$rule=ThemexCore::$components['rewrite_rules'][$rule]['name'];
		
		if(get_query_var($rule)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets page URL
     *
     * @access public
	 * @param string $rule
	 * @param int $value
     * @return string
     */
	public static function getURL($rule, $value=1) {
		global $wp_rewrite;	
		
		$url=$wp_rewrite->get_page_permastruct();
		$rule=ThemexCore::$components['rewrite_rules'][$rule];
		
		$slug='';
		if(isset($rule['name'])) {
			$slug=$rule['name'];
		}
		
		if(!empty($url)) {
			$url=site_url(str_replace('%pagename%', $slug, $url));			
			if(isset($rule['dynamic']) && $rule['dynamic']) {
				$url.='/'.$value;
			}
		} else {
			$url=site_url('?'.$slug.'='.$value);
		}
		
		return $url;
	}
	
	/**
	 * Adds editor styles
     *
     * @access public
	 * @param array $options
     * @return array
     */
	public static function addEditorStyles($options) {
		$styles='';
		foreach(self::$components['editor_styles'] as $class=>$name) {
			$styles.=$name.'='.$class.';';
		}
	
		$options['theme_advanced_styles']=substr($styles, 0, -1);
		$options['theme_advanced_buttons2_add_before']='styleselect';
		
		return $options;
	}
	
	/**
	 * Uploads image
     *
     * @access public
	 * @param array $file
     * @return int
     */
	public static function uploadImage($file) {
		require_once(ABSPATH.'wp-admin/includes/image.php');
		$attachment=array('ID' => 0);
		
		if(!empty($file['name'])) {
			$uploads=wp_upload_dir();
			$filetype=wp_check_filetype($file['name'], null);
			$filename=wp_unique_filename($uploads['path'], 'image-1.'.$filetype['ext']);
			$filepath=$uploads['path'].'/'.$filename;			
			
			//validate file
			if (!in_array($filetype['ext'], array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'))) {
				ThemexInterface::$messages[]=__('Only JPG and PNG images are allowed.', 'lovestory');
			} else if(move_uploaded_file($file['tmp_name'], $filepath)) {
				
					//upload image
					$attachment=array(
						'guid' => $uploads['url'].'/'.$filename,
						'post_mime_type' => $filetype['type'],
						'post_title' => sanitize_title(current(explode('.', $filename))),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_author' => get_current_user_id(),
					);
					
					//add image
					$attachment['ID']=wp_insert_attachment($attachment, $attachment['guid'], 0);
					update_post_meta($attachment['ID'], '_wp_attached_file', substr($uploads['subdir'], 1).'/'.$filename);
					
					//add thumbnails
					$metadata=wp_generate_attachment_metadata($attachment['ID'], $filepath);
					wp_update_attachment_metadata($attachment['ID'], $metadata);
			
			} else {
				ThemexInterface::$messages[]=__('This image is too large for uploading.','lovestory');
			}
		}
		
		return $attachment;
	}
}