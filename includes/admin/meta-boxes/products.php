<?php
/**
 * WooManager Hide price for products.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

global $post;

$rule_id = $post->ID;

$wmhp_all_products = get_post_meta( $rule_id, 'wmhp_all_products', true );
$wmhp_products     = (array) get_post_meta( $rule_id, 'wmhp_products', true );
$wmhp_categories   = (array) get_post_meta( $rule_id, 'wmhp_categories', true );
$wmhp_tags         = (array) get_post_meta( $rule_id, 'wmhp_tags', true );

$all_categories = WMHP_GF::get_all_product_categories();
$all_tags       = WMHP_GF::get_all_product_tags();

?>
<div class="wp-metabox metabox wmhp_metabox">
	<table class="wmhp_metabox_table">
		<tr >
			<th>
				<?php esc_html_e( 'All products', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<input type="checkbox" name="wmhp_all_products" id="wmhp_all_products" value="yes" <?php echo checked( 'yes', $wmhp_all_products ); ?>>
				<?php wc_help_tip( 'Apply this rule on all products.', true ); ?>
			</td>
		</tr>
		<tr class="wmhp_products_tr">
			<th>
				<?php esc_html_e( 'Select Products', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<select class="wmhp_products" name="wmhp_products[]" id="wmhp_products[]" multiple>

					<?php
					foreach ( $wmhp_products as $product_id ) :

						$product = wc_get_product( $product_id );

						if ( ! $product ) {
							continue;
						}
						?>
						<option value="<?php echo intval( $product->get_id() ); ?>" selected><?php echo esc_attr( $product->get_name() ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php wc_help_tip( 'Select products for rule.', true ); ?>
			</td>
		</tr>
		<tr class="wmhp_cats_tr">
			<th>
				<?php esc_html_e( 'Select Categories', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<select class="wmhp_select2" name="wmhp_categories[]" id="wmhp_categories[]" multiple>
					<?php
					foreach ( $all_categories as $category_id ) :
						$category = get_term_by( 'id', $category_id, 'product_cat' );

						if ( ! is_a( $category, 'WP_Term' ) ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $category_id ); ?>" <?php echo in_array( $category_id, $wmhp_categories ) ? 'selected' : ''; ?>><?php echo esc_attr( $category->name ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php wc_help_tip( 'Select the user roles that apply to this rule.', true ); ?>
			</td>
		</tr>
		<tr class="wmhp_tags_tr">
			<th>
				<?php esc_html_e( 'Select Tags', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<select class="wmhp_select2" name="wmhp_tags" id="wmhp_tags" multiple>
					<?php
					foreach ( $all_tags as $tag_id ) :
						$hp_tag = get_term_by( 'id', $tag_id, 'product_tag' );

						if ( ! is_a( $hp_tag, 'WP_Term' ) ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $tag_id ); ?>" <?php echo in_array( $tag_id, $wmhp_tags ) ? 'selected' : ''; ?>><?php echo esc_attr( $hp_tag->name ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php wc_help_tip( 'Select user roles that do not apply to this rule.', true ); ?>
			</td>
		</tr>
	</table>
</div>
