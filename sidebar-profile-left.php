<aside class="column threecol">
	<div class="profile-preview">
		<div class="profile-image">
			<?php echo get_avatar(ThemexUser::$data['user']['ID'], 200); ?>
		</div>
		<div class="profile-options clearfix">
			<form action="" enctype="multipart/form-data" method="POST" class="upload-form">
				<label for="user_avatar" class="button small"><?php _e('Change Photo', 'lovestory'); ?></label>
				<input type="file" id="user_avatar" name="user_avatar" class="shifted" />
				<input type="hidden" name="user_action" value="update_avatar" />
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
			</form>
		</div>
	</div>
	<div class="widget profile-menu">
		<ul>
			<li <?php if(get_query_var('author')) { ?>class="current"<?php } ?>><a href="<?php echo ThemexUser::$data['user']['profile_url']; ?>"><?php _e('My Profile', 'lovestory'); ?></a></li>
			<?php if(ThemexUser::$data['user']['membership']['ID']>=0) { ?>
			<li <?php if(get_query_var('memberships')) { ?>class="current"<?php } ?>><a href="<?php echo ThemexUser::$data['user']['memberships_url']; ?>"><?php _e('My Membership', 'lovestory'); ?></a></li>
			<?php } ?>
			<li <?php if(get_query_var('settings')) { ?>class="current"<?php } ?>><a href="<?php echo ThemexUser::$data['user']['settings_url']; ?>"><?php _e('My Settings', 'lovestory'); ?></a></li>							
			<li <?php if(get_query_var('messages')) { ?>class="current"<?php } ?>>
				<div class="static-column tencol">
					<a href="<?php echo ThemexUser::$data['user']['messages_url']; ?>"><?php _e('My Messages', 'lovestory'); ?></a>
				</div>
				<div class="static-column twocol last profile-value"><?php echo ThemexUser::countMessages(ThemexUser::$data['user']['ID']); ?></div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>	
</aside>