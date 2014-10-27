<?php
//Columns
add_shortcode('one_sixth', 'themex_one_sixth');
function themex_one_sixth($atts, $content = null) {
   return '<div class="twocol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_sixth_last', 'themex_one_sixth_last');
function themex_one_sixth_last($atts, $content = null) {
   return '<div class="twocol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_fourth', 'themex_one_fourth');
function themex_one_fourth($atts, $content = null) {
   return '<div class="threecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_fourth_last', 'themex_one_fourth_last');
function themex_one_fourth_last($atts, $content = null) {
   return '<div class="threecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_third', 'themex_one_third');
function themex_one_third($atts, $content = null) {
   return '<div class="fourcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_third_last', 'themex_one_third_last');
function themex_one_third_last($atts, $content = null) {
   return '<div class="fourcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('five_twelfths', 'themex_five_twelfths');
function themex_five_twelfths($atts, $content = null) {
   return '<div class="fivecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('five_twelfths_last', 'themex_five_twelfths_last');
function themex_five_twelfths_last($atts, $content = null) {
   return '<div class="fivecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_half', 'themex_one_half');
function themex_one_half($atts, $content = null) {
   return '<div class="sixcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_half_last', 'themex_one_half_last');
function themex_one_half_last($atts, $content = null) {
   return '<div class="sixcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('seven_twelfths', 'themex_seven_twelfths');
function themex_seven_twelfths($atts, $content = null) {
   return '<div class="sevencol column">'.do_shortcode($content).'</div>';
}

add_shortcode('seven_twelfths_last', 'themex_seven_twelfths_last');
function themex_seven_twelfths_last($atts, $content = null) {
   return '<div class="sevencol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('two_thirds', 'themex_two_thirds');
function themex_two_thirds($atts, $content = null) {
   return '<div class="eightcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('two_thirds_last', 'themex_two_thirds_last');
function themex_two_thirds_last($atts, $content = null) {
   return '<div class="eightcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('three_fourths', 'themex_three_fourths');
function themex_three_fourths($atts, $content = null) {
   return '<div class="ninecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('three_fourths_last', 'themex_three_fourths_last');
function themex_three_fourths_last($atts, $content = null) {
   return '<div class="ninecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

//Button
add_shortcode('button','themex_button');
function themex_button($atts, $content=null) {	
	extract(shortcode_atts(array(
		'url'     	 => '#',
		'target' => 'self',
		'color'   => '',
		'size'	=> '',
    ), $atts));
	
	$out='<a href="'.$url.'" target="_'.$target.'" class="button '.$size.' '.$color.'">'.do_shortcode($content).'</a>';
	return $out;
}

//Title
add_shortcode('title', 'themex_title');
function themex_title($atts, $content=null) {
	extract(shortcode_atts(array(
		'align' => 'left',
    ), $atts));
	
	$out='<div class="section-title align'.$align.'"><h1>'.do_shortcode($content).'</h1></div>';
	return $out;
}

//Stories
add_shortcode('stories', 'themex_stories');
function themex_stories($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '3',
		'sections' => '0',
		'order' => 'date',	
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$query = new WP_Query(array(
		'post_type' => 'story',
		'showposts' => $number,	
		'orderby' => $order,		
	));
	
	$out='<section class="featured-stories">';
	while($query->have_posts()) {
		$query->the_post();
		
		$GLOBALS['content']=wpautop(get_the_excerpt());
		if(intval($sections)!=0) {
			$GLOBALS['content']=themex_sections(wpautop(get_the_content()), $sections);
		}
		
		$GLOBALS['content']=themex_excerpt($GLOBALS['content'], get_permalink());		
		
		ob_start();
		get_template_part('content','story-list');
		$out.=ob_get_contents();
		ob_end_clean();	
	}
	$out.='</section>';
	
	wp_reset_query();
	return $out;
}

//Profiles
add_shortcode('profiles', 'themex_profiles');
function themex_profiles($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '3',
		'order' => 'date',
    ), $atts));
	
	$args=array(
		'role' => 'subscriber',
		'number' => $number,
		'orderby' => 'registered',
		'order' => 'DESC',
		'exclude' => get_current_user_id(),
	);
	
	if(!ThemexCore::checkOption('user_name')) {
		$args['meta_query']=array(
			array(
				'key' => 'first_name',
				'value' => '',
				'compare' => '!=',
			),
		);
	}
	
	if($order=='name') {
		$args['orderby']='display_name';
		$args['order']='ASC';
	}
	
	if($order=='status' && isset($_SESSION['users']) && !empty($_SESSION['users'])) {
		$args['exclude']=array_merge(array(get_current_user_id()), array_keys($_SESSION['users']));
		$users=get_users($args);
		
		$args['include']=array_diff(array_keys($_SESSION['users']), array(get_current_user_id()));
		$args['exclude']=get_current_user_id();
		$users=array_slice(array_merge(get_users($args), $users), 0, $number);
	} else {
		$users=get_users($args);
	}
	
	$out='<section class="featured-profiles">';
	foreach($users as $user) {
		$GLOBALS['user']=$user;
		
		ob_start();
		get_template_part('content','profile-list');
		$out.=ob_get_contents();
		ob_end_clean();
	}
	$out.='</section>';
	
	return $out;
}

//Tabs
add_shortcode('tabs', 'themex_tabs');
function themex_tabs($atts, $content=null) {
	$out='<div class="tabs-container"><ul class="tabs clearfix">';
	
	$tabs=explode('][', $content);
	foreach($tabs as $tab) {
		$title='';		
		preg_match('/tab\s{1,}title=\"(.*)\"/', $tab, $matches);			
		if(isset($matches[1])) {
			$title=$matches[1];
		}
				
		if(!empty($title)) {
			$out.='<li><h5><a href="#'.themex_sanitize_key($title).'">'.$title.'</a></h5></li>';
		}
	}
	
	$out.='</ul><div class="panes">';
	$out.=do_shortcode($content);
    $out.= '</div></div>';
	
    return $out;
}

add_shortcode('tab', 'themex_tabs_panes');
function themex_tabs_panes($atts, $content=null) {
	extract(shortcode_atts(array(
		'title' => '',
    ), $atts));
	
	$out='<div class="pane" id="'.themex_sanitize_key($title).'-tab">'.do_shortcode($content).'</div>';	
    return $out;
}

//Toggle
add_shortcode('toggles', 'themex_toggles');
function themex_toggles( $atts, $content=null ) {
	$out='<div class="toggles-container">'.do_shortcode($content).'</div>';	
    return $out;
}

add_shortcode('toggle', 'themex_toggle');
function themex_toggle($atts, $content=null) {
    extract(shortcode_atts(array(
		'title'    	 => '',
    ), $atts));
	
	$out='<div class="toggle-container"><div class="toggle-title"><h4>'.$title.'</h4></div>';
	$out.='<div class="toggle-content"><p>'.do_shortcode($content).'</p></div></div>';	
	
	return $out;
}