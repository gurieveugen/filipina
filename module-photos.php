<div class="widget clearfix">
	<h4 class="widget-title clearfix">
		<span class="left"><?php _e('Photos', 'lovestory'); ?></span>
		<span class="widget-options">
			<?php if(ThemexUser::isProfile()) { ?>
			<form action="" enctype="multipart/form-data" method="POST" class="upload-form popup-container">
				<label for="user_photo" title="<?php _e('Upload New Photo', 'lovestory'); ?>"></label>
				<input type="file" id="user_photo" name="user_photo" class="shifted" />
				<input type="hidden" name="user_action" value="add_photo" />
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
				<?php if(ThemexUser::$data['user']['membership']['photos']<=0) { ?>
				<div class="popup hidden">
					<ul class="error">
						<li><?php _e('You have exceeded the number of photos', 'lovestory'); ?></li>
					</ul>
				</div>
				<?php } ?>				
			</form>
			<?php } ?>
		</span>	
	</h4>
	<?php if(empty(ThemexUser::$data['active_user']['photos'])) { ?>
	<span class="secondary"><?php _e('No photos uploaded yet.', 'lovestory'); ?></span>
	<?php } else { ?>
	<div class="themex-slider carousel-slider">						
		<ul>
			<?php
			$counter=0;
			foreach(ThemexUser::sortPhotos(ThemexUser::$data['active_user']['photos']) as $photo) {
			$thumbnail=wp_get_attachment_image_src($photo['ID'], 'full');
			$fullsize=wp_get_attachment_image_src($photo['ID'], 'extended');
			$counter++;
			if($counter==1) {
			?>
			<li class="clearfix">
			<?php } ?>
				<div class="fourcol static-column <?php if($counter==3) { ?>last<?php } ?>">
					<div class="profile-preview widget-profile">
						<div class="profile-image popup-container">
							<a href="<?php echo $fullsize[0]; ?>" class="colorbox" data-group="photos"><img src="<?php echo themex_resize($thumbnail[0], 150, 150); ?>" class="fullwidth" alt="" /></a>
							<?php if(!is_user_logged_in()) { ?>
							<div class="popup hidden">
								<ul class="error">
									<li><?php _e('Sign in to view full size photos', 'lovestory'); ?></li>
								</ul>
							</div>
							<?php } ?>
						</div>
						<?php if(ThemexUser::isProfile()) { ?>
						<div class="profile-options clearfix">
							<div class="profile-option">
								<form class="ajax-form" action="<?php echo AJAX_URL; ?>" method="POST">
									<?php if(ThemexUser::isFeaturedPhoto($photo['ID'])) { ?>
									<a href="#" title="<?php _e('Unfeature Photo', 'lovestory'); ?>" data-title="<?php _e('Feature Photo', 'lovestory'); ?>" class="icon-star submit-button current"></a>
									<input type="hidden" class="toggle" name="user_action" value="unfeature_photo" data-value="feature_photo" />
									<?php } else { ?>
									<a href="#" title="<?php _e('Feature Photo', 'lovestory'); ?>" data-title="<?php _e('Unfeature Photo', 'lovestory'); ?>" class="icon-star submit-button"></a>
									<input type="hidden" class="toggle" name="user_action" value="feature_photo" data-value="unfeature_photo" />
									<?php } ?>
									<input type="hidden" name="user_photo" value="<?php echo $photo['ID']; ?>" />
									<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
									<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />			
								</form>
							</div>
							<div class="profile-option">
								<form action="" method="POST">
									<a href="#" title="<?php _e('Remove Photo', 'lovestory'); ?>" class="submit-button icon-remove"></a>									
									<input type="hidden" name="user_photo" value="<?php echo $photo['ID']; ?>" />
									<input type="hidden" name="user_action" value="remove_photo" />
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
								</form>
							</div>
						</div>
						<?php } ?>	
					</div>										
				</div>
			<?php 
			if($counter==3) {
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