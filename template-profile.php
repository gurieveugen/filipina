<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="full-profile fivecol column">
	<div class="section-title">
		<h2><?php _e('My Profile', 'lovestory'); ?></h2>
	</div>
	<form class="formatted-form" action="" method="POST">
		<table class="profile-fields" data-test="asdf">
			<tbody>
				<?php if(!ThemexCore::checkOption('user_name')) { ?>
				<tr>
					<th><?php _e('First Name', 'lovestory'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="first_name" size="50" value="<?php echo ThemexUser::$data['user']['profile']['name']; ?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Last Name', 'lovestory'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="last_name" size="50" value="<?php echo ThemexUser::$data['user']['profile']['last_name']; ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('user_gender')) { ?>
				<tr>
					<th><?php _e('Gender', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'gender',
								'type' => 'select',
								'value' => !empty(ThemexUser::$data['user']['profile']['gender'])?ThemexUser::$data['user']['profile']['gender']:'man',
								'options' => ThemexCore::$components['genders'],
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Seeking', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'seeking',
								'type' => 'select',
								'value' => !empty(ThemexUser::$data['user']['profile']['seeking'])?ThemexUser::$data['user']['profile']['seeking']:'woman',
								'options' => ThemexCore::$components['genders'],
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('user_age')) { ?>
				<tr>
					<th><?php _e('Age', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'age',
								'type' => 'select_age',
								'value' => ThemexUser::$data['user']['profile']['age'],
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('user_location')) { ?>
				<tr>
					<th><?php _e('Country', 'lovestory'); ?></th>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'country',
								'type' => 'select',
								'options' => array_merge(array('0' => '&ndash;'), ThemexCore::$components['countries']),
								'value' => !empty(ThemexUser::$data['user']['profile']['country'])?ThemexUser::$data['user']['profile']['country']:null,
								'wrap' => false,				
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('City', 'lovestory'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="city" size="50" value="<?php echo ThemexUser::$data['user']['profile']['city']; ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php
				ThemexForm::renderData('profile', array(
					'edit' =>  true,
					'before_title' => '<tr><th>',
					'after_title' => '</th>',
					'before_content' => '<td>',
					'after_content' => '</td></tr>',
				), ThemexUser::$data['user']['profile']);
				?>
			</tbody>
		</table>
		<div class="profile-description">
			<?php ThemexInterface::renderEditor('description', wpautop(ThemexUser::$data['user']['profile']['description'])); ?>
		</div>		
		<a href="#" class="button submit-button"><?php _e('Save Changes', 'lovestory'); ?></a>
		<input type="hidden" name="update" value="1" />
		<input type="hidden" name="user_action" value="update_profile" />
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
	</form>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>