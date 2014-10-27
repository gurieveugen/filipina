<div class="profile-preview">
	<div class="profile-image">
		<a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>"><?php echo get_avatar(ThemexUser::$data['active_user']['ID'], 200); ?></a>
	</div>
	<?php if(is_page_template('template-profiles.php') || ThemexCore::isRewriteRule('message') || ThemexCore::isRewriteRule('chat') || ThemexUser::isUserFilter()) { ?>
	<div class="profile-text">
		<h5><?php get_template_part('module', 'status'); ?><a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>"><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></a></h5>
		<p><?php echo ThemexUser::getExcerpt(ThemexUser::$data['active_user']['profile']); ?></p>
	</div>
	<?php } ?>
	<div class="profile-options popup-container clearfix">
		<div class="profile-option">
			<form class="ajax-form" action="<?php echo AJAX_URL; ?>" method="POST">
				<?php if(ThemexUser::isFavorite(ThemexUser::$data['active_user']['ID'])) { ?>
				<a href="#" title="<?php _e('Remove from Favorites', 'lovestory'); ?>" data-title="<?php _e('Add to Favorites', 'lovestory'); ?>" class="icon-heart submit-button current"></a>
				<input type="hidden" class="toggle" name="user_action" value="remove_favorite" data-value="add_favorite" />
				<?php } else { ?>
				<a href="#" title="<?php _e('Add to Favorites', 'lovestory'); ?>" data-title="<?php _e('Remove from Favorites', 'lovestory'); ?>" class="icon-heart submit-button"></a>
				<input type="hidden" class="toggle" name="user_action" value="add_favorite" data-value="remove_favorite" />
				<?php } ?>
				<input type="hidden" name="user_favorite" value="<?php echo ThemexUser::$data['active_user']['ID']; ?>" />
				<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
				<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
			</form>
		</div>
		<?php if(!ThemexCore::checkOption('user_gifts')) { ?>
		<div class="profile-option">
			<a href="#gifts_listing" title="<?php _e('Send Gift', 'lovestory'); ?>" class="icon-gift colorbox inline" data-value="<?php echo ThemexUser::$data['active_user']['ID']; ?>"></a>
		</div>
		<?php } ?>
		<div class="profile-option">
			<a href="<?php echo ThemexUser::$data['active_user']['message_url']; ?>" title="<?php _e('Send Message', 'lovestory'); ?>" class="icon-envelope-alt"></a>
		</div>
		<?php if(!ThemexCore::checkOption('user_chat')) { ?>
		<div class="profile-option">
			<a href="<?php echo ThemexUser::$data['active_user']['chat_url']; ?>" title="<?php _e('Live Chat', 'lovestory'); ?>" data-popup="chat" class="icon-comment"></a>
		</div>
		<?php } ?>
		<?php if(!is_user_logged_in()) { ?>
		<div class="popup hidden">
			<ul class="error">
				<li><?php _e('You must be signed in to do that', 'lovestory'); ?></li>
			</ul>
		</div>
		<?php } else if(!ThemexUser::$data['user']['membership']['chat']) { ?>
		<div class="popup hidden" data-id="chat">
			<ul class="error">
				<li><?php _e('Live chat is disabled in your membership', 'lovestory'); ?></li>
			</ul>
		</div>
		<?php } ?>
	</div>
</div>