<?php
/**
 * Style one to display text to replace add to cart button.
 *
 * - Container of text
 * - Text HTML
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/wm-hide-price/text/style-one.php.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wm-hide-atc-text-container">
	<p class="wm-hide-atc-text">
		<?php echo wp_kses_post( $replace_text ); ?>
	</p>
</div>
