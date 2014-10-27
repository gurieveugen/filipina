<div class="widget clearfix">
	<h4 class="widget-title clearfix">
		<span class="left"><?php _e('Gifts', 'lovestory'); ?></span>
		<span class="widget-options"></span>
	</h4>
	<?php if(empty(ThemexUser::$data['active_user']['gifts'])) { ?>
	<span class="secondary"><?php _e('No gifts received yet.', 'lovestory'); ?></span>
	<?php } else { ?>
	<div class="themex-slider carousel-slider">						
		<ul>
			<?php
			$counter=0;
			foreach(ThemexUser::$data['active_user']['gifts'] as $gift) {
			$counter++;
			
			$name=get_user_meta($gift['sender'], 'first_name', true);
			if(ThemexCore::checkOption('user_name') || empty($name)) {
				$name=get_user_meta($gift['sender'], 'nickname', true);
			} else {
				$name=trim($name.' '.get_user_meta($gift['sender'], 'last_name', true));
			}
			
			if($counter==1) {
			?>
			<li class="clearfix">
			<?php } ?>
				<div class="threecol static-column <?php if($counter==4) { ?>last<?php } ?>">
					<?php if(!empty($name)) { ?>
					<a href="<?php echo get_author_posts_url($gift['sender']); ?>" class="tooltip">
						<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($gift['ID'])); ?>" class="fullwidth" alt="" />
						<span class="tooltip-wrap">
							<span class="tooltip-text"><?php echo $name; ?></span>
						</span>
					</a>
					<?php } else { ?>
					<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($gift['ID'])); ?>" class="fullwidth" alt="" />
					<?php } ?>
				</div>
			<?php 
			if($counter==4) {
			$counter=0;
			?>
			</li>
			<?php 
				}
			}
			if($counter!==0) {
			?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</div>