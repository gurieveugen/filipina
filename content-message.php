<li>
	<div class="listed-message">
		<header class="message-header clearfix">
			<h6 class="message-author"><a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php comment_author(); ?></a></h6>
			<time class="message-date" datetime="<?php comment_time('Y-m-d'); ?>"><?php comment_time(get_option('date_format')); ?></time>
		</header>
		<?php comment_text(); ?>
	</div>
</li>