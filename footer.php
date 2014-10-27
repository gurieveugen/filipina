			</section>
			<!-- /site content -->
			<footer class="site-footer container">
				<div class="site-copyright static-column fourcol">
					<?php echo ThemexCore::getOption('copyright', 'LoveStory Theme &copy; 2013'); ?>
				</div>
				<nav class="footer-menu static-column eightcol last">
					<?php wp_nav_menu( array( 'theme_location' => 'footer_menu' ) ); ?>
				</nav>
			</footer>
			<!-- /footer -->
		</div>
	</div>
	<!-- /wrap -->
	<?php if(is_user_logged_in()) { ?>
	<form class="ajax-form status-form hidden" action="<?php echo AJAX_URL; ?>" method="POST">
		<div class="message popup"></div>
		<input type="hidden" name="user_action" value="update_status" />
		<input type="hidden" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />	
	</form>
	<?php } ?>	
	<?php wp_footer(); ?>
</body>
</html>