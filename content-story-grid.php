<div class="listed-story clearfix">
	<div class="story-preview">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('normal', array('class' => 'fullwidth')); ?></a>
		<?php if($caption=ThemexCore::getPostMeta($post->ID, 'caption')) { ?>
		<div class="story-caption">
			<h6><a href="<?php the_permalink(); ?>"><?php echo $caption; ?></a></h6>
			<div class="story-corner"></div>
		</div>
		<?php } ?>
	</div>
	<?php if(is_page_template('template-stories.php')) { ?>
	<div class="story-text">
		<h4 class="story-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php echo themex_excerpt(get_the_excerpt(), get_permalink()); ?>
	</div>
	<?php } ?>
</div>	
