<?php
/**
 * Themex Style
 *
 * Adds custom styles and fonts
 *
 * @class ThemexStyle
 * @author Themex
 */
 
class ThemexStyle {

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//add custom styles
		add_action('wp_head', array(__CLASS__,'renderStyles'));
		
		//add custom fonts
		add_action('wp_head', array(__CLASS__,'renderFonts'));
	}
	
	/**
	 * Adds custom styles
     *
     * @access public
     * @return void
     */
	public static function renderStyles() {
	
		$out='<link rel="shortcut icon" href="'.ThemexCore::getOption('favicon', THEME_URI.'images/favicon.ico').'" />';	
		$out.='<style type="text/css">';
		
		if(isset(ThemexCore::$components['custom_styles'])) {			
			foreach(ThemexCore::$components['custom_styles'] as $style) {
				$out.=$style['elements'].'{';
				
				foreach($style['attributes'] as $attribute) {
					$option=ThemexCore::getOption($attribute['option']);
					
					if($option) {
						if($attribute['name']=='background-image') {
							$option='url('.$option.')';
						} else if($attribute['name']=='font-size') {
							$option=$option.'px';
						} else if($attribute['name']=='font-family') {
							$option=$option.', Arial, Helvetica, sans-serif';
						}
						
						$out.=$attribute['name'].':'.$option.';';
					}
				}				
				
				$out.='}';				
			}
		}
		
		$out.=ThemexCore::getOption('css');
		$out.='</style>';

		echo $out;
	}
	
	/**
	 * Adds custom fonts
     *
     * @access public
     * @return void
     */
	public static function renderFonts() {
		$fonts=array();
		foreach(ThemexCore::$options as $option) {
			if($option['type']=='select_font') {
				$fonts[]=ThemexCore::getOption($option['id'], $option['default']);
			}
		}
		
		if(!empty($fonts)) {
			$out='<script type="text/javascript">
			WebFontConfig = {google: { families: [ "'.implode($fonts, '","').'" ] } };
			(function() {
				var wf = document.createElement("script");
				wf.src = ("https:" == document.location.protocol ? "https" : "http") + "://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
				wf.type = "text/javascript";
				wf.async = "true";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(wf, s);
			})();
			</script>';
			
			echo $out;
		}		
	}
}