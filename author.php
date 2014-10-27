<?php if(ThemexUser::isProfile()) { ?>
<?php get_template_part('template', 'profile'); ?>
<?php } else { ?>
<?php get_header(); ?>
<aside class="column threecol">	
	<?php get_template_part('content', 'profile-grid'); ?>
</aside>
<div class="full-profile fivecol column">
	<div class="section-title separated-title">
		<h2>
		<?php
		get_template_part('module', 'status');
		echo ThemexUser::$data['active_user']['profile']['full_name'];
		?>
		</h2>
	</div>
	<table class="profile-fields">
		<tbody>	
		<?php if(!ThemexCore::checkOption('user_gender')) { ?>
			<?php if(!empty(ThemexUser::$data['active_user']['profile']['gender'])) { ?>
			<tr>
				<th><?php _e('Gender', 'lovestory'); ?></th>
				<td><?php echo themex_array_value(ThemexUser::$data['active_user']['profile']['gender'], ThemexCore::$components['genders']); ?></td>
			</tr>
			<?php } ?>			
			<?php if(!empty(ThemexUser::$data['active_user']['profile']['seeking'])) { ?>
			<tr>
				<th><?php _e('Seeking', 'lovestory'); ?></th>
				<td><?php echo themex_array_value(ThemexUser::$data['active_user']['profile']['seeking'], ThemexCore::$components['genders']); ?></td>
			</tr>
			<?php } ?>
		<?php } ?>
		<?php if(!empty(ThemexUser::$data['active_user']['profile']['age']) && !ThemexCore::checkOption('user_age')) { ?>
			<tr>
				<th><?php _e('Age', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['active_user']['profile']['age']; ?></td>
			</tr>
		<?php } ?>			
		<?php if(!ThemexCore::checkOption('user_location')) { ?>
			<?php if(!empty(ThemexUser::$data['active_user']['profile']['country'])) { ?>
			<tr>
				<th><?php _e('Country', 'lovestory'); ?></th>
				<td><?php echo themex_array_value(ThemexUser::$data['active_user']['profile']['country'], ThemexCore::$components['countries']); ?></td>
			</tr>
			<?php } ?>
			<?php if(!empty(ThemexUser::$data['active_user']['profile']['city'])) { ?>
			<tr>
				<th><?php _e('City', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['active_user']['profile']['city']; ?></td>
			</tr>
			<?php } ?>
		<?php } ?>
			<?php
			ThemexForm::renderData('profile', array(
				'before_title' => '<tr><th>',
				'after_title' => '</th>',
				'before_content' => '<td>',
				'after_content' => '</td></tr>',
			), ThemexUser::$data['active_user']['profile']);
			?>
		</tbody>
	</table>
	<div class="profile-description">
	<?php echo wpautop(ThemexInterface::parseEmbed(ThemexUser::$data['active_user']['profile']['description'])); ?>
	</div>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>
<?php } ?>