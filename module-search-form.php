<div class="profile-search-form">
	<form action="<?php echo SITE_URL; ?>" method="GET">
		<table>
			<tbody>
			<?php if(!ThemexCore::checkOption('user_gender')) { ?>
				<?php if(!empty(ThemexUser::$data['user']['profile']['gender'])) { ?>
				<input type="hidden" name="gender" value="<?php echo ThemexUser::$data['user']['profile']['gender']; ?>" />
				<?php } else { ?>
				<tr>
					<td><h5><?php _e('I am a', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'gender',
								'type' => 'select',
								'value' => isset($_GET['gender'])?$_GET['gender']:'man',
								'wrap' => false,
								'options' => ThemexCore::$components['genders'],					
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!empty(ThemexUser::$data['user']['profile']['seeking'])) { ?>
				<input type="hidden" name="seeking" value="<?php echo ThemexUser::$data['user']['profile']['seeking']; ?>" />
				<?php } else { ?>
				<tr>
					<td><h5><?php _e('Seeking a', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'seeking',
								'type' => 'select',
								'value' => isset($_GET['seeking'])?$_GET['seeking']:'woman',
								'wrap' => false,
								'options' => ThemexCore::$components['genders'],						
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('user_location')) { ?>
				<tr>
					<td><h5><?php _e('Country', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'country',
								'type' => 'select',
								'options' => array_merge(array('0' => '&ndash;'), ThemexCore::$components['countries']),
								'value' => isset($_GET['country'])?$_GET['country']:null,
								'attributes' => array('class' => 'countries-list'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td><h5><?php _e('City', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'city',
								'type' => 'select_city',
								'value' => isset($_GET['city'])?$_GET['city']:'',
								'attributes' => array(
									'class' => 'filterable-list',
									'data-filter' => 'countries-list',
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('user_age')) { ?>
				<tr>
					<td><h5><?php _e('From', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'age_from',
								'type' => 'select_age',
								'value' => isset($_GET['age_from'])?$_GET['age_from']:18,
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td><h5><?php _e('To', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'age_to',
								'type' => 'select_age',
								'value' => isset($_GET['age_to'])?$_GET['age_to']:35,
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
			<?php } ?>
				<?php
				ThemexForm::renderData('profile', array(
					'edit' => true,
					'search' => true,
					'before_title' => '<tr><td><h5>',
					'after_title' => '</h5></td>',
					'before_content' => '<td>',
					'after_content' => '</td></tr>',
				), $_GET);
				?>
			</tbody>
		</table>
		<a href="#" class="button medium submit-button"><span class="button-icon icon-search"></span><?php _e('Find My Matches', 'lovestory'); ?></a>
		<input type="hidden" name="s" value="" />
	</form>
</div>