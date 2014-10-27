<?php
class ThemexSearch extends WP_Widget {

	//Widget Setup
	function __construct() {
		$widget_ops=array('classname' => 'widget_themex_search', 'description' => __('Shows profile search form', 'lovestory'));
		parent::__construct('widget_themex_search', __('Profile Search', 'lovestory'), $widget_ops);
		$this->alt_option_name='widget_themex_search';
	}

	//Widget view
	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$instance=wp_parse_args( (array)$instance, array(
			'title'=>__('Profile Search', 'lovestory'),
		));
		
		$out=$before_widget;
		
		$title=apply_filters( 'widget_title', empty($instance['title'])?__('Profile Search', 'lovestory'):$instance['title'], $instance, $this->id_base);
		$out.=$before_title.$title.$after_title;
		
		ob_start();
		get_template_part('module', 'search-form');
		$out.=ob_get_contents();
		ob_end_clean();				
		
		$out.=$after_widget;
		
		echo $out;
	}

	//Update widget
	function update( $new_instance, $old_instance ) {
		$instance=$old_instance;
		$instance['title']=sanitize_text_field($new_instance['title']);
		
		return $instance;
	}
	
	//Widget form
	function form( $instance ) {
		$instance=wp_parse_args( (array)$instance, array(
			'title'=>__('Profile Search', 'lovestory'),
		));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'lovestory'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php
	}
}