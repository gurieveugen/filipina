<?php
//Error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);

//Define constants
define('SITE_URL', home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));
define('THEME_PATH', get_template_directory().'/');
define('THEME_URI', get_template_directory_uri().'/');
define('CHILD_URI', get_stylesheet_directory_uri().'/');
define('THEMEX_PATH', THEME_PATH.'framework/');
define('THEMEX_URI', THEME_URI.'framework/');
define('AJAX_URL_ADD_PROFILE', THEME_URI.'framework/add-new-profile.php');
define('THEMEX_PREFIX', 'themex_');

//Set content width
$content_width=940;

//Load language files
load_theme_textdomain('lovestory', THEME_PATH.'languages');

//Include theme functions
include(THEMEX_PATH.'functions.php');

//Include configuration
include(THEMEX_PATH.'config.php');

//Include core class
include(THEMEX_PATH.'classes/themex.core.php');

//Create theme instance
$themex=new ThemexCore($config);
register_sidebar( array(
	'name'          => __( 'Filtered Profile Search', '_themex' ),
	'id'            => 'filter-search',
	'description'   => __( 'Appears in the footer section of the site.', '_themex' ),
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '<h3 class="title">',
	'after_title'   => '</h3>',
) );

// ==============================================================
// Require
// ==============================================================
require_once 'includes/Notifications.php';
require_once 'includes/Messages.php';
require_once 'includes/MessageHTML.php';

