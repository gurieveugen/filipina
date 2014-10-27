<?php
class ThemexAdvert extends WP_Widget {

	//Widget Setup
	function __construct() {
		$widget_ops=array('classname' => 'widget_themex_advert', 'description' => __('Shows banner with a link', 'lovestory'));
		parent::__construct('widget_themex_advert', __('Banner','lovestory'), $widget_ops);
		$this->alt_option_name='widget_themex_advert';
	}

	//Widget view
	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$instance=wp_parse_args( (array)$instance, array(
			'image'=>'',
			'link'=>'',
			'target'=>'',
		));
		
		$out=$before_widget;
		$out.='<div class="bordered-image sidebar-widget"><a href="'.$instance['link'].'" target="_'.$instance['target'].'">';		
		if(!empty($instance['image'])) {
			$out.='<img src="'.$instance['image'].'" class="fullwidth" alt="">';
		}		
		$out.='</a></div>';
		$out.=$after_widget;
		
		echo $out;
	}

	//Update widget
	function update( $new_instance, $old_instance ) {
		$instance=$old_instance;
		$instance['image']=strip_tags($new_instance['image']);
		$instance['link']=strip_tags($new_instance['link']);
		$instance['target']=strip_tags($new_instance['target']);
		
		return $instance;
	}
	
	//Widget form
	function form( $instance ) {
		$instance=wp_parse_args( (array)$instance, array(
			'image'=>'',
			'link'=>'',
			'target'=>'',
		)); 
		?>
		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image', 'lovestory'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo $instance['image']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'lovestory'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Target', 'lovestory'); ?>:</label>
			<?php 
			echo ThemexInterface::renderOption(array(
				'id' => $this->get_field_name('target'),
				'type' => 'select',
				'value' => $instance['target'],
				'wrap' => false,
				'options' => array(
					'self' => __('Current Tab','lovestory'), 
					'blank' => __('New Tab','lovestory'),
				),
				'attributes' => array(
					'class' => 'widefat',
				),
			));
			?>
		</p>
	<?php
	}
}