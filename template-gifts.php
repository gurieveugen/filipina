<?php
$query=new WP_Query(array(
	'post_type' =>'gift',
	'showposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));
?>
<div class="hidden">	
	<section id="gifts_listing" class="gifts-listing element-select">
		<?php if($query->have_posts()) { ?>
		<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">
			<div class="message"></div>
			<?php
			$counter=0;
			while($query->have_posts()) {
			$query->the_post();
			$counter++;
			?>
				<div class="threecol static-column <?php if($counter==4) { ?>last<?php } ?>">
					<div class="listed-gift bordered-image element-option" data-value="<?php the_ID(); ?>">
					<?php the_post_thumbnail('normal', array('class' => 'fullwidth')); ?>
					</div>
				</div>
				<?php
				if($counter==4) {
				$counter=0;
				?>
				<div class="clear"></div>
				<?php } ?>
			<?php } ?>
			<div class="clear"></div>
			<a href="#" class="button submit-button"><?php _e('Send Gift', 'lovestory'); ?></a>
			<div class="loader"></div>
			<input type="hidden" name="user_action" value="add_gift" />
			<input type="hidden" class="element-value" name="user_gift" value="" />
			<input type="hidden" class="colorbox-value" name="user_recipient" value="" />
			<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
			<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
		</form>
		<?php } else { ?>
		<ul class="error">
			<li><?php _e('No gifts available yet.', 'lovestory'); ?></li>
		</ul>
		<?php } ?>
	</section>	
</div>