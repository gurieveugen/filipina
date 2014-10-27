<?php get_header(); ?>
<?php the_post(); ?>
<article class="full-story">
	<?php if(has_post_thumbnail()) { ?>
	<div class="fourcol column">
		<?php get_template_part('content', 'story-grid'); ?>
	</div>
	<div class="eightcol column last">
	<?php } else { ?>
	<div class="fullwidth">
	<?php } ?>
		<div class="section-title">
			<h1><?php the_title(); ?></h1>
		</div>
		<?php the_content(); ?>
	</div>
</article>
<?php get_footer(); ?>