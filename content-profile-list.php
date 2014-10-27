<?php ThemexUser::$data['active_user']=ThemexUser::getUser($GLOBALS['user']->ID); ?>
<article class="featured-profile">
	<div class="profile-wrap">
		<?php get_template_part('content', 'profile-grid'); ?>		
	</div>
	<div class="profile-text">
		<h4><?php get_template_part('module', 'status'); ?><a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>"><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></a></h4>
		<?php echo themex_sections(wpautop(ThemexUser::$data['active_user']['profile']['description']), 1); ?>
	</div>
</article>