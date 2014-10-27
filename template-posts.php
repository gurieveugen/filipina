<?php
/*
Template Name: Posts
*/

get_header();

if(is_page()) {
	query_posts(array(
		'post_type' =>'post',
		'paged' => themex_paged(),
	));
}
?>
<div class="column eightcol">
	<?php if(have_posts()) { ?>
	<div class="posts-listing clearfix">
		<?php 
		while(have_posts()) {
			the_post();
			get_template_part('content', 'post');	
		}
		?>
	</div>
	<!-- /posts -->
	<?php ThemexInterface::renderPagination(themex_paged(), $wp_query->max_num_pages); ?>
	<?php } else { ?>
	<h3><?php _e('No posts found. Try a different search?','lovestory'); ?></h3>
	<p><?php _e('Sorry, no posts matched your search. Try again with different keywords.','lovestory'); ?></p>
	<?php } ?>
</div>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php get_footer(); ?>