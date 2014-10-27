<?php
//Theme Configuration
$config = array (
	
	//Theme Modules
	'modules' => array(
		'ThemexInterface',
		'ThemexStyle',
		'ThemexForm',
		'ThemexSidebar',
		'ThemexShortcode',
		'ThemexUser',
		'ThemexFacebook',
		'ThemexWoo',
	),
	
	//Theme Components
	'components' => array(
	
		//Supports
		'supports' => array (
			'automatic-feed-links',
			'post-thumbnails',
			'woocommerce',
		),
		
		//Rewrite Rules
		'rewrite_rules' => array (
			'profile' => array(
				'name' => 'profile',
				'rule' => 'author_base',
				'rewrite' => 'profile',
				'position' => 'top',
				'replace' => true,
				'dynamic' => true,
			),
			
			'register' => array(
				'title' => __('Registration', 'lovestory'),
				'name' => 'register',
				'rule' => 'register/?',
				'rewrite' => 'index.php?register=1',
				'position' => 'top',
				'authorized' => false,				
			),
			
			'message' => array(
				'title' => __('Messages', 'lovestory'),
				'name' => 'message',
				'rule' => 'message/([^/]+)',
				'rewrite' => 'index.php?message=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
			),
			
			'chat' => array(
				'title' => __('Chat', 'lovestory'),
				'name' => 'chat',
				'rule' => 'chat/([^/]+)',
				'rewrite' => 'index.php?chat=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
				'option' => 'user_chat',
			),
			
			'messages' => array(
				'title' => __('Messages', 'lovestory'),
				'name' => 'messages',
				'rule' => 'messages/([^/]+)',
				'rewrite' => 'index.php?messages=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
			),
			
			'memberships' => array(
				'title' => __('Memberships', 'lovestory'),
				'name' => 'memberships',
				'rule' => 'memberships/([^/]+)',
				'rewrite' => 'index.php?memberships=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
			),
			
			'settings' => array(
				'title' => __('Settings', 'lovestory'),
				'name' => 'settings',
				'rule' => 'settings/([^/]+)',
				'rewrite' => 'index.php?settings=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
			),
		),
	
		//User Roles
		'user_roles' => array (
			array(
				'role' => 'inactive',
				'name' => __('Inactive', 'lovestory'),
				'capabilities' => array(),
			),
		),
		
		//Custom Menus
		'custom_menus' => array (
			array(
				'slug' => 'main_menu',
				'name' => __('Main Menu','lovestory'),
			),
			
			array(
				'slug' => 'footer_menu',
				'name' => __('Footer Menu','lovestory'),
			),
		),
		
		//Image Sizes
		'image_sizes' => array (
		
			array(
				'name' => 'preview',
				'width' => 420,
				'height' => 420,
				'crop' => true,
			),
		
			array(
				'name' => 'normal',
				'width' => 420,
				'height' => 9999,
				'crop' => false,
			),
			
			array(
				'name' => 'extended',
				'width' => 738,
				'height' => 9999,
				'crop' => false,
			),			
		),
		
		//Editor styles
		'editor_styles' => array(
			'bordered-list'=>__('Bordered List', 'lovestory'),
			'checked-list'=>__('Checked List', 'lovestory'),
		),
		
		//Admin Styles
		'admin_styles' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//interface
			array(	
				'name' => 'themex-style',
				'uri' => THEMEX_URI.'assets/css/style.css'
			),			
		),
		
		//Admin Scripts
		'admin_scripts' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//uploader
			array(	
				'name' => 'media-upload',
			),
			
			//slider
			array(	
				'name' => 'jquery-ui-slider',
			),
			
			//popup
			array(
				'name' => 'themex-popup',
				'uri' => THEMEX_URI.'assets/js/themex.popup.js',
			),
			
			//interface
			array(
				'name' => 'themex-interface',
				'uri' => THEMEX_URI.'assets/js/themex.interface.js',
			),			
		),
		
		//User Styles
		'user_styles' => array(
		
			//colorbox
			array(	
				'name' => 'colorbox',
				'uri' => THEME_URI.'js/colorbox/colorbox.css',
			),
			
			//main
			array(	
				'name' => 'themex-style',
				'uri' => CHILD_URI.'style.css',
			),
		),
		
		//User Scripts
		'user_scripts' => array(
			
			//jquery
			array(	
				'name' => 'jquery',
			),
			
			//comment
			array(	
				'name' => 'comment-reply',
			),
			
			//hover
			array(	
				'name' => 'hoverintent',
				'uri' => THEME_URI.'js/jquery.hoverIntent.min.js',
			),
			
			//colorbox
			array(	
				'name' => 'colorbox',
				'uri' => THEME_URI.'js/colorbox/jquery.colorbox.min.js',
			),
			
			//placeholder
			array(	
				'name' => 'placeholder',
				'uri' => THEME_URI.'js/jquery.placeholder.min.js',
			),
			
			//slider
			array(	
				'name' => 'themex-slider',
				'uri' => THEME_URI.'js/jquery.themexSlider.js',
			),

			//interface
			array(	
				'name' => 'themex-interface',
				'uri' => THEME_URI.'js/jquery.interface.js',
			),
		),
		
		//Widget Settings
		'widget_settings' => array (
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		),
		
		//Widget Areas
		'widget_areas' => array (
			array(
				'id' => 'profile_right',
				'name' => __('Profile', 'lovestory'),
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>',
			),
		),
		
		//Widgets
		'widgets' => array (
			'ThemexAdvert',
			'ThemexSearch',
		),
		
		//Post types
		'post_types' => array (
		
			//Memberships
			array (
				'id' => 'membership',
				'labels' => array (
					'name' => __('Memberships','lovestory'),
					'singular_name' => __( 'Membership','lovestory' ),
					'add_new' => __('Add New','lovestory'),
					'add_new_item' => __('Add New Membership','lovestory'),
					'edit_item' => __('Edit Membership','lovestory'),
					'new_item' => __('New Membership','lovestory'),
					'view_item' => __('View Membership','lovestory'),
					'search_items' => __('Search Memberships','lovestory'),
					'not_found' =>  __('No Memberships Found','lovestory'),
					'not_found_in_trash' => __('No Memberships Found in Trash','lovestory'),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'page-attributes'),
				'rewrite' => array('slug' => __('membership', 'lovestory')),
			),
			
			//Gift
			array (
				'id' => 'gift',
				'labels' => array (
					'name' => __('Gifts','lovestory'),
					'singular_name' => __( 'Gift','lovestory' ),
					'add_new' => __('Add New','lovestory'),
					'add_new_item' => __('Add New Gift','lovestory'),
					'edit_item' => __('Edit Gift','lovestory'),
					'new_item' => __('New Gift','lovestory'),
					'view_item' => __('View Gift','lovestory'),
					'search_items' => __('Search Gifts','lovestory'),
					'not_found' =>  __('No Gifts Found','lovestory'),
					'not_found_in_trash' => __('No Gifts Found in Trash','lovestory'),
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'thumbnail', 'page-attributes'),
				'rewrite' => array('slug' => __('gift', 'lovestory')),
			),
			
			//Story
			array (
				'id' => 'story',
				'labels' => array (
					'name' => __('Stories','lovestory'),
					'singular_name' => __( 'Story','lovestory' ),
					'add_new' => __('Add New','lovestory'),
					'add_new_item' => __('Add New Story','lovestory'),
					'edit_item' => __('Edit Story','lovestory'),
					'new_item' => __('New Story','lovestory'),
					'view_item' => __('View Story','lovestory'),
					'search_items' => __('Search Stories','lovestory'),
					'not_found' =>  __('No Stories Found','lovestory'),
					'not_found_in_trash' => __('No Stories Found in Trash','lovestory'),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
				'rewrite' => array('slug' => __('story', 'lovestory')),
			),
		
			//Slide
			array (
				'id' => 'slide',
				'labels' => array (
					'name' => __('Slides','lovestory'),
					'singular_name' => __( 'Slide','lovestory' ),
					'add_new' => __('Add New','lovestory'),
					'add_new_item' => __('Add New Slide','lovestory'),
					'edit_item' => __('Edit Slide','lovestory'),
					'new_item' => __('New Slide','lovestory'),
					'view_item' => __('View Slide','lovestory'),
					'search_items' => __('Search Slides','lovestory'),
					'not_found' =>  __('No Slides Found','lovestory'),
					'not_found_in_trash' => __('No Slides Found in Trash','lovestory'),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'page-attributes'),
			),			
		),
		
		//Taxonomies
		'taxonomies' => array (
		
			//Story Category
			array(
				'taxonomy' => 'story_category',
				'object_type' => array('story'),
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,			
					'labels' => array(
	                    'name' => __( 'Story Categories', 'academy'),
	                    'singular_name' => __( 'Story Category', 'academy'),
						'menu_name' => __( 'Categories', 'academy' ),
	                    'search_items' => __( 'Search Story Categories', 'academy'),
	                    'all_items' => __( 'All Story Categories', 'academy'),
	                    'parent_item' => __( 'Parent Story Category', 'academy'),
	                    'parent_item_colon' => __( 'Parent Story Category', 'academy'),
	                    'edit_item' => __( 'Edit Story Category', 'academy'),
	                    'update_item' => __( 'Update Story Category', 'academy'),
	                    'add_new_item' => __( 'Add New Story Category', 'academy'),
	                    'new_item_name' => __( 'New Story Category Name', 'academy'),
	            	),
					'rewrite' => array(
						'slug' => __('stories', 'academy'),
						'hierarchical' => true,
					),
				),
			),
		),
		
		//Meta Boxes
		'meta_boxes' => array(
		
			//Story
			array(
				'id' => 'story_metabox',
				'title' =>  __('Story Options', 'lovestory'),
				'page' => 'story',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(		
					array(	
						'name' => __('Caption', 'lovestory'),
						'id' => 'caption',
						'type' => 'text',
					),
				),			
			),
			
			//Membership
			array(
				'id' => 'membership_metabox',
				'title' =>  __('Membership Options', 'lovestory'),
				'page' => 'membership',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Product','lovestory'),
						'id' => 'product',
						'type' => 'select_post',
						'post_type' => 'product',
					),
					
					array(	
						'name' => __('Period','lovestory'),
						'id' => 'period',
						'type' => 'select',
						'options' => array(
							'7' => __('Week', 'lovestory'),
							'31' => __('Month', 'lovestory'),
							'93' => __('3 Months', 'lovestory'),
							'186' => __('6 Months', 'lovestory'),
							'365' => __('Year', 'lovestory'),
						),
					),
					
					array(	
						'name' => __('Messages','lovestory'),
						'id' => 'messages',
						'type' => 'number',
						'default' => '0',
					),
					
					array(	
						'name' => __('Photos','lovestory'),
						'id' => 'photos',
						'type' => 'number',
						'default' => '0',
					),
					
					array(	
						'name' => __('Gifts','lovestory'),
						'id' => 'gifts',
						'type' => 'number',
						'default' => '0',
					),
					
					array(	
						'name' => __('Chat','lovestory'),
						'id' => 'chat',
						'type' => 'select',
						'options' => array(
							'1' => __('Enabled', 'lovestory'),
							'0' => __('Disabled', 'lovestory'),
						),
					),
					
					array(
						'name' => __('Users', 'academy'),
						'id' => 'users',
						'type' => 'users',
					),
				),			
			),
		),
		
		//Shortcodes
		'shortcodes' => array(
		
			//Button
			array(
				'id' => 'button',
				'name' => __('Button', 'lovestory'),
				'shortcode' => '[button color="{{color}}" size="{{size}}" url="{{url}}" target="{{target}}"]{{content}}[/button]',
				'options' => array(
					array(			
						'id' => 'color',
						'name' => __('Button Color', 'lovestory'),						
						'type' => 'select',
						'options' => array(
							'primary' => __('Primary', 'lovestory'),
							'secondary' => __('Secondary', 'lovestory'),
						),
					),
				
					array(			
						'id' => 'size',
						'name' => __('Button Size', 'lovestory'),						
						'type' => 'select',
						'options' => array(
							'small' => __('Small', 'lovestory'),
							'medium' => __('Medium', 'lovestory'),
							'large' => __('Large', 'lovestory'),
						),
					),
					
					array(			
						'id' => 'url',
						'name' => __('Button Link', 'lovestory'),			
						'type' => 'text',
					),
					
					array(			
						'id' => 'target',
						'name' => __('Button Target', 'lovestory'),			
						'type' => 'select',
						'options' => array(
							'self' => __('Current Tab', 'lovestory'),
							'blank' => __('New Tab', 'lovestory'),
						),
					),
					
					array(
						'id' => 'content',
						'name' => __('Button Text', 'lovestory'),						
						'type' => 'text',
					),					
				),
			),
		
			//Columns
			array(
				'id' => 'column',
				'name' => __('Columns', 'lovestory'),
				'shortcode' => '{{clone}}',
				'clone' => array(
					'shortcode' => '[{{column}}]{{content}}[/{{column}}]',
					'options' => array(
						array(
							'id' => 'column',
							'name' => __('Width', 'lovestory'),
							'type' => 'select',
							'options' => array(
								'one_sixth' => __('One Sixth', 'lovestory'),
								'one_sixth_last' => __('One Sixth Last', 'lovestory'),
								'one_fourth' => __('One Fourth', 'lovestory'),
								'one_fourth_last' => __('One Fourth Last', 'lovestory'),
								'one_third' => __('One Third', 'lovestory'),
								'one_third_last' => __('One Third Last', 'lovestory'),
								'five_twelfths' => __('Five Twelfths', 'lovestory'),
								'five_twelfths_last' => __('Five Twelfths Last', 'lovestory'),
								'one_half' => __('One Half', 'lovestory'),
								'one_half_last' => __('One Half Last', 'lovestory'),
								'seven_twelfths' => __('Seven Twelfths', 'lovestory'),
								'seven_twelfths_last' => __('Seven Twelfths Last', 'lovestory'),
								'two_thirds' => __('Two Thirds', 'lovestory'),
								'two_thirds_last' => __('Two Thirds Last', 'lovestory'),
								'three_fourths' => __('Three Fourths', 'lovestory'),
								'three_fourths_last' => __('Three Fourths Last', 'lovestory'),
							),
						),
						
						array(					
							'id' => 'content',
							'name' => __('Content', 'lovestory'),						
							'type' => 'textarea',
						),
					),
				),
			),
			
			//Profiles
			array(
				'id' => 'profiles',
				'name' => __('Profiles', 'lovestory'),
				'shortcode' => '[profiles number="{{number}}" order="{{order}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Profiles Number', 'lovestory'),
						'value' => '3',
						'type' => 'number',
					),
					
					array(			
						'id' => 'order',
						'name' => __('Profiles Order', 'lovestory'),		
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'lovestory'),
							'name' => __('Name', 'lovestory'),
							'status' => __('Status', 'lovestory'),
						),
					),	
				),
			),
			
			//Stories
			array(
				'id' => 'story',
				'name' => __('Stories', 'lovestory'),
				'shortcode' => '[stories number="{{number}}" order="{{order}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Stories Number', 'lovestory'),
						'value' => '2',
						'type' => 'number',
					),		
					
					array(			
						'id' => 'order',
						'name' => __('Stories Order', 'lovestory'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'lovestory'),
							'random' => __('Random', 'lovestory'),
						),
					),
				),
			),
			
			//Tabs
			array(
				'id' => 'tabs',
				'name' => __('Tabs', 'lovestory'),
				'shortcode' => '[tabs]{{clone}}[/tabs]',
				'options' => array(

				),
				'clone' => array(
					'shortcode' => '[tab title="{{title}}"]{{content}}[/tab]',
					'options' => array(
						array(
							'id' => 'title',
							'name' => __('Title', 'lovestory'),
							'type' => 'text',
						),
						
						array(					
							'id' => 'content',
							'name' => __('Content', 'lovestory'),							
							'type' => 'textarea',						
						),
					),
				),
			),
			
			//Title
			array(
				'id' => 'title',
				'name' => __('Title', 'lovestory'),
				'shortcode' => '[title align="{{align}}"]{{content}}[/title]',
				'options' => array(
					array(
						'id' => 'content',
						'name' => __('Title Text', 'lovestory'),
						'type' => 'text',
					),
					
					array(			
						'id' => 'align',
						'name' => __('Title Align', 'lovestory'),			
						'type' => 'select',
						'options' => array(
							'left' => __('Left', 'lovestory'),
							'center' => __('Center', 'lovestory'),
						),
					),	
				),
			),
			
			//Toggles
			array(
				'id' => 'toggles',
				'name' => __('Toggles', 'lovestory'),
				'shortcode' => '[toggles]{{clone}}[/toggles]',
				'options' => array(

				),
				'clone' => array(
					'shortcode' => '[toggle title="{{title}}"]{{content}}[/toggle]',
					'options' => array(
						array(
							'id' => 'title',
							'name' => __('Title', 'lovestory'),
							'type' => 'text',
						),		
						
						array(
							'id' => 'content',
							'name' => __('Content', 'lovestory'),							
							'type' => 'textarea',					
						),
					),
				),
			),
		),
		
		//Custom Styles
		'custom_styles' => array(
			array(
				'elements' => 'body',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'content_font',
					),
					
					array(
						'name' => 'background-image',
						'option' => 'content_background',
					),
				),
			),
			
			array(
				'elements' => 'h1,h2,h3,h4,h5,h6,.mobile-menu,.header-search-form,.header-menu a,input[type="submit"],input[type="button"],.button,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button,.woocommerce-page #respond input#submit,.woocommerce-page #content input.button',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'heading_font',
					),
				),
			),
			
			array(
				'elements' => '.header-wrap,.header-menu div > ul > li.current-menu-item > a',
				'attributes' => array(
					array(
						'name' => 'background-image',
						'option' => 'header_background',
					),
				),
			),
			
			array(
				'elements' => 'input[type="submit"],input[type="button"],.button,.woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover',
				'attributes' => array(
					array(
						'name' => 'background',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.profile-status.online,.story-caption:after',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.story-caption .story-corner:before',
				'attributes' => array(
					array(
						'name' => 'border-top-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.story-caption .story-corner:after',
				'attributes' => array(
					array(
						'name' => 'border-bottom-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.button.secondary,.header-search-form .select-field:after,.check-listing .expanded .toggle-title:before,.pagination span,.widget-title,.header-content:after,.header-form:after,.site-footer:after,.element-option.current,.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover',
				'attributes' => array(
					array(
						'name' => 'background',
						'option' => 'secondary_color',
					),
				),
			),
			
			array(
				'elements' => 'a,.profile-option a.current,.profile-option a:hover',
				'attributes' => array(
					array(
						'name' => 'color',
						'option' => 'secondary_color',
					),
				),
			),
		),
		
		//Fonts
		'fonts' => array(
			'ABeeZee' => 'ABeeZee',
			'Abel' => 'Abel',
			'Abril Fatface' => 'Abril Fatface',
			'Aclonica' => 'Aclonica',
			'Acme' => 'Acme',
			'Actor' => 'Actor',
			'Adamina' => 'Adamina',
			'Advent Pro' => 'Advent Pro',
			'Aguafina Script' => 'Aguafina Script',
			'Aladin' => 'Aladin',
			'Aldrich' => 'Aldrich',
			'Alegreya' => 'Alegreya',
			'Alegreya SC' => 'Alegreya SC',
			'Alex Brush' => 'Alex Brush',
			'Alfa Slab One' => 'Alfa Slab One',
			'Alice' => 'Alice',
			'Alike' => 'Alike',
			'Alike Angular' => 'Alike Angular',
			'Allan' => 'Allan',
			'Allerta' => 'Allerta',
			'Allerta Stencil' => 'Allerta Stencil',
			'Allura' => 'Allura',
			'Almendra' => 'Almendra',
			'Almendra SC' => 'Almendra SC',
			'Amaranth' => 'Amaranth',
			'Amatic SC' => 'Amatic SC',
			'Amethysta' => 'Amethysta',
			'Andada' => 'Andada',
			'Andika' => 'Andika',
			'Angkor' => 'Angkor',
			'Annie Use Your Telescope' => 'Annie Use Your Telescope',
			'Anonymous Pro' => 'Anonymous Pro',
			'Antic' => 'Antic',
			'Antic Didone' => 'Antic Didone',
			'Antic Slab' => 'Antic Slab',
			'Anton' => 'Anton',
			'Arapey' => 'Arapey',
			'Arbutus' => 'Arbutus',
			'Architects Daughter' => 'Architects Daughter',
			'Arimo' => 'Arimo',
			'Arizonia' => 'Arizonia',
			'Armata' => 'Armata',
			'Artifika' => 'Artifika',
			'Arvo' => 'Arvo',
			'Asap' => 'Asap',
			'Asset' => 'Asset',
			'Astloch' => 'Astloch',
			'Asul' => 'Asul',
			'Atomic Age' => 'Atomic Age',
			'Aubrey' => 'Aubrey',
			'Audiowide' => 'Audiowide',
			'Average' => 'Average',
			'Averia Gruesa Libre' => 'Averia Gruesa Libre',
			'Averia Libre' => 'Averia Libre',
			'Averia Sans Libre' => 'Averia Sans Libre',
			'Averia Serif Libre' => 'Averia Serif Libre',
			'Bad Script' => 'Bad Script',
			'Balthazar' => 'Balthazar',
			'Bangers' => 'Bangers',
			'Basic' => 'Basic',
			'Battambang' => 'Battambang',
			'Baumans' => 'Baumans',
			'Bayon' => 'Bayon',
			'Belgrano' => 'Belgrano',
			'Belleza' => 'Belleza',
			'Bentham' => 'Bentham',
			'Berkshire Swash' => 'Berkshire Swash',
			'Bevan' => 'Bevan',
			'Bigshot One' => 'Bigshot One',
			'Bilbo' => 'Bilbo',
			'Bilbo Swash Caps' => 'Bilbo Swash Caps',
			'Bitter' => 'Bitter',
			'Black Ops One' => 'Black Ops One',
			'Bokor' => 'Bokor',
			'Bonbon' => 'Bonbon',
			'Boogaloo' => 'Boogaloo',
			'Bowlby One' => 'Bowlby One',
			'Bowlby One SC' => 'Bowlby One SC',
			'Brawler' => 'Brawler',
			'Bree Serif' => 'Bree Serif',
			'Bubblegum Sans' => 'Bubblegum Sans',
			'Buda' => 'Buda',
			'Buenard' => 'Buenard',
			'Butcherman' => 'Butcherman',
			'Butterfly Kids' => 'Butterfly Kids',
			'Cabin' => 'Cabin',
			'Cabin Condensed' => 'Cabin Condensed',
			'Cabin Sketch' => 'Cabin Sketch',
			'Caesar Dressing' => 'Caesar Dressing',
			'Cagliostro' => 'Cagliostro',
			'Calligraffitti' => 'Calligraffitti',
			'Cambo' => 'Cambo',
			'Candal' => 'Candal',
			'Cantarell' => 'Cantarell',
			'Cantata One' => 'Cantata One',
			'Cardo' => 'Cardo',
			'Carme' => 'Carme',
			'Carter One' => 'Carter One',
			'Caudex' => 'Caudex',
			'Cedarville Cursive' => 'Cedarville Cursive',
			'Ceviche One' => 'Ceviche One',
			'Changa One' => 'Changa One',
			'Chango' => 'Chango',
			'Chau Philomene One' => 'Chau Philomene One',
			'Chelsea Market' => 'Chelsea Market',
			'Chenla' => 'Chenla',
			'Cherry Cream Soda' => 'Cherry Cream Soda',
			'Chewy' => 'Chewy',
			'Chicle' => 'Chicle',
			'Chivo' => 'Chivo',
			'Coda' => 'Coda',
			'Coda Caption' => 'Coda Caption',
			'Codystar' => 'Codystar',
			'Comfortaa' => 'Comfortaa',
			'Coming Soon' => 'Coming Soon',
			'Concert One' => 'Concert One',
			'Condiment' => 'Condiment',
			'Content' => 'Content',
			'Contrail One' => 'Contrail One',
			'Convergence' => 'Convergence',
			'Cookie' => 'Cookie',
			'Copse' => 'Copse',
			'Corben' => 'Corben',
			'Cousine' => 'Cousine',
			'Coustard' => 'Coustard',
			'Covered By Your Grace' => 'Covered By Your Grace',
			'Crafty Girls' => 'Crafty Girls',
			'Creepster' => 'Creepster',
			'Crete Round' => 'Crete Round',
			'Crimson Text' => 'Crimson Text',
			'Crushed' => 'Crushed',
			'Cuprum' => 'Cuprum',
			'Cutive' => 'Cutive',
			'Damion' => 'Damion',
			'Dancing Script' => 'Dancing Script',
			'Dangrek' => 'Dangrek',
			'Dawning of a New Day' => 'Dawning of a New Day',
			'Days One' => 'Days One',
			'Delius' => 'Delius',
			'Delius Swash Caps' => 'Delius Swash Caps',
			'Delius Unicase' => 'Delius Unicase',
			'Della Respira' => 'Della Respira',
			'Devonshire' => 'Devonshire',
			'Didact Gothic' => 'Didact Gothic',
			'Diplomata' => 'Diplomata',
			'Diplomata SC' => 'Diplomata SC',
			'Doppio One' => 'Doppio One',
			'Dorsa' => 'Dorsa',
			'Dosis' => 'Dosis',
			'Dr Sugiyama' => 'Dr Sugiyama',
			'Droid Sans' => 'Droid Sans',
			'Droid Sans Mono' => 'Droid Sans Mono',
			'Droid Serif' => 'Droid Serif',
			'Duru Sans' => 'Duru Sans',
			'Dynalight' => 'Dynalight',
			'EB Garamond' => 'EB Garamond',
			'Eater' => 'Eater',
			'Economica' => 'Economica',
			'Electrolize' => 'Electrolize',
			'Emblema One' => 'Emblema One',
			'Emilys Candy' => 'Emilys Candy',
			'Engagement' => 'Engagement',
			'Enriqueta' => 'Enriqueta',
			'Erica One' => 'Erica One',
			'Esteban' => 'Esteban',
			'Euphoria Script' => 'Euphoria Script',
			'Ewert' => 'Ewert',
			'Exo' => 'Exo',
			'Expletus Sans' => 'Expletus Sans',
			'Fanwood Text' => 'Fanwood Text',
			'Fascinate' => 'Fascinate',
			'Fascinate Inline' => 'Fascinate Inline',
			'Federant' => 'Federant',
			'Federo' => 'Federo',
			'Felipa' => 'Felipa',
			'Fjord One' => 'Fjord One',
			'Flamenco' => 'Flamenco',
			'Flavors' => 'Flavors',
			'Fondamento' => 'Fondamento',
			'Fontdiner Swanky' => 'Fontdiner Swanky',
			'Forum' => 'Forum',
			'Francois One' => 'Francois One',
			'Fredericka the Great' => 'Fredericka the Great',
			'Fredoka One' => 'Fredoka One',
			'Freehand' => 'Freehand',
			'Fresca' => 'Fresca',
			'Frijole' => 'Frijole',
			'Fugaz One' => 'Fugaz One',
			'GFS Didot' => 'GFS Didot',
			'GFS Neohellenic' => 'GFS Neohellenic',
			'Galdeano' => 'Galdeano',
			'Gentium Basic' => 'Gentium Basic',
			'Gentium Book Basic' => 'Gentium Book Basic',
			'Geo' => 'Geo',
			'Geostar' => 'Geostar',
			'Geostar Fill' => 'Geostar Fill',
			'Germania One' => 'Germania One',
			'Give You Glory' => 'Give You Glory',
			'Glass Antiqua' => 'Glass Antiqua',
			'Glegoo' => 'Glegoo',
			'Gloria Hallelujah' => 'Gloria Hallelujah',
			'Goblin One' => 'Goblin One',
			'Gochi Hand' => 'Gochi Hand',
			'Gorditas' => 'Gorditas',
			'Goudy Bookletter 1911' => 'Goudy Bookletter 1911',
			'Graduate' => 'Graduate',
			'Gravitas One' => 'Gravitas One',
			'Great Vibes' => 'Great Vibes',
			'Gruppo' => 'Gruppo',
			'Gudea' => 'Gudea',
			'Habibi' => 'Habibi',
			'Hammersmith One' => 'Hammersmith One',
			'Handlee' => 'Handlee',
			'Hanuman' => 'Hanuman',
			'Happy Monkey' => 'Happy Monkey',
			'Henny Penny' => 'Henny Penny',
			'Herr Von Muellerhoff' => 'Herr Von Muellerhoff',
			'Holtwood One SC' => 'Holtwood One SC',
			'Homemade Apple' => 'Homemade Apple',
			'Homenaje' => 'Homenaje',
			'IM Fell DW Pica' => 'IM Fell DW Pica',
			'IM Fell DW Pica SC' => 'IM Fell DW Pica SC',
			'IM Fell Double Pica' => 'IM Fell Double Pica',
			'IM Fell Double Pica SC' => 'IM Fell Double Pica SC',
			'IM Fell English' => 'IM Fell English',
			'IM Fell English SC' => 'IM Fell English SC',
			'IM Fell French Canon' => 'IM Fell French Canon',
			'IM Fell French Canon SC' => 'IM Fell French Canon SC',
			'IM Fell Great Primer' => 'IM Fell Great Primer',
			'IM Fell Great Primer SC' => 'IM Fell Great Primer SC',
			'Iceberg' => 'Iceberg',
			'Iceland' => 'Iceland',
			'Imprima' => 'Imprima',
			'Inconsolata' => 'Inconsolata',
			'Inder' => 'Inder',
			'Indie Flower' => 'Indie Flower',
			'Inika' => 'Inika',
			'Irish Grover' => 'Irish Grover',
			'Istok Web' => 'Istok Web',
			'Italiana' => 'Italiana',
			'Italianno' => 'Italianno',
			'Jim Nightshade' => 'Jim Nightshade',
			'Jockey One' => 'Jockey One',
			'Jolly Lodger' => 'Jolly Lodger',
			'Josefin Sans' => 'Josefin Sans',
			'Josefin Slab' => 'Josefin Slab',
			'Judson' => 'Judson',
			'Julee' => 'Julee',
			'Junge' => 'Junge',
			'Jura' => 'Jura',
			'Just Another Hand' => 'Just Another Hand',
			'Just Me Again Down Here' => 'Just Me Again Down Here',
			'Kameron' => 'Kameron',
			'Karla' => 'Karla',
			'Kaushan Script' => 'Kaushan Script',
			'Kelly Slab' => 'Kelly Slab',
			'Kenia' => 'Kenia',
			'Khmer' => 'Khmer',
			'Knewave' => 'Knewave',
			'Kotta One' => 'Kotta One',
			'Koulen' => 'Koulen',
			'Kranky' => 'Kranky',
			'Kreon' => 'Kreon',
			'Kristi' => 'Kristi',
			'Krona One' => 'Krona One',
			'La Belle Aurore' => 'La Belle Aurore',
			'Lancelot' => 'Lancelot',
			'Lato' => 'Lato',
			'League Script' => 'League Script',
			'Leckerli One' => 'Leckerli One',
			'Ledger' => 'Ledger',
			'Lekton' => 'Lekton',
			'Lemon' => 'Lemon',
			'Lilita One' => 'Lilita One',
			'Limelight' => 'Limelight',
			'Linden Hill' => 'Linden Hill',
			'Lobster' => 'Lobster',
			'Lobster Two' => 'Lobster Two',
			'Londrina Outline' => 'Londrina Outline',
			'Londrina Shadow' => 'Londrina Shadow',
			'Londrina Sketch' => 'Londrina Sketch',
			'Londrina Solid' => 'Londrina Solid',
			'Lora' => 'Lora',
			'Love Ya Like A Sister' => 'Love Ya Like A Sister',
			'Loved by the King' => 'Loved by the King',
			'Lovers Quarrel' => 'Lovers Quarrel',
			'Luckiest Guy' => 'Luckiest Guy',
			'Lusitana' => 'Lusitana',
			'Lustria' => 'Lustria',
			'Macondo' => 'Macondo',
			'Macondo Swash Caps' => 'Macondo Swash Caps',
			'Magra' => 'Magra',
			'Maiden Orange' => 'Maiden Orange',
			'Mako' => 'Mako',
			'Marck Script' => 'Marck Script',
			'Marko One' => 'Marko One',
			'Marmelad' => 'Marmelad',
			'Marvel' => 'Marvel',
			'Mate' => 'Mate',
			'Mate SC' => 'Mate SC',
			'Maven Pro' => 'Maven Pro',
			'Meddon' => 'Meddon',
			'MedievalSharp' => 'MedievalSharp',
			'Medula One' => 'Medula One',
			'Megrim' => 'Megrim',
			'Merienda One' => 'Merienda One',
			'Merriweather' => 'Merriweather',
			'Metal' => 'Metal',
			'Metamorphous' => 'Metamorphous',
			'Metrophobic' => 'Metrophobic',
			'Michroma' => 'Michroma',
			'Miltonian' => 'Miltonian',
			'Miltonian Tattoo' => 'Miltonian Tattoo',
			'Miniver' => 'Miniver',
			'Miss Fajardose' => 'Miss Fajardose',
			'Modern Antiqua' => 'Modern Antiqua',
			'Molengo' => 'Molengo',
			'Monofett' => 'Monofett',
			'Monoton' => 'Monoton',
			'Monsieur La Doulaise' => 'Monsieur La Doulaise',
			'Montaga' => 'Montaga',
			'Montez' => 'Montez',
			'Montserrat' => 'Montserrat',
			'Moul' => 'Moul',
			'Moulpali' => 'Moulpali',
			'Mountains of Christmas' => 'Mountains of Christmas',
			'Mr Bedfort' => 'Mr Bedfort',
			'Mr Dafoe' => 'Mr Dafoe',
			'Mr De Haviland' => 'Mr De Haviland',
			'Mrs Saint Delafield' => 'Mrs Saint Delafield',
			'Mrs Sheppards' => 'Mrs Sheppards',
			'Muli' => 'Muli',
			'Mystery Quest' => 'Mystery Quest',
			'Neucha' => 'Neucha',
			'Neuton' => 'Neuton',
			'News Cycle' => 'News Cycle',
			'Niconne' => 'Niconne',
			'Nixie One' => 'Nixie One',
			'Nobile' => 'Nobile',
			'Nokora' => 'Nokora',
			'Norican' => 'Norican',
			'Nosifer' => 'Nosifer',
			'Nothing You Could Do' => 'Nothing You Could Do',
			'Noticia Text' => 'Noticia Text',
			'Nova Cut' => 'Nova Cut',
			'Nova Flat' => 'Nova Flat',
			'Nova Mono' => 'Nova Mono',
			'Nova Oval' => 'Nova Oval',
			'Nova Round' => 'Nova Round',
			'Nova Script' => 'Nova Script',
			'Nova Slim' => 'Nova Slim',
			'Nova Square' => 'Nova Square',
			'Numans' => 'Numans',
			'Nunito' => 'Nunito',
			'Odor Mean Chey' => 'Odor Mean Chey',
			'Old Standard TT' => 'Old Standard TT',
			'Oldenburg' => 'Oldenburg',
			'Oleo Script' => 'Oleo Script',
			'Open Sans' => 'Open Sans',
			'Open Sans Condensed' => 'Open Sans Condensed',
			'Orbitron' => 'Orbitron',
			'Original Surfer' => 'Original Surfer',
			'Oswald' => 'Oswald',
			'Over the Rainbow' => 'Over the Rainbow',
			'Overlock' => 'Overlock',
			'Overlock SC' => 'Overlock SC',
			'Ovo' => 'Ovo',
			'Oxygen' => 'Oxygen',
			'PT Mono' => 'PT Mono',
			'PT Sans' => 'PT Sans',
			'PT Sans Caption' => 'PT Sans Caption',
			'PT Sans Narrow' => 'PT Sans Narrow',
			'PT Serif' => 'PT Serif',
			'PT Serif Caption' => 'PT Serif Caption',
			'Pacifico' => 'Pacifico',
			'Parisienne' => 'Parisienne',
			'Passero One' => 'Passero One',
			'Passion One' => 'Passion One',
			'Patrick Hand' => 'Patrick Hand',
			'Patua One' => 'Patua One',
			'Paytone One' => 'Paytone One',
			'Permanent Marker' => 'Permanent Marker',
			'Petrona' => 'Petrona',
			'Philosopher' => 'Philosopher',
			'Piedra' => 'Piedra',
			'Pinyon Script' => 'Pinyon Script',
			'Plaster' => 'Plaster',
			'Play' => 'Play',
			'Playball' => 'Playball',
			'Playfair Display' => 'Playfair Display',
			'Podkova' => 'Podkova',
			'Poiret One' => 'Poiret One',
			'Poller One' => 'Poller One',
			'Poly' => 'Poly',
			'Pompiere' => 'Pompiere',
			'Pontano Sans' => 'Pontano Sans',
			'Port Lligat Sans' => 'Port Lligat Sans',
			'Port Lligat Slab' => 'Port Lligat Slab',
			'Prata' => 'Prata',
			'Preahvihear' => 'Preahvihear',
			'Press Start 2P' => 'Press Start 2P',
			'Princess Sofia' => 'Princess Sofia',
			'Prociono' => 'Prociono',
			'Prosto One' => 'Prosto One',
			'Puritan' => 'Puritan',
			'Quantico' => 'Quantico',
			'Quattrocento' => 'Quattrocento',
			'Quattrocento Sans' => 'Quattrocento Sans',
			'Questrial' => 'Questrial',
			'Quicksand' => 'Quicksand',
			'Qwigley' => 'Qwigley',
			'Radley' => 'Radley',
			'Raleway' => 'Raleway',
			'Rammetto One' => 'Rammetto One',
			'Rancho' => 'Rancho',
			'Rationale' => 'Rationale',
			'Redressed' => 'Redressed',
			'Reenie Beanie' => 'Reenie Beanie',
			'Revalia' => 'Revalia',
			'Ribeye' => 'Ribeye',
			'Ribeye Marrow' => 'Ribeye Marrow',
			'Righteous' => 'Righteous',
			'Roboto' => 'Roboto',
			'Roboto Condensed' => 'Roboto Condensed',
			'Rochester' => 'Rochester',
			'Rock Salt' => 'Rock Salt',
			'Rokkitt' => 'Rokkitt',
			'Ropa Sans' => 'Ropa Sans',
			'Rosario' => 'Rosario',
			'Rosarivo' => 'Rosarivo',
			'Rouge Script' => 'Rouge Script',
			'Ruda' => 'Ruda',
			'Ruge Boogie' => 'Ruge Boogie',
			'Ruluko' => 'Ruluko',
			'Ruslan Display' => 'Ruslan Display',
			'Russo One' => 'Russo One',
			'Ruthie' => 'Ruthie',
			'Sail' => 'Sail',
			'Salsa' => 'Salsa',
			'Sanchez' => 'Sanchez',
			'Sancreek' => 'Sancreek',
			'Sansita One' => 'Sansita One',
			'Sarina' => 'Sarina',
			'Satisfy' => 'Satisfy',
			'Schoolbell' => 'Schoolbell',
			'Seaweed Script' => 'Seaweed Script',
			'Sevillana' => 'Sevillana',
			'Shadows Into Light' => 'Shadows Into Light',
			'Shadows Into Light Two' => 'Shadows Into Light Two',
			'Shanti' => 'Shanti',
			'Share' => 'Share',
			'Shojumaru' => 'Shojumaru',
			'Short Stack' => 'Short Stack',
			'Siemreap' => 'Siemreap',
			'Sigmar One' => 'Sigmar One',
			'Signika' => 'Signika',
			'Signika Negative' => 'Signika Negative',
			'Simonetta' => 'Simonetta',
			'Sirin Stencil' => 'Sirin Stencil',
			'Six Caps' => 'Six Caps',
			'Slackey' => 'Slackey',
			'Smokum' => 'Smokum',
			'Smythe' => 'Smythe',
			'Sniglet' => 'Sniglet',
			'Snippet' => 'Snippet',
			'Sofia' => 'Sofia',
			'Sonsie One' => 'Sonsie One',
			'Sorts Mill Goudy' => 'Sorts Mill Goudy',
			'Special Elite' => 'Special Elite',
			'Spicy Rice' => 'Spicy Rice',
			'Spinnaker' => 'Spinnaker',
			'Spirax' => 'Spirax',
			'Squada One' => 'Squada One',
			'Stardos Stencil' => 'Stardos Stencil',
			'Stint Ultra Condensed' => 'Stint Ultra Condensed',
			'Stint Ultra Expanded' => 'Stint Ultra Expanded',
			'Stoke' => 'Stoke',
			'Sue Ellen Francisco' => 'Sue Ellen Francisco',
			'Sunshiney' => 'Sunshiney',
			'Supermercado One' => 'Supermercado One',
			'Suwannaphum' => 'Suwannaphum',
			'Swanky and Moo Moo' => 'Swanky and Moo Moo',
			'Syncopate' => 'Syncopate',
			'Tangerine' => 'Tangerine',
			'Taprom' => 'Taprom',
			'Telex' => 'Telex',
			'Tenor Sans' => 'Tenor Sans',
			'The Girl Next Door' => 'The Girl Next Door',
			'Tienne' => 'Tienne',
			'Tinos' => 'Tinos',
			'Titan One' => 'Titan One',
			'Trade Winds' => 'Trade Winds',
			'Trocchi' => 'Trocchi',
			'Trochut' => 'Trochut',
			'Trykker' => 'Trykker',
			'Tulpen One' => 'Tulpen One',
			'Ubuntu' => 'Ubuntu',
			'Ubuntu Condensed' => 'Ubuntu Condensed',
			'Ubuntu Mono' => 'Ubuntu Mono',
			'Ultra' => 'Ultra',
			'Uncial Antiqua' => 'Uncial Antiqua',
			'UnifrakturCook' => 'UnifrakturCook',
			'UnifrakturMaguntia' => 'UnifrakturMaguntia',
			'Unkempt' => 'Unkempt',
			'Unlock' => 'Unlock',
			'Unna' => 'Unna',
			'VT323' => 'VT323',
			'Varela' => 'Varela',
			'Varela Round' => 'Varela Round',
			'Vast Shadow' => 'Vast Shadow',
			'Vibur' => 'Vibur',
			'Vidaloka' => 'Vidaloka',
			'Viga' => 'Viga',
			'Voces' => 'Voces',
			'Volkhov' => 'Volkhov',
			'Vollkorn' => 'Vollkorn',
			'Voltaire' => 'Voltaire',
			'Waiting for the Sunrise' => 'Waiting for the Sunrise',
			'Wallpoet' => 'Wallpoet',
			'Walter Turncoat' => 'Walter Turncoat',
			'Wellfleet' => 'Wellfleet',
			'Wire One' => 'Wire One',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Yellowtail' => 'Yellowtail',
			'Yeseva One' => 'Yeseva One',
			'Yesteryear' => 'Yesteryear',
			'Zeyada' => 'Zeyada',
		),
		
		//Countries
		'countries' => array(
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
		),
		
		//Genders
		'genders' => array(
			'man' => __('man', 'lovestory'),
			'woman' => __('woman', 'lovestory'),
		),
	),
	
	//Theme Options
	'options' => array(
	
		//General Settings
		array(	
			'name' => __('General','lovestory'),
			'type' => 'section'
		),

			array(	
				'name' => __('Site Favicon','lovestory'),
				'description' => __('Upload an image for your site favicon','lovestory'),
				'id' => 'favicon',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Site Logo','lovestory'),
				'description' => __('Upload an image for your site logo','lovestory'),
				'id' => 'logo',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Copyright Text','lovestory'),
				'description' => __('Add copyright text to be shown in the site footer','lovestory'),
				'id' => 'copyright',
				'type' => 'textarea',
			),

			array(	
				'name' => __('Tracking Code','lovestory'),
				'description' => __('Add Google Analytics code to track your site visitors','lovestory'),
				'id' => 'tracking',
				'type' => 'textarea',
			),

		//Styling
		array(
			'name' => __('Styling','lovestory'),
			'type' => 'section',
		),	

			array(	
				'name' => __('Primary Color','lovestory'),
				'default' => '#ef6a8a',
				'id' => 'primary_color',
				'type' => 'color',
			),

			array(	
				'name' => __('Secondary Color','lovestory'),
				'default' => '#48a9d0',
				'id' => 'secondary_color',
				'type' => 'color',
			),

			array(	
				'name' => __('Header Background','lovestory'),
				'id' => 'header_background',
				'type' => 'uploader',
			),
			
			array(	
				'name' => __('Content Background','lovestory'),
				'id' => 'content_background',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Heading Font','lovestory'),					
				'id' => 'heading_font',
				'default' => 'Montserrat',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Content Font','lovestory'),
				'id' => 'content_font',
				'default' => 'Open Sans',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Custom CSS','lovestory'),
				'description' => __('Add custom CSS rules to overwrite the default styles','lovestory'),
				'id' => 'css',
				'type' => 'textarea',
			),

		//Slider
		array(	'name' => __('Header','lovestory'),
				'type' => 'section'),
				
			array(	
				'name' => __('Hide Search Form','lovestory'),
				'id' => 'search_bar',
				'type' => 'checkbox',
			),
					
			array(	
				'name' => __('Slider Pause','lovestory'),
				'default' => '0',
				'id' => 'slider_pause',
				'min_value' => 0,
				'max_value' => 15000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
			array(	
				'name' => __('Slider Speed','lovestory'),
				'default' => '1000',
				'id' => 'slider_speed',
				'min_value' => 0,
				'max_value' => 1000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
		//Registration
		array(	
			'name' => __('Registration','lovestory'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Enable Email Activation','lovestory'),
				'id' => 'user_activation',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('Enable Captcha Protection','lovestory'),
				'id' => 'user_captcha',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Enable Facebook Login','lovestory'),
				'id' => 'user_facebook',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Facebook Application ID','lovestory'),
				'id' => 'user_facebook_id',
				'type' => 'text',
			),
			
			array(	
				'name' => __('Facebook Application Secret','lovestory'),
				'id' => 'user_facebook_secret',
				'type' => 'text',
			),
			
			array(	
				'name' => __('Registration Email','lovestory'),
				'id' => 'email_registration',
				'description' => __('Use %username%, %password% and %link% keywords to show them in the email text','lovestory'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Password Reset Email','lovestory'),
				'id' => 'email_password',
				'description' => __('Use %username% and %link% keywords to show them in the email text','lovestory'),
				'type' => 'textarea',
			),
					
		//Membership
		array(	
			'name' => __('Membership','lovestory'),
			'type' => 'section',
		),
			
			array(	
				'name' => __('Membership Type','lovestory'),
				'id' => 'user_membership',
				'type' => 'select',
				'options' => array(
					'all' => __('Enabled for All', 'lovestory'),
					'woman' => __('Disabled for Women', 'lovestory'),
					'none' => __('Disabled for All', 'lovestory'),
				),
			),
			
			array(	
				'name' => __('Free Membership Duration','lovestory'),
				'id' => 'user_membership_date',
				'type' => 'number',
				'default' => '31',
			),
			
			array(	
				'name' => __('Free Membership Chat','lovestory'),
				'id' => 'user_membership_chat',
				'type' => 'select',
				'options' => array(
					'1' => __('Enabled', 'lovestory'),
					'0' => __('Disabled', 'lovestory'),
				),
			),
			
			array(	
				'name' => __('Free Messages Number','lovestory'),
				'id' => 'user_membership_messages',
				'type' => 'number',
				'default' => '100',
			),
			
			array(	
				'name' => __('Free Photos Number','lovestory'),
				'id' => 'user_membership_photos',				
				'type' => 'number',
				'default' => '10',
			),
			
			array(	
				'name' => __('Free Gifts Number','lovestory'),
				'id' => 'user_membership_gifts',
				'type' => 'number',
				'default' => '5',
			),
			
		//Notifications
		array(	
			'name' => __('Notifications','lovestory'),
			'type' => 'section',
		),
		
			array(
				'name' => __('Disable Notifications','lovestory'),
				'id' => 'user_notice',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('New Message Email','lovestory'),
				'id' => 'email_message',
				'description' => __('Use %username%, %sender% and %link% keywords to show them in the email text','lovestory'),
				'type' => 'textarea',
			),
		
			array(	
				'name' => __('New Gift Email','lovestory'),
				'id' => 'email_gift',
				'description' => __('Use %username%, %sender% and %link% keywords to show them in the email text','lovestory'),
				'type' => 'textarea',
			),
			
		//Privacy
		array(	
			'name' => __('Privacy','lovestory'),
			'type' => 'section',
		),
		
			array(
				'name' => __('Disable Chat','lovestory'),
				'id' => 'user_chat',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Disable Gifts','lovestory'),
				'id' => 'user_gifts',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Ignoring','lovestory'),
				'id' => 'user_ignore',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Favorites Visibility','lovestory'),
				'id' => 'user_settings_favorites',
				'type' => 'select',
				'options' => array(
					'1' => __('Everybody', 'lovestory'),
					'2' => __('Favorites', 'lovestory'),
					'3' => __('Nobody', 'lovestory'),
				),
			),
			
			array(	
				'name' => __('Photos Visibility','lovestory'),
				'id' => 'user_settings_photos',
				'type' => 'select',
				'options' => array(
					'1' => __('Everybody', 'lovestory'),
					'2' => __('Favorites', 'lovestory'),
					'3' => __('Nobody', 'lovestory'),
				),
			),
			
			array(	
				'name' => __('Gifts Visibility','lovestory'),
				'id' => 'user_settings_gifts',
				'type' => 'select',
				'options' => array(
					'1' => __('Everybody', 'lovestory'),
					'2' => __('Favorites', 'lovestory'),
					'3' => __('Nobody', 'lovestory'),
				),
			),
			
			array(	
				'name' => __('Message Filters','lovestory'),
				'id' => 'user_message_filters',
				'type' => 'textarea',
				'description' => __('Enter a comma separated list of words which will be cut from the private messages', 'lovestory'),
			),
		
		//Profiles
		array(	
			'name' => __('Profiles','lovestory'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Profiles Order','lovestory'),
				'id' => 'user_order',
				'type' => 'select',
				'options' => array(
					'date' => __('Date', 'lovestory'),
					'name' => __('Name', 'lovestory'),
					'status' => __('Status', 'lovestory'),					
				),
			),
		
			array(	
				'name' => __('Profiles Per Page','lovestory'),
				'id' => 'user_per_page',
				'type' => 'number',
				'default' => '9',
			),
			
			array(
				'name' => __('Hide for Guests','lovestory'),
				'id' => 'user_guest',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Hide Name','lovestory'),
				'id' => 'user_name',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Hide Gender','lovestory'),
				'id' => 'user_gender',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Hide Age','lovestory'),
				'id' => 'user_age',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('Hide Location','lovestory'),
				'id' => 'user_location',
				'type' => 'checkbox',
			),			
			
			array(
				'id' => 'ThemexForm',
				'slug' => 'profile',
				'name' => __('Profile Fields', 'lovestory'),								
				'type' => 'module',
			),
			
		//Posts
		array(
			'name' => __('Posts','lovestory'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Hide Post Date','lovestory'),
				'id' => 'post_date',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Hide Post Author','lovestory'),
				'id' => 'post_author',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('Hide Featured Image','lovestory'),
				'id' => 'post_image',
				'type' => 'checkbox',
			),
			
		//Checkout
		array(	
			'name' => __('Checkout','lovestory'),
			'type' => 'section',
		),
		
			array(
				'id' => 'ThemexWoo',
				'type' => 'module',
			),
			
		//Sidebars
		array(	
			'name' => __('Sidebars','lovestory'),
			'type' => 'section',
		),
		
			array(
				'id' => 'ThemexSidebar',
				'type' => 'module',
			),
	),
	
);