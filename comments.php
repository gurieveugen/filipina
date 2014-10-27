<?php if(!post_password_required() && (have_comments() || comments_open())) { ?>
<div id="comments" class="clearfix">
	<div class="section-title">
		<h2><?php _e('Comments', 'lovestory'); ?></h2>
	</div>
	<div class="comments-listing">
		<ul>
			<?php
			wp_list_comments(array(
				'avatar_size' => 100,
				'callback' => array('ThemexInterface', 'renderComment'),
			));
			?>
		</ul>
	</div>
	<div class="pagination comment-pagination">
	<?php paginate_comments_links(array('prev_text' => '', 'next_text' => '')); ?>
	</div>
	<?php if(comments_open()) { ?>
	<div class="comment-form formatted-form ">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<?php } ?>