<?php
/**
 * Template name: Message2
 */
?>
<?php
if(get_current_user_id() != 411251 AND !is_admin()) wp_redirect( get_bloginfo( 'url' ) );
if(isset($_GET['user_message']))
{
	if(Messages::add($_GET['owner'], $_GET['user_recipient'], $_GET['user_message']))
	{
		wp_redirect( 
			sprintf(
				'%s/msg?owner=%s&to=%s',
				get_bloginfo('url'),
				$_GET['user_recipient'],
				$_GET['owner']
			),
			303
		); 
		exit;
	}
}
?>
<?php get_header(); ?>
<aside class="message-preview column threecol <?php if(!ThemexCore::checkOption('user_ignore')) { ?>unbordered<?php } ?>">
	<?php get_template_part('content', 'profile-grid-msg'); ?>
	<?php if(!ThemexCore::checkOption('user_ignore')) { ?>
	<div class="profile-footer clearfix">
		<form action="" method="POST">
			<?php if(ThemexUser::isIgnored($_GET['owner'])) { ?>
			<a href="#" class="button secondary submit-button"><?php _e('Unignore User', 'lovestory'); ?></a>			
			<input type="hidden" name="user_action" value="unignore_user" />
			<?php } else { ?>
			<a href="#" class="button submit-button"><?php _e('Ignore User', 'lovestory'); ?></a>			
			<input type="hidden" name="user_action" value="ignore_user" />
			<?php } ?>			
			<input type="hidden" name="user_ignore" value="<?php echo $_GET['owner']; ?>" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		</form>
	</div>
	<?php } ?>
</aside>
<div class="ninecol column last">	
	<div class="pagination top-pagination clearfix">
		<?php ThemexInterface::renderPagination(themex_paged(), themex_pages(ThemexUser::getMessages($_GET['owner'], $_GET['to']), 5)); ?>
	</div>
	<!-- /pagination -->
	<ul class="bordered-list aaa">
		<?php $messages=ThemexUser::getMessages($_GET['owner'], $_GET['to'], themex_paged()); ?>
		<?php 
		ThemexUser::$data['user']['ID'] = $_GET['owner'];
		foreach($messages as $message) {
			$GLOBALS['comment']=$message;
			get_template_part('content', 'message');
		}
		?>
	</ul>						
	<!-- /messages -->
	<div class="message-form">
		<form class="formatted-form" action="" method="GET">
			<div class="message">
				<?php ThemexInterface::renderMessages(); ?>
			</div>
			<?php ThemexInterface::renderEditor('user_message'); ?>
			<input type="submit" value="<?php _e('Send Message', 'lovestory'); ?>">
			<input type="hidden" name="user_recipient" value="<?php echo $_GET['owner']; ?>" />
			<input type="hidden" name="owner" value="<?php echo $_GET['to']; ?>" />
			<input type="hidden" name="user_action" value="add_message" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		</form>
	</div>						
</div>
<?php get_footer(); ?>