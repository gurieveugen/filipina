<?php
/*
Template Name: Stories
*/

get_header();

$args=array(
	'post_type' =>'story',
	'posts_per_page' => ThemexCore::getOption('story_per_page', 9),
	'paged' => themex_paged(),
);

if(get_query_var('story_category')) {
	$args['tax_query']=array(array(
		'taxonomy' => 'story_category',
		'field' => 'slug',
		'terms' => get_query_var('story_category'),				
	));
}

$query=new WP_Query($args);
?>
<div class="stories-listing clearfix">
	<?php 
	$counter=0;
	while($query->have_posts()) {
	$query->the_post(); 
	$counter++;
	?>
		<?php if(has_post_thumbnail()) { ?>
			<div class="column fourcol <?php if($counter==3) { ?>last<?php } ?>">
			<?php get_template_part('content', 'story-grid'); ?>
			</div>
			<?php		
			if($counter==3) {
			$counter=0;
			?>
			<div class="clear"></div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>
<!-- /stories -->
<?php ThemexInterface::renderPagination(themex_paged(), $query->max_num_pages); ?>
<?php get_footer(); ?>