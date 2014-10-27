<?php get_header(); ?>
<aside class="message-preview column threecol <?php if(!ThemexCore::checkOption('user_ignore')) { ?>unbordered<?php } ?>">
	<?php get_template_part('content', 'profile-grid'); ?>
	<?php if(!ThemexCore::checkOption('user_ignore')) { ?>
	<div class="profile-footer clearfix">
		<form action="" method="POST">
			<?php if(ThemexUser::isIgnored(ThemexUser::$data['active_user']['ID'])) { ?>
			<a href="#" class="button secondary submit-button"><?php _e('Unignore User', 'lovestory'); ?></a>			
			<input type="hidden" name="user_action" value="unignore_user" />
			<?php } else { ?>
			<a href="#" class="button submit-button"><?php _e('Ignore User', 'lovestory'); ?></a>			
			<input type="hidden" name="user_action" value="ignore_user" />
			<?php } ?>			
			<input type="hidden" name="user_ignore" value="<?php echo ThemexUser::$data['active_user']['ID']; ?>" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		</form>
	</div>
	<?php } ?>
</aside>
<div class="ninecol column last">
	<form action="<?php echo AJAX_URL; ?>" method="POST" class="ajax-form chat-form-send <?php if(ThemexUser::isIgnored(ThemexUser::$data['user']['ID'], false)) { ?>disabled<?php } ?>">
		<div class="chat-container scroll">
			<ul class="message static bordered-list">
				<?php if(ThemexUser::isIgnored(ThemexUser::$data['user']['ID'], false)) { ?>
				<li class="secondary"><?php _e("You've been added to the ignore list", 'lovestory'); ?></li>
				<?php } ?>
			</ul>
		</div>
		<input type="hidden" class="temporary" name="user_message" value="" />
		<input type="hidden" name="user_recipient" value="<?php echo ThemexUser::$data['active_user']['ID']; ?>" />
		<input type="hidden" name="user_action" value="update_chat" />
		<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
	<form action="<?php echo AJAX_URL; ?>" method="POST" class="formatted-form chat-form-update <?php if(ThemexUser::isIgnored(ThemexUser::$data['user']['ID'], false)) { ?>disabled<?php } ?>">
		<div class="chat-form-wrap">	
			<div class="chat-input-wrap">
				<div class="field-wrap">
					<input type="text" class="message" value="" />
				</div>
			</div>
			<div class="chat-button-wrap">
				<a href="#" class="button submit-button"><?php _e('Send', 'lovestory'); ?></a>
			</div>			
		</div>	
	</form>			
</div>
<?php get_footer(); ?>