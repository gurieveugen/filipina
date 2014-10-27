<article <?php post_class('listed-post clearfix'); ?>>
	<?php if(has_post_thumbnail()) { ?>
	<div class="post-image column fivecol">
		<div class="bordered-image">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('normal', array('class' => 'fullwidth')); ?></a>
		</div>
	</div>
	<div class="post-content column sevencol last">
	<?php } else { ?>
	<div class="fullwidth">
	<?php } ?>
		<div class="section-title">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</div>
		<?php echo themex_excerpt(get_the_excerpt(), get_permalink()); ?>
		<footer class="post-footer">
			<?php if(!ThemexCore::checkOption('post_date')) { ?>
			<span class="icon-calendar post-icon"></span><time class="post-date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></time>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('post_author')) { ?>
			<span class="icon-pencil post-icon"></span><span class="post-author"><?php the_author_posts_link(); ?></span>
			<?php } ?>
			<?php if(comments_open()) { ?>
				<a href="<?php comments_link(); ?>">
					<span class="icon-comments-alt post-icon"></span>
					<?php comments_number('0','1','%'); ?>
				</a>
			<?php } ?>
		</footer>
	</div>
</article>