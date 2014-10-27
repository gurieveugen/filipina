<div class="widget clearfix">
	<h4 class="widget-title clearfix">
		<span class="left"><?php _e('Favorites', 'lovestory'); ?></span>
		<span class="widget-options"></span>
	</h4>
	<?php if(empty(ThemexUser::$data['active_user']['favorites'])) { ?>
	<span class="secondary"><?php _e('No favorites added yet.', 'lovestory'); ?></span>
	<?php } else { ?>
	<div class="themex-slider carousel-slider">
		<ul>
			<?php
			$counter=0;
			foreach(ThemexUser::$data['active_user']['favorites'] as $user) {
			ThemexUser::$data['active_user']=ThemexUser::getUser($user['ID']);
			$counter++;
			if($counter==1) {
			?>
			<li class="clearfix">
			<?php } ?>
				<div class="fourcol static-column <?php if($counter==3) { ?>last<?php } ?>">
					<div class="profile-preview widget-profile">
						<div class="profile-image">
							<a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>" title="<?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?>">
								<?php echo get_avatar($user['ID'], 100); ?>
							</a>
						</div>					
						<div class="profile-options clearfix">
							<div class="profile-option">
								<?php get_template_part('module', 'status'); ?>
							</div>
							<div class="profile-option">								
								<form action="" method="POST">
									<a href="#" title="<?php _e('Remove from Favorites', 'lovestory'); ?>" class="submit-button icon-remove"></a>									
									<input type="hidden" name="user_favorite" value="<?php echo $user['ID']; ?>" />
									<input type="hidden" name="user_action" value="remove_favorite" />
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
								</form>
							</div>
						</div>
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
			
			ThemexUser::refresh();
			if($counter!==0) {
			?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</div>