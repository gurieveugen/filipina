<?php
/**
 * Themex Interface
 *
 * Renders pages and options
 *
 * @class ThemexInterface
 * @author Themex
 */
 
class ThemexInterface {

	/** @var array Contains an array of messages. */
	public static $messages;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//add options page
		add_action('admin_menu', array(__CLASS__,'addPage'));
		
		//render thickbox				
		add_action('admin_init', array(__CLASS__,'renderTB'));
		
		//render embed
		add_filter('embed_oembed_html', array(__CLASS__,'renderEmbed'), 99, 4 );
		
		//render footer				
		add_action('wp_footer', array(__CLASS__,'renderFooter'));
		
		//render comment form
		add_filter('comment_form_defaults', array(__CLASS__,'renderCommentForm'));
		
		//render page template
		add_filter('template_include', array(__CLASS__,'renderTemplate'), 100, 1);
		
		//render template titles
		add_filter('wp_title', array(__CLASS__,'renderTemplateTitle'), 10, 2);
	}
	
	/**
	 * Renders thickbox page
     *
     * @access public
     * @return void
     */
	public static function renderTB() {
		if(isset($_GET['themex_uploader'])) {
			add_filter('media_upload_tabs', array(__CLASS__,'filterTBTabs'));
			add_filter('attachment_fields_to_edit', array(__CLASS__,'renderTBUploader'), 10, 2);
		}
	}	
	
	/**
	 * Filters thickbox tabs
     *
     * @access public
	 * @param array $tabs
     * @return array
     */
	public static function filterTBTabs($tabs) {
		unset($tabs['type_url'], $tabs['gallery']);
    	return $tabs;
	}	
	
	/**
	 * Filters thickbox uploader
     *
     * @access public
	 * @param array $fields
	 * @param object $post
     * @return array
     */
	public static function renderTBUploader($fields, $post) {
		
		//save fields
		$filename=basename($post->guid);
		$attachment_id=$post->ID;
		$attachment['post_title']='';
		$attachment['url']=$fields['image_url']['value'];
		$attachment['post_excerpt']='';
		
		//unset fields
		unset($fields);
		
		//send button
		$send_button="<input type='submit' class='button' name='send[$attachment_id]' value='".__( 'Insert This Item' , 'lovestory' )."' />&nbsp;&nbsp;&nbsp;";
		$send_button.="<input type='radio' checked='checked' value='full' id='image-size-full-$attachment_id' name='attachments[$attachment_id][image-size]' style='display:none;' />";
		$send_button.="<input type='hidden' value='' name='attachments[$attachment_id][post_title]' id='attachments[$attachment_id][post_title]' />";
		$send_button.="<input type='hidden' value='$attachment[url]' class='themex_image_url' name='attachments[$attachment_id][url]' id='attachments[$attachment_id][url]' />";
		$send_button.="<input type='hidden' value='' name='attachments[$attachment_id][post_excerpt]' id='attachments[$attachment_id][post_excerpt]' />";
		$fields['buttons']=array( 'tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send_button</td></tr>\n" );
		
		return $fields;
	}
	
	/**
	 * Renders embedded video
     *
     * @access public
	 * @param string $html
     * @return string
     */
	public static function renderEmbed($html) {
		return '<div class="embedded-video">'.$html.'</div>';
	}
	
	/**
	 * Parse embedded video
     *
     * @access public
	 * @param string $content
     * @return string
     */
	public static function parseEmbed($content) {
		return preg_replace_callback('|^\s*(https?://[^\s"]+)\s*$|im', array(__CLASS__, 'filterEmbed'), $content);
	}
	
	/**
	 * Filter embedded video
     *
     * @access public
	 * @param string $content
     * @return string
     */
	public static function filterEmbed($url) {
		$html=wp_oembed_get($url[1]);	
		if($html) {
			$html=apply_filters('embed_oembed_html', $html);
		} else {
			$html=$url[1];
		}
		
		return $html;
	}
	
	/**
	 * Adds options page to menu
     *
     * @access public
     * @return void
     */
	public static function addPage() {
		add_theme_page(__('Theme Options','lovestory'), __('Theme Options','lovestory'), 'administrator', 'theme-options', array(__CLASS__,'renderPage'));
	}
	
	/**
	 * Renders options page
     *
     * @access public
     * @return void
     */
	public static function renderPage() {	
		include(THEMEX_PATH.'templates/index.php');		
	}
	
	/**
	 * Renders page template
     *
     * @access public
	 * @param string $template
     * @return string
     */
	public static function renderTemplate($template) {
		foreach(ThemexCore::$components['rewrite_rules'] as $key=>$rule) {
			if(get_query_var($rule['name'])) {				
				if(isset($rule['authorized'])) {
					if($rule['authorized'] && !is_user_logged_in()) {
						wp_redirect(SITE_URL);
						exit();
					} else if(!$rule['authorized'] && is_user_logged_in()) {
						wp_redirect(get_author_posts_url(get_current_user_id()));
						exit();
					}
				}
				
				$disabled=false;
				if(isset($rule['option']) && ThemexCore::checkOption($rule['option'])) {
					$disabled=true;
				}
			
				if($disabled) {
					wp_redirect(SITE_URL);
					exit();
				} else {
					$template=THEME_PATH.'template-'.$key.'.php';					
				}
			}
		}
		
		return $template;
	}
	
	/**
	 * Renders options page menu
     *
     * @access public
     * @return void
     */
	public static function renderMenu() {
		
		$out='<ul>';	
		
		foreach(ThemexCore::$options as $option) {
			if($option['type']=='section') {
				$out.='<li><a href="#'.themex_sanitize_key($option['name']).'">'.$option['name'].'</a></li>';
			}			
		}		
		
		$out.='</ul>';
		
		echo $out;		
	}
	
	/**
	 * Renders page sections
     *
     * @access public
     * @return void
     */
	public static function renderSections() {
	
		$first=true;
		$out='';
	
		foreach(ThemexCore::$options as $option) {
			
			if($option['type']=='section') {
				if($first) {
					$first=false;
				} else {
					$out.='</div>';
				}
				
				$out.='<div class="themex-section" id="'.themex_sanitize_key($option['name']).'"><h2>'.$option['name'].'</h2>';
			} else {
				$option['id']=THEMEX_PREFIX.$option['id'];
				$out.=self::renderOption($option);
			}
		}

		$out.='</div>';
		
		echo $out;		
	}
	
	/**
	 * Renders metabox
     *
     * @access public
     * @return void
     */
	public static function renderMetabox() {
	
		global $post;
		
		//create nonce
		$out='<input type="hidden" name="themex_nonce" value="'.wp_create_nonce($post->ID).'" />'; 
		$out.='<table class="themex-metabox">';
		
		//render metabox
		foreach(ThemexCore::$components['meta_boxes'] as $meta_box) {
			if($meta_box['page']==$post->post_type) {
				foreach($meta_box['options'] as $option) {					
					
					//get option value
					$option['value']=ThemexCore::getPostMeta($post->ID, $option['id']);
					
					//add option prefix
					$option['id']='_'.THEMEX_PREFIX.$option['id'];
				
					//render option
					$out.='<tr><th><h4 class="themex-meta-title">'.$option['name'].'</h4></th><td>'.self::renderOption($option).'</td></tr>';
				}
			}
		}
		
		$out.='</table>';
		
		echo $out;
	}
	
	/**
	 * Renders option
     *
     * @access public
	 * @param array $option
     * @return string
     */
	public static function renderOption($option) {
	
		global $post, $wpdb;
		$out='';
	
		//option wrapper
		if(!isset($option['wrap']) || $option['wrap']) {
			$out.='<div class="themex-option themex-'.str_replace('_', '-', $option['type']).'">';
			
			if(isset($option['name']) && $option['type']!='checkbox') {
				$out.='<h3 class="themex-option-title">'.$option['name'].'</h3>';
			}
		}
		
		//option before
		if(isset($option['before'])) {
			$out.=$option['before'];
		}
		
		//option description
		if(isset($option['description'])) {
			$out.='<div class="themex-tooltip"><div class="themex-tooltip-icon"></div><div class="themex-tooltip-text">'.$option['description'].'</div></div>';
		}
		
		//option attributes
		$attributes='';
		if(isset($option['attributes'])) {
			foreach($option['attributes'] as $name=>$value) {
				$attributes.=$name.'="'.$value.'" ';
			}
		}	
		
		//option value		
		if(!isset($option['value'])) {
			$option['value']='';
			if(isset($option['id'])) {
				$option['value']=themex_stripslashes(get_option($option['id']));
				if(($option['value']===false || $option['value']=='') && isset($option['default'])) {
					$option['value']=themex_stripslashes($option['default']);
				}
			} else if(isset($option['default'])) {
				$option['value']=themex_stripslashes($option['default']);
			}
		}
		
		switch($option['type']) {
		
			//text field
			case 'text':
				$out.='<input type="text" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$option['value'].'" '.$attributes.' />';
			break;
			
			//number field
			case 'number':
				$out.='<input type="number" id="'.$option['id'].'" name="'.$option['id'].'" value="'.abs(intval($option['value'])).'" '.$attributes.' />';
			break;
			
			//hidden field
			case 'hidden':
				$out.='<input type="hidden" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$option['value'].'" '.$attributes.' />';
			break;
			
			//message field
			case 'textarea':
				$out.='<textarea id="'.$option['id'].'" name="'.$option['id'].'" '.$attributes.'>'.$option['value'].'</textarea>';
			break;
			
			//checkbox
			case 'checkbox':
				$checked='';
				if($option['value']=='true') {
					$checked='checked="checked"';
				}
				
				$out.='<input type="checkbox" id="'.$option['id'].'" name="'.$option['id'].'" value="true" '.$checked.' '.$attributes.' />';
				
				if(isset($option['name'])) {
					$out.='<label for="'.$option['id'].'">'.$option['name'].'</label>';
				}				
			break;
			
			//colorpicker
			case 'color':
				$out.='<input name="'.$option['id'].'" id="'.$option['id'].'" type="text" value="'.$option['value'].'" class="themex-colorpicker" />';
			break;
			
			//uploader
			case 'uploader':
				$out.='<input name="'.$option['id'].'" id="'.$option['id'].'" type="text" value="'.$option['value'].'" '.$attributes.' />';
				$out.='<a class="button themex-upload-button">'.__('Browse','lovestory').'</a>';
			break;
			
			//images selector
			case 'select_image':
				foreach($option['options'] as $name=>$src) {
					$out.='<image src="'.$src.'" ';
					
					if($name==$option['value']) {
						$out.='class="current"';
					}
					
					$out.=' data-value="'.$name.'" />';
				}
				
				$out.='<input type="hidden" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['value'].'" '.$attributes.' />';
			break;
			
			//custom dropdown
			case 'select':
				$out.='<select id="'.$option['id'].'" name="'.$option['id'].'" '.$attributes.'>';
				if(isset($option['options'])) {
					foreach($option['options'] as $name=>$title) {
						$selected='';
						if((string)$name===$option['value'] || (is_array($option['value']) && in_array($name, $option['value']))) {
							$selected='selected="selected"';
						}
						
						$out.='<option value="'.$name.'" '.$selected.'>'.$title.'</option>';
					}
				}
				
				$out.='</select>';
			break;
			
			//fonts dropdown
			case 'select_font':
				$options=ThemexCore::$components['fonts'];
				asort($options);
				
				$out.=self::renderOption(array(
					'id' => $option['id'],
					'type' => 'select',
					'wrap' => false,
					'default' => $option['value'],
					'options' => $options,
				));
			break;
			
			//cities dropdown
			case 'select_city':
				$fields=$wpdb->get_results($wpdb->prepare("
					SELECT DISTINCT user_id, meta_key, meta_value FROM {$wpdb->usermeta}
					WHERE (meta_key = '_".THEMEX_PREFIX."country'
					OR meta_key = '_".THEMEX_PREFIX."city')
					AND meta_value <> '0'
					ORDER BY meta_key ASC, meta_value ASC
				", ''));

				$list=array();
				foreach($fields as $field) {
					if(!empty($field->meta_value)) {
						if($field->meta_key=='_'.THEMEX_PREFIX.'city') {
							$list[$field->user_id]['city']=$field->meta_value;
						} else {
							$list[$field->user_id]['country']=$field->meta_value;
						}
					}					
				}
				
				$out.='<select name="'.$option['id'].'" '.$attributes.'><option value="0">&ndash;</option>';
				foreach($list as $item) {
					if(isset($item['city']) && isset($item['country'])) {
						if(!isset($tree[$item['country']][$item['city']])) {
							$selected='';
							if($item['city']==$option['value']) {
								$selected='selected="selected"';
							}
							
							$out.='<option value="'.$item['city'].'" class="'.$item['country'].'" '.$selected.'>'.$item['city'].'</option>';
						}
						
						$tree[$item['country']][$item['city']]=$item['city'];
					}					
				}		
				$out.='</select>';
			break;
			
			//ages dropdown
			case 'select_age':
				$options=array(
					'18' => '18',
					'19' => '19',
					'20' => '20',
					'21' => '21',
					'22' => '22',
					'23' => '23',
					'24' => '24',
					'25' => '25',
					'26' => '26',
					'27' => '27',
					'28' => '28',
					'29' => '29',
					'30' => '30',
					'31' => '31',
					'32' => '32',
					'33' => '33',
					'34' => '34',
					'35' => '35',
					'36' => '36',
					'37' => '37',
					'38' => '38',
					'39' => '39',
					'40' => '40',
					'41' => '41',
					'42' => '42',
					'43' => '43',
					'44' => '44',
					'45' => '45',
					'46' => '46',
					'47' => '47',
					'48' => '48',
					'49' => '49',
					'50' => '50',
					'51' => '51',
					'52' => '52',
					'53' => '53',
					'54' => '54',
					'55' => '55',
					'56' => '56',
					'57' => '57',
					'58' => '58',
					'59' => '59',
					'60' => '60',
					'61' => '61',
					'62' => '62',
					'63' => '63',
					'64' => '64',
					'65' => '65',
					'66' => '66',
					'67' => '67',
					'68' => '68',
					'69' => '69',
					'70' => '70',
					'71' => '71',
					'72' => '72',
					'73' => '73',
					'74' => '74',
					'75' => '75',
					'76' => '76',
					'77' => '77',
					'78' => '78',
					'79' => '79',
					'80' => '80',
					'81' => '81',
					'82' => '82',
					'83' => '83',
					'84' => '84',
					'85' => '85',
					'86' => '86',
					'87' => '87',
					'88' => '88',
					'89' => '89',
					'90' => '90',
					'91' => '91',
					'92' => '92',
					'93' => '93',
					'94' => '94',
					'95' => '95',
					'96' => '96',
					'97' => '97',
					'98' => '98',
					'99' => '99',
				);
				
				$out.=self::renderOption(array(
					'id' => $option['id'],
					'type' => 'select',
					'wrap' => false,
					'default' => $option['value'],
					'options' => $options,
				));
			break;
			
			//pages dropdown
			case 'select_page':
				$args=array(
					'selected' => $option['value'],
					'echo' => 0,
					'name' => $option['id'],
					'id' => $option['id'],
				);
				
				$out.=wp_dropdown_pages($args);
			break;
			
			//posts dropdown
			case 'select_post':				
				$atts=array(
					'numberposts'=> -1,
					'post_type' => $option['post_type'], 
					'orderby' => 'title',
					'order' => 'ASC',
					'post__not_in' => array($post->ID),
				);
				
				if(!current_user_can('manage_options')) {
					$atts['author']=get_current_user_id();
				}
				
				$items=get_posts($atts);
				
				$out.='<select id="'.$option['id'].'" name="'.$option['id'].'" '.$attributes.'>';
				$out.='<option value="0">'.__('None', 'lovestory').'</option>';
				
				foreach($items as $item) {
					$selected='';
					if($item->ID==$option['value']) {
						$selected='selected="selected"';
					}
					
					$out.='<option value="'.$item->ID.'" '.$selected.'>'.$item->post_title.'</option>';
				}
				
				$out.='</select>';
			break;
			
			//categories dropdown
			case 'select_category':			
				$args=array(
					'hide_empty'=>0,
					'echo'=> 0,
					'selected' => $option['value'],
					'hierarchical' => 0, 
					'name' => $option['id'],
					'id' => $option['id'],
					'depth' => 0,
					'tab_index' => 0,
					'taxonomy' => $option['taxonomy'],
					'hide_if_empty' => false,
				);
				
				$out.= wp_dropdown_categories($args);
			break;
			
			//range slider
			case 'slider':
				$out.='<div class="themex-slider-controls"></div><div class="themex-slider-value"></div>';
				$out.='<input type="hidden" class="slider-max" value="'.$option['max_value'].'" />';
				$out.='<input type="hidden" class="slider-min" value="'.$option['min_value'].'" />';
				$out.='<input type="hidden" class="slider-unit" value="'.$option['unit'].'" />';
				$out.='<input type="hidden" class="slider-value" name="'.$option['id'].'" id="'.$option['id'].'"  value="'.$option['value'].'" />';
			break;
			
			//users manager
			case 'users':
				$search='s:2:"ID";i:'.$post->ID.';';
				$users=$wpdb->get_results($wpdb->prepare("
					SELECT user_id FROM {$wpdb->usermeta} 
					WHERE meta_value LIKE %s
				", '%'.$search.'%'));
				$users=wp_list_pluck($users, 'user_id');
				
				$out.='<div class="themex-row clearfix">';
				$out.=wp_dropdown_users(array(
					'echo' => false,
					'exclude' => $users,
					'name' => 'add_user_id',
					'id' => 'add_user_id',
				));
				$out.='<input type="submit" name="add_user" class="button" value="'.__('Add', 'academy').'" /></div>';

				
				if(!empty($users)) {
					$out.='<div class="themex-row clearfix">';
					$out.=wp_dropdown_users(array(
						'echo' => false,
						'include' => $users,
						'name' => 'remove_user_id',
						'id' => 'remove_user_id',
					));
					$out.='<input type="submit" name="remove_user" class="button" value="'.__('Remove', 'academy').'" /></div>';
				}
			break;
			
			//module settings
			case 'module':
				$out.='<div class="'.substr(strtolower(implode('-', preg_split('/(?=[A-Z])/', str_replace(THEMEX_PREFIX, '', $option['id'])))), 1).'">';
				if(isset($option['slug'])) {
					$out.=call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'renderSettings'), $option['slug']);
				} else {
					$out.=call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'renderSettings'));
				}				
				$out.='</div>';
			break;
		}
		
		//option after
		if(isset($option['after'])) {
			$out.=$option['after'];
		}
		
		//wrap option
		if(!isset($option['wrap']) || $option['wrap']) {
			$out.='</div>';
		}
		
		return $out;
	}
	
	/**
	 * Renders select menu
     *
     * @access public
	 * @param string $slug
     * @return void
     */
	public static function renderSelectMenu($slug) {
		$locations=get_nav_menu_locations();
		
		if(isset($locations[$slug])) {
			$menu=wp_get_nav_menu_object($locations[$slug]);
			
			if(isset($menu->term_id)) {
				$menu_items=wp_get_nav_menu_items($menu->term_id);
				
				$out= '<select>';
				foreach ( (array) $menu_items as $key => $menu_item ) {
					$out.='<option value="'.$menu_item->url.'">'.$menu_item->title.'</option>';
				}
				$out.='</select>';
				
				echo $out;
			} else {
				wp_dropdown_pages();
			}
		} else {
			wp_dropdown_pages();
		}
	}
	
	/**
	 * Renders comment
     *
     * @access public
	 * @param mixed $comment
	 * @param array $args
	 * @param int $depth
     * @return void
     */
	public static function renderComment($comment, $args, $depth) {
		$GLOBALS['comment']=$comment;
		$GLOBALS['depth']=$depth;
		get_template_part('content', 'comment');
	}
	
	/**
	 * Renders comment form
     *
     * @access public
	 * @param array $fields
     * @return void
     */
	public static function renderCommentForm($fields) {
		$fields['logged_in_as']='<div class="formatted-form">';
		$fields['comment_notes_before']='<div class="formatted-form">';
		$fields['comment_notes_after']='</div>';
		$fields['fields']['author']='<div class="sixcol column"><div class="field-wrap"><input id="author" name="author" type="text" value="" size="30" placeholder="'.__('Name', 'lovestory').'" /></div></div>';
		$fields['fields']['email']='<div class="sixcol column last"><div class="field-wrap"><input id="email" name="email" type="text" value="" size="30" placeholder="'.__('Email', 'lovestory').'" /></div></div>';
		$fields['fields']['url']='';
		$fields['comment_field']='<div class="field-wrap"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.__('Comment', 'lovestory').'"></textarea></div>';
		
		$fields['title_reply']='';
		$fields['title_reply_to']='';
		$fields['label_submit']=__('Add Comment', 'lovestory');
		
		return $fields;
	}
	
	/**
	 * Renders editor
     *
     * @access public
	 * @param string $ID
	 * @param string $content
     * @return void
     */
	public static function renderEditor($ID, $content='') {
		$settings=array(
			'media_buttons'=>false,
			'teeny'=>true,
			'quicktags' => false,
			'textarea_rows' => 10,
			'tinymce' => array(
				'theme_advanced_buttons1' => 'bold,italic,undo,redo',
				'theme_advanced_buttons2' => '',
				'theme_advanced_buttons3' => '',				
				'toolbar1' => 'bold,italic,undo,redo',
				'toolbar2' => '',
				'toolbar3' => '',
			),
		);
		
		wp_editor($content, $ID, $settings);
	}
	
	/**
	 * Renders pagination
     *
     * @access public
     * @return void
     */
	public static function renderPagination($current, $maximum) {		
		$args['base']=str_replace(999999999, '%#%', get_pagenum_link(999999999));
		$args['total']=intval($maximum);
		$args['current']=intval($current);

		$args['mid_size']=5;
		$args['end_size']=1;
		$args['prev_text']='';
		$args['next_text']='';
		
		$out=paginate_links($args);
		if($out!='') {
			$out='<nav class="pagination">'.$out.'</nav>';
		}
		
		echo $out;
	}
	
	/**
	 * Renders template title
     *
     * @access public
	 * @param string $title
	 * @param string $sep
     * @return void
     */
	public static function renderTemplateTitle($title, $sep) {
		if(isset($_GET['s']) && empty($_GET['s'])) {
			$title=__('Search Results', 'lovestory');
			$title.=' '.$sep.' ';
		}
	
		foreach(ThemexCore::$components['rewrite_rules'] as $key=>$rule) {
			if(get_query_var($rule['name'])) {
				$title=$rule['title'];
				$title.=' '.$sep.' ';
			}
		}
		
		return $title;
	}
	
	/**
	 * Renders page title
     *
     * @access public
     * @return void
     */
	public static function renderPageTitle() {
		global $post;
		$type=get_post_type();
		
		$out=wp_title('', false);
		
		if(is_single()) {
			if($type=='post') {
				$categories=wp_get_post_terms($post->ID, 'category');
				if(!empty($categories)) {
					$out=$categories[0]->name;
				}
			} else {
				$types=get_post_types(null, 'objects');
				$out=$types[$type]->labels->name;
			}
		} else if(is_tax()) {
			$out=single_term_title('', false);
		} else if(get_query_var('author')) {
			$out=__('Profile', 'lovestory');
		}

		if(empty($out)) {
			$out=__('Archives', 'lovestory');
		}
		
		echo $out;
	}
	
	/**
	 * Renders footer
     *
     * @access public
     * @return void
     */
	public static function renderFooter() {
		$out=ThemexCore::getOption('tracking');	
		echo $out;
	}
	
	/**
	 * Renders messages
     *
     * @access public
	 * @param array $messages
	 * @param bool $success
     * @return void
     */
	public static function renderMessages($success=false) {
		$out='';
		$class='error';
		if($success) {
			$class='success';
		}
		
		if(isset(self::$messages)) {
			$out.='<ul class="'.$class.'">';			
			foreach(self::$messages as $message) {
				$out.='<li>'.$message.'</li>';
			}			
			$out.='</ul>';
		}

		echo $out;
	}
}