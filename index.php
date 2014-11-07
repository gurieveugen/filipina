<?php 
get_header();

if(ThemexUser::isUserFilter()) {
	get_template_part('template', 'profiles');
} else if(get_query_var('story_category')) {
	get_template_part('template', 'stories');
} else {
	get_template_part('template', 'posts');
}

get_footer(); 
?>