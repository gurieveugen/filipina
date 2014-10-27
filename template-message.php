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
	<div class="pagination top-pagination clearfix">
		<?php ThemexInterface::renderPagination(themex_paged(), themex_pages(ThemexUser::getMessages(ThemexUser::$data['user']['ID'], get_query_var('message')), 5)); ?>
	</div>
	<!-- /pagination -->
	<ul class="bordered-list">
		<?php $messages=ThemexUser::getMessages(ThemexUser::$data['user']['ID'], get_query_var('message'), themex_paged()); ?>
		<?php 
		foreach($messages as $message) {
			$GLOBALS['comment']=$message;
			get_template_part('content', 'message');
		}
		?>
	</ul>						
	<!-- /messages -->
	<div class="message-form">
		<form class="formatted-form" action="<?php echo ThemexUser::$data['active_user']['message_url']; ?>" method="POST">
			<div class="message">
				<?php ThemexInterface::renderMessages(); ?>
			</div>
			<?php ThemexInterface::renderEditor('user_message'); ?>
			<a href="#" class="button submit-button"><?php _e('Send Message', 'lovestory'); ?></a>
			<input type="hidden" name="user_recipient" value="<?php echo get_query_var('message'); ?>" />
			<input type="hidden" name="user_action" value="add_message" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		</form>
	</div>						
</div>
<?php get_footer(); ?>