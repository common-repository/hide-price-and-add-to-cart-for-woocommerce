<?php
/**
 * Style one to display custom link to replace add to cart button.
 *
 * - Container of custom link
 * - Button HTML
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/wm-hide-price/custom-link/style-one.php.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="wm-hide-atc-text-container">
	<a href="<?php echo esc_url( $replace_link ); ?>" class="button">
		<?php echo esc_html( $replace_text ); ?>
	</a>
</div>
