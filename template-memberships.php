<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="full-profile fivecol column">
	<div class="section-title">
		<h2><?php _e('My Membership', 'lovestory'); ?></h2>
	</div>
	<table class="profile-fields">
		<tbody>
			<tr>
				<th><?php _e('Membership', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['user']['membership']['name']; ?></td>
			</tr>
			<tr>
				<th><?php _e('Expiration Date', 'lovestory'); ?></th>
				<td><?php echo date_i18n(get_option('date_format'), ThemexUser::$data['user']['membership']['date']); ?></td>
			</tr>
			<?php if(!ThemexCore::checkOption('user_chat')) { ?>
			<tr>
				<th><?php _e('Live Chat', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['user']['membership']['chat']?__('Enabled', 'lovestory'):__('Disabled', 'lovestory'); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<th><?php _e('Messages to Send', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['user']['membership']['messages']; ?></td>
			</tr>
			<tr>
				<th><?php _e('Photos to Upload', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['user']['membership']['photos']; ?></td>
			</tr>
			<?php if(!ThemexCore::checkOption('user_gifts')) { ?>
			<tr>
				<th><?php _e('Gifts to Send', 'lovestory'); ?></th>
				<td><?php echo ThemexUser::$data['user']['membership']['gifts']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php 
	$query=new WP_Query(array(
		'post_type' =>'membership',
		'showposts' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	));
	
	if($query->have_posts()) {
	?>
	<div class="toggles-container accordion-container check-listing">
		<?php
		while($query->have_posts()) {
		$query->the_post(); 
		?>
		<div class="toggle-container">
			<div class="toggle-title clearfix">
				<div class="static-column sixcol">
					<h3><?php the_title(); ?></h3>
				</div>
				<div class="static-column sixcol last membership-price">
					<?php echo ThemexWoo::getPrice(ThemexCore::getPostMeta($post->ID, 'product'), ThemexCore::getPostMeta($post->ID, 'period')); ?>
				</div>
			</div>
			<div class="toggle-content">
				<?php the_content(); ?>
				<footer class="membership-footer">
					<form action="" method="POST">
						<a href="#" class="submit-button button small"><?php _e('Sign Up Now', 'lovestory'); ?></a>
						<input type="hidden" name="user_membership" value="<?php echo $post->ID; ?>" />
						<input type="hidden" name="user_action" value="add_membership" />
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
					</form>					
				</footer>								
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>