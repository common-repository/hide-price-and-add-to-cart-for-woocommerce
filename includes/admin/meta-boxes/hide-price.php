<?php
/**
 * WooManager Hide price and add to cart options.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

global $post;

$rule_id = $post->ID;

$wmhp_hide_price         = (string) get_post_meta( $rule_id, 'wmhp_hide_price', true );
$wmhp_replace_price_text = (string) get_post_meta( $rule_id, 'wmhp_replace_price_text', true );
$wmhp_hide_atc           = (string) get_post_meta( $rule_id, 'wmhp_hide_atc', true );
$wmhp_replace_atc_text   = (string) get_post_meta( $rule_id, 'wmhp_replace_atc_text', true );
$wmhp_hide_atc_options   = (string) get_post_meta( $rule_id, 'wmhp_hide_atc_options', true );
$wmhp_replace_atc_link   = (string) get_post_meta( $rule_id, 'wmhp_replace_atc_link', true );
?>

<div class="wp-metabox metabox wmhp_metabox">
	<?php wp_nonce_field( 'woomangers_save_data', 'woomangers_meta_nonce' ); ?>
	<table class="wmhp_metabox_table">
		<tr>
			<th>
				<?php esc_html_e( 'Hide Price?', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="checkbox" name="wmhp_hide_price" id="wmhp_hide_price" value="yes" <?php echo checked( 'yes', $wmhp_hide_price ); ?>>
				<?php wc_help_tip( 'For selected users and roles, hide prices of selected products and taxonomies.', true ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php esc_html_e( 'Replace text with price', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="text" name="wmhp_replace_price_text" id="wmhp_replace_price_text" value="<?php echo esc_attr( $wmhp_replace_price_text ); ?>">
				<?php wc_help_tip( 'Text to replace the prices. ex. "Ask for Price".', true ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php esc_html_e( 'Hide Add to cart?', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="checkbox" name="wmhp_hide_atc" id="wmhp_hide_atc" value="yes" <?php echo checked( 'yes', $wmhp_hide_atc ); ?>>
				<?php wc_help_tip( 'For selected users and roles, hide prices of selected products and taxonomies.', true ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php esc_html_e( 'Replace add to cart options', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="radio" name="wmhp_hide_atc_options" id="wmhp_hide_atc_options_1" value="text" <?php echo checked( 'text', $wmhp_hide_atc_options ); ?>>
				<?php esc_html_e( 'Replace with Text', 'wmhp_hide_price' ); ?>
				<br/>
				<input type="radio" name="wmhp_hide_atc_options" id="wmhp_hide_atc_options_2" value="link" <?php echo checked( 'link', $wmhp_hide_atc_options ); ?>>
				<?php esc_html_e( 'Replace with custom link button', 'wmhp_hide_price' ); ?>
				<br/>
			</td>
		</tr>
		<tr class="wmhp_atc_replace_text">
			<th>
				<?php esc_html_e( 'Text/Button Text', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="text" name="wmhp_replace_atc_text" id="wmhp_replace_atc_text" value="<?php echo esc_attr( $wmhp_replace_atc_text ); ?>" >
				<?php wc_help_tip( 'Text to replace the add to cart. ex. "Request a Quote".', true ); ?>
			</td>
		</tr>
		<tr class="wmhp_atc_custom_link">
			<th>
				<?php esc_html_e( 'Custom link', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="url" name="wmhp_replace_atc_link" id="wmhp_replace_atc_link" value="<?php echo esc_attr( $wmhp_replace_atc_link ); ?>" >
				<?php wc_help_tip( 'Custom url of button.', true ); ?>
			</td>
		</tr>
	</table>
</div>
