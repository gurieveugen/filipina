<?php
$query=new WP_Query(array(
	'post_type' =>'slide',
	'showposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));

if($query->have_posts()) {
?>
<div class="header-slider themex-slider">
	<ul>
		<?php 
		while($query->have_posts()) { 
		$query->the_post(); 
		?>
		<li>
			<div class="container">
				<?php the_content(); ?>
			</div>
		</li>
		<?php } ?>
	</ul>
	<input type="hidden" class="slider-pause" value="<?php echo ThemexCore::getOption('slider_pause', '0'); ?>" />
	<input type="hidden" class="slider-speed" value="<?php echo ThemexCore::getOption('slider_speed', '1000'); ?>" />	
</div>
<?php } else { ?>
<div class="header-slider"></div>
<?php } ?>