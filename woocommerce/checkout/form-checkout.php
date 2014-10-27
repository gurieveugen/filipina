<?php
if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce;
$product=reset($woocommerce->cart->get_cart());
$related=ThemexWoo::getRelatedPost($product['product_id'], 'membership');

if(!empty($related)) {
$get_checkout_url = apply_filters('woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url());
wc_print_notices();
do_action('woocommerce_before_checkout_form', $checkout);

$query=new WP_Query(array(
	'post__in' => array($related->ID), 
	'post_type' => 'membership',
));
?>
<form name="checkout" method="post" class="checkout membership-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
	<div class="column fourcol">
		<div class="toggles-container accordion-container">
			<?php $query->the_post(); ?>
			<div class="toggle-container">
				<div class="toggle-title clearfix">
					<div class="left sevencol">
						<h3><?php the_title(); ?></h3>
					</div>
					<div class="right fivecol membership-price">
						<?php echo ThemexWoo::getPrice(ThemexCore::getPostMeta($related->ID, 'product'), ThemexCore::getPostMeta($related->ID, 'period')); ?>
					</div>
				</div>
				<div class="toggle-content">
					<?php the_content(); ?>						
				</div>
			</div>
		</div>
	</div>
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>		
		<div class="column fourcol">
			<h3><?php _e('Billing Details', 'lovestory'); ?></h3>
			<div class="billing-details">
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<?php do_action('woocommerce_before_order_notes', $checkout); ?>
				<?php do_action('woocommerce_after_order_notes', $checkout); ?>
				<?php if (woocommerce_get_page_id('terms')>0) : ?>
				<p class="form-row terms">
					<input type="checkbox" class="input-checkbox" name="terms" <?php if (isset($_POST['terms'])) echo 'checked="checked"'; ?> id="terms" />
					<label for="terms" class="checkbox"><?php _e('I accept the', 'lovestory'); ?> <a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e('terms &amp; conditions', 'lovestory'); ?></a></label>
				</p>
				<?php endif; ?>
				<input id="shiptobilling-checkbox" type="hidden" name="shiptobilling" value="1" />
			</div>			
		</div>
	<?php endif; ?>
	<div class="fourcol column last">
		<h3><?php _e('Payment Methods', 'lovestory'); ?></h3>
		<div class="membership-checkout">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>		
	</div>	
</form>
<?php 
do_action('woocommerce_after_checkout_form', $checkout);
} else if(file_exists(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php')) {
	include(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php');
}