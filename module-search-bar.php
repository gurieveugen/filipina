<div class="header-search-form clearfix">
	<form action="<?php echo SITE_URL; ?>" method="GET">
		<div class="text-field field-wrap"><?php _e('Hello, I am a', 'lovestory'); ?></div>
		<?php if(!empty(ThemexUser::$data['user']['profile']['gender'])) { ?>
		<div class="text-field field-wrap"><?php echo themex_array_value(ThemexUser::$data['user']['profile']['gender'], ThemexCore::$components['genders']); ?></div>
		<input type="hidden" name="gender" value="<?php echo ThemexUser::$data['user']['profile']['gender']; ?>" />
		<?php } else { ?>
		<div class="field-wrap">
			<div class="select-field">
				<span></span>
				<?php 
				echo ThemexInterface::renderOption(array(
					'id' => 'gender',
					'type' => 'select',
					'value' => 'man',
					'wrap' => false,
					'options' => ThemexCore::$components['genders'],
				));
				?>
			</div>
		</div>
		<?php } ?>
		<div class="text-field field-wrap"><?php _e('seeking a', 'lovestory'); ?></div>
		<?php if(!empty(ThemexUser::$data['user']['profile']['seeking'])) { ?>
		<div class="text-field field-wrap"><?php echo themex_array_value(ThemexUser::$data['user']['profile']['seeking'], ThemexCore::$components['genders']); ?></div>
		<input type="hidden" name="seeking" value="<?php echo ThemexUser::$data['user']['profile']['seeking']; ?>" />
		<?php } else { ?>
		<div class="field-wrap">
			<div class="select-field">
				<span></span>
				<?php 
				echo ThemexInterface::renderOption(array(
					'id' => 'seeking',
					'type' => 'select',
					'value' => 'woman',
					'wrap' => false,
					'options' => ThemexCore::$components['genders'],							
				));
				?>
			</div>
		</div>
		<?php } ?>
		<?php if(!ThemexCore::checkOption('user_age')) { ?>
		<div class="text-field field-wrap mobile-hidden"><?php _e('from', 'lovestory'); ?></div>
		<div class="field-wrap mobile-hidden">
			<div class="select-field">
				<span></span>
				<?php 
				echo ThemexInterface::renderOption(array(
					'id' => 'age_from',
					'type' => 'select_age',
					'value' => 18,
					'wrap' => false,
				));
				?>
			</div>
		</div>	
		<div class="text-field field-wrap mobile-hidden"><?php _e('to', 'lovestory'); ?></div>
		<div class="field-wrap mobile-hidden">
			<div class="select-field">
				<span></span>
				<?php 
				echo ThemexInterface::renderOption(array(
					'id' => 'age_to',
					'type' => 'select_age',
					'value' => 35,
					'wrap' => false,
				));
				?>
			</div>
		</div>
		<?php } ?>
		<div class="button-field field-wrap">
			<a href="#" class="button submit-button"><span class="button-icon icon-search"></span><?php _e('Find My Matches', 'lovestory'); ?></a>
		</div>
		<input type="hidden" name="s" value="" />
	</form>
</div>