<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="full-profile fivecol column">
	<div class="section-title">
		<h2><?php _e('My Settings', 'lovestory'); ?></h2>
	</div>
	<form class="formatted-form" action="" method="POST">
		<div class="message">
			<?php ThemexInterface::renderMessages(isset($_POST['success'])?$_POST['success']:false); ?>
		</div>
		<table class="profile-fields">
			<tbody>								
				<tr>
					<th><?php _e('Favorites are Visible to', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'user_favorites',
								'type' => 'select',
								'value' => ThemexUser::$data['user']['settings']['favorites'],
								'wrap' => false,
								'options' => array(
									'1' => __('Everybody','lovestory'), 
									'2' => __('Favorites','lovestory'),
									'3' => __('Nobody','lovestory'),
								),
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Photos are Visible to', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'user_photos',
								'type' => 'select',
								'value' => ThemexUser::$data['user']['settings']['photos'],
								'wrap' => false,
								'options' => array(
									'1' => __('Everybody','lovestory'), 
									'2' => __('Favorites','lovestory'),
									'3' => __('Nobody','lovestory'),
								),
							));
							?>
						</div>
					</td>
				</tr>
				<?php if(!ThemexCore::checkOption('user_gifts')) { ?>
				<tr>
					<th><?php _e('Gifts are Visible to', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'user_gifts',
								'type' => 'select',
								'value' => ThemexUser::$data['user']['settings']['gifts'],
								'wrap' => false,
								'options' => array(
									'1' => __('Everybody','lovestory'), 
									'2' => __('Favorites','lovestory'),
									'3' => __('Nobody','lovestory'),
								),
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('user_notice')) { ?>
				<tr>
					<th><?php _e('Email Notifications', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'user_notices',
								'type' => 'select',
								'value' => ThemexUser::$data['user']['settings']['notices'],
								'wrap' => false,
								'options' => array(
									'1' => __('Messages and Gifts','lovestory'), 
									'2' => __('Messages','lovestory'),
									'3' => __('Gifts','lovestory'),
									'4' => __('None','lovestory'),
								),
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<th><?php _e('New Password', 'lovestory'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="password" name="user_password" size="50" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Repeat New Password', 'lovestory'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="password" name="user_password_repeat" size="50" />
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<a href="#" class="button submit-button"><?php _e('Save Changes', 'lovestory'); ?></a>
		<input type="hidden" name="user_action" value="update_settings" />
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
	</form>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>