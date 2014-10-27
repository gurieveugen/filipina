<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="full-profile fivecol column">
	<div class="section-title">
		<h2><?php _e('My Messages', 'lovestory'); ?></h2>
	</div>
	<?php 
	$recipients=ThemexUser::getRecipients(ThemexUser::$data['user']['ID']); 
	if(!empty($recipients)) {
	?>
	<ul class="bordered-list">
		<?php 
		foreach($recipients as $recipient) {
		ThemexUser::$data['active_user']=ThemexUser::getUser($recipient['ID']);
		?>
		<li class="clearfix">
			<div class="static-column tencol">
				<h4><?php get_template_part('module', 'status'); ?><a href="<?php echo ThemexUser::$data['active_user']['message_url']; ?>"><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></a></h4>
			</div>
			<?php if($recipient['unread']>0) { ?>
			<div class="static-column twocol last profile-value"><?php echo $recipient['unread']; ?></div>
			<?php } ?>
		</li>
		<?php 
		}
		ThemexUser::refresh();
		?>
	</ul>
	<?php } else { ?>
	<p class="secondary"><?php _e('No messages received yet.', 'lovestory'); ?></p>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>