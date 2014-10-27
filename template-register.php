<?php
/*
Template Name: Registration
*/
?>
<?php get_header(); ?>
<!--ACTUAL CONTENT STARTS HERE -->
<div class="column">
<?php if(get_option('users_can_register')):?>
<div class="">
	<div class="section-title">
		<h1><?php _e('Register', 'lovestory'); ?> REGISTER</h1>
	</div>
</div>
<?php endif;?>
<div class="facebook-login-area">
<div class="column fourcol">
	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">
		<div class="message"></div>
		<!--<a href="#" class="button submit-button form-button"><?php _e('Sign In', 'lovestory'); ?></a>-->
		<?php if(ThemexFacebook::isActive()) { ?>
		<a href="<?php echo home_url('?facebook_login=1'); ?>" 
        	class="button facebook-login-button form-button" title="<?php _e('Sign in with Facebook', 'lovestory'); ?>">
			<!--<span class="button-icon icon-facebook nomargin"></span>-->
            <img src="<?php echo THEME_URI?>/images/icons/btnLoginFacebook.png" />
		</a>
		<?php } ?>
		<div class="loader"></div>
		<input type="hidden" name="user_action" value="login_user" />
		<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
</div>
</div>
<div class="clear"></div>
<div class="column fourcol">
<div class="clear"></div>
	UPLOAD IMAGE OPTION HERE    
</div>
<?php if(get_option('users_can_register')) { ?>
<div class="column eightcol last">
    <div class="sub-title">
    	<h2><?php _e('100% Free site (You might decide to pay only for added visibility)!','lovestory'); ?></h2>
        <h3>
		<?php _e('This below are the only info we require you to provide us to register on our site (no more forms to fill-up after this)!','lovestory'); ?>
        </h3>
    </div>
	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL_ADD_PROFILE;//echo AJAX_URL; ?>" method="POST">
		<div class="message"></div>
		<div class="column sixcol">
			<div class="field-wrap">
				<input type="text" name="user_login" placeholder="<?php _e('Username', 'lovestory'); ?>" />
			</div>
		</div>
		<div class="column sixcol last">
			<div class="field-wrap">
				<input type="text" name="user_email" placeholder="<?php _e('Email', 'lovestory'); ?>" />
			</div>
		</div>
		<div class="clear"></div>
		<div class="column sixcol">
			<div class="field-wrap">
				<input type="password" name="user_password" placeholder="<?php _e('Password','lovestory'); ?>" />
			</div>
		</div>
		<div class="column sixcol last">
			<div class="field-wrap">
				<input type="password" name="user_password_repeat" placeholder="<?php _e('Repeat Password','lovestory'); ?>" />
			</div>
		</div>
        <div class="clear"></div>
        <div class="column sixcol">
			<div class="field-wrap">
				<input type="text" name="first_name" placeholder="<?php _e('First Name','lovestory'); ?>" />
			</div>
		</div>
        <div class="column sixcol last">
			<div class="field-wrap">
				<input type="text" name="last_name" placeholder="<?php _e('Last Name','lovestory'); ?>" />
			</div>
		</div>
        <div class="clear"></div>
        <?php if(!ThemexCore::checkOption('user_gender')):?>
            <div class="column fourcol">
                <div class="field-wrap">
                <?php _e('Gender', 'lovestory'); ?>
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
                </div>
            </div>
        <?php endif;?>
        <div class="column fourcol">
            <div class="field-wrap">
            	<?php _e('Seeking', 'lovestory'); ?>
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
            </div>
        </div>
        <?php if(!ThemexCore::checkOption('user_age')):?>
        <div class="column fourcol last">
            <div class="field-wrap">
            	<?php _e('Age', 'lovestory'); ?>
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
            </div>
        </div>
        <?php endif;?>
        <div class="clear"></div>
        <?php if(!ThemexCore::checkOption('user_location')):?>
            <div class="column fourcol">
            <div class="field-wrap">
                <?php _e('Country', 'lovestory'); ?>
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
            </div>
            </div>
            <div class="column fourcol last">
            <div class="field-wrap">
                <?php _e('City', 'lovestory'); ?>
                <input type="text" name="city" size="50" value="" />                
            </div>
            </div>
        <?php endif;?>
		<?php if(ThemexCore::checkOption('user_captcha')) { ?>
		<div class="form-captcha">
			<img src="<?php echo THEMEX_URI; ?>assets/images/captcha/captcha.php" alt="" />
			<input type="text" name="captcha" id="captcha" size="6" value="" />
		</div>
		<div class="clear"></div>
		<?php } ?>
        <div class="clear"></div>
		<a href="#" class="button submit-button"><?php _e('Register', 'lovestory'); ?></a>
		<div class="loader"></div>
		<input type="hidden" name="user_action" value="register_user" />
		<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
    <div id="upload-image-form" style="display:none;">
		<?php echo get_template_part('popup-uploadphoto');?>
    </div>
    <script type="text/javascript">
		$(document).ready(function() {
            $('#gender').change(function() {
				$('#seeking option').removeAttr('selected');
                if($(this).val()=='man')
				{					
					$('#seeking option[value=\'woman\']').attr('selected','selected');
					$('#seeking option[value=\'woman\']').select();
				}
				else
				{
					$('#seeking option[value=\'man\']').attr('selected','selected');
					$('#seeking option[value=\'man\']').select();
				}
            });
        });
	</script>
</div>
<?php } ?>
<div class="clear"></div>
</div>
<!--ACTUAL CONTENT ENDS HERE -->
<?php get_footer(); ?>