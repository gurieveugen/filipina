<?php
/*
* Template Name: popup-uploadphoto
*/
?>
<!-- FORM FOR UPLOADING PHOTO -->
<div class="profile-preview">
    <div class="profile-image">
        <?php  echo get_avatar(ThemexUser::$data['user']['ID'], 200); ?>
    </div>
    <div class="profile-options clearfix">
        <form action="" enctype="multipart/form-data" method="POST" class="upload-form">
            <label for="user_avatar" class="button small"><?php _e('Change Photo', 'lovestory'); ?></label>
            <input type="file" id="user_avatar" name="user_avatar" class="shifted" />
            <input type="hidden" name="user_action" value="update_avatar" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
        </form>
    </div>
</div>
<!-- END FORM FOR UPLOADING PHOTO -->
