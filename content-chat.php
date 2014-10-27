<li class="clearfix">
	<div class="chat-message">
		<div class="chat-message-meta">
			<h5 class="chat-name">
				<a href="<?php echo get_author_posts_url($GLOBALS['chat']['author']); ?>">
				<?php
				$name=get_user_meta($GLOBALS['chat']['author'], 'first_name', true);
				if(ThemexCore::checkOption('user_name') || empty($name)) {
					echo get_user_meta($GLOBALS['chat']['author'], 'nickname', true); 
				} else {
					echo $name; 
				}
				?>
				</a>
			</h5>
			<time class="chat-time"><?php echo date('H:i:s', $GLOBALS['chat']['time']); ?></time>
		</div>
		<div class="chat-message-text">
			<p><?php echo themex_stripslashes($GLOBALS['chat']['content']); ?></p>
		</div>
	</div>									
</li>