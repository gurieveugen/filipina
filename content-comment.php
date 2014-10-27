<li id="comment-<?php echo $comment->comment_ID; ?>">
	<div class="comment clearfix">
		<div class="avatar-container">
			<div class="bordered-image">
				<a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php echo get_avatar($comment); ?></a>
			</div>
		</div>
		<div class="comment-text">
			<header class="comment-header clearfix">
				<h6 class="comment-author"><a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php comment_author(); ?></a></h6>
				<time class="comment-date" datetime="<?php comment_time('Y-m-d'); ?>"><?php comment_time(get_option('date_format').' '.get_option('time_format')); ?></time>
				<?php 
				comment_reply_link(array(
					'reply_text' => '<span class="icon-repeat"></span>'.__('Reply', 'lovestory'),
					'depth' => $GLOBALS['depth'], 
					'max_depth' => get_option('thread_comments_depth'),
				)); 
				?>
			</header>
			<?php comment_text(); ?>
		</div>
	</div>