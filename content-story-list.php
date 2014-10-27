<article class="featured-story">
	<div class="story-wrap">
	<?php get_template_part('content', 'story-grid'); ?>
	</div>							
	<div class="story-text">
		<h4 class="story-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php echo $GLOBALS['content']; ?>
	</div>
</article>