<aside class="sidebar fourcol column last">
<?php 
if(ThemexUser::checkAccess(ThemexUser::$data['user']['ID'], ThemexUser::$data['active_user']['ID'], 'favorites')) {
	get_template_part('module', 'favorites');
}

if(ThemexUser::checkAccess(ThemexUser::$data['user']['ID'], ThemexUser::$data['active_user']['ID'], 'photos')) {
	get_template_part('module', 'photos');
} 

if(ThemexUser::checkAccess(ThemexUser::$data['user']['ID'], ThemexUser::$data['active_user']['ID'], 'gifts') && !ThemexCore::checkOption('user_gifts')) {
	get_template_part('module', 'gifts');
}

if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('profile_right'));
?>
</aside>