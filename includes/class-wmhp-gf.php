<?php
/**
 * General methods of plugin.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_GF class.
 */
class WMHP_GF {

	/**
	 * Get template path.
	 *
	 * @return string
	 */
	public static function get_template_path() {
		return apply_filters( 'wmhp_price_' . __FUNCTION__, get_stylesheet_directory() . 'woocommerce/wm-hide-price/' );
	}

	/**
	 * Get default template path.
	 *
	 * @return string.
	 */
	public static function get_default_template_path() {
		return apply_filters( 'wmhp_price_' . __FUNCTION__, WMHP_PLUGIN_PATH . 'templates/' );
	}

	/**
	 * Check whether a rule is applicable for product or not.
	 *
	 * @param int        $rule_id ID of rule.
	 * @param WC_Product $product Object of WC_Product class.
	 *
	 * @return bool Return true if rule is applicable otherwise false.
	 */
	public static function is_rule_applicable( $rule_id, $product ) {

		$wmhp_enable_products = get_option( 'wmhp_enable_products', 'yes' );

		if ( 'yes' !== $wmhp_enable_products ) {
			return true;
		}

		$wmhp_all_products = get_post_meta( $rule_id, 'wmhp_all_products', true );

		if ( 'yes' === $wmhp_all_products ) {
			return true;
		}

		$rule_products = get_post_meta( $rule_id, 'wmhp_products', true );
		$rule_cat      = get_post_meta( $rule_id, 'wmhp_categories', true );
		$rule_tags     = get_post_meta( $rule_id, 'wmhp_tags', true );
		$rule_brands   = get_post_meta( $rule_id, 'wmhp_brands', true );

		if ( in_array( $product->get_id(), (array) $rule_products ) ) {
			return true;
		}

		if ( ! empty( $rule_cat ) && has_term( $rule_cat, 'product_cat', $product->get_id() ) ) {
			return true;
		}

		if ( ! empty( $rule_tags ) && has_term( $rule_tags, 'product_tag', $product->get_id() ) ) {
			return true;
		}

		if ( ! empty( $rule_brands ) && has_term( $rule_brands, 'product_brand', $product->get_id() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether a rule is applicable for user and roles.
	 *
	 * @param int $rule_id ID of rule.
	 *
	 * @return bool true if the rule is valid for user/roles otherwise false.
	 */
	public static function is_role_applicable( $rule_id ) {

		$wmhp_enable_userroles = get_option( 'wmhp_enable_userroles', 'yes' );

		if ( 'yes' !== $wmhp_enable_userroles ) {
			return true;
		}

		$users = get_post_meta( $rule_id, 'wmhp_users', true );
		$roles = get_post_meta( $rule_id, 'wmhp_users_roles', true );

		if ( empty( $users ) && empty( $roles ) ) {
			return true;
		}

		$user_roles = is_user_logged_in() ? wp_get_current_user()->roles : array( 'guest' );
		$user_id    = is_user_logged_in() ? wp_get_current_user()->ID : 0;

		if ( in_array( $user_id, (array) $users ) ) {
			return true;
		}

		if ( ! empty( $roles ) && array_intersect( $user_roles, (array) $roles ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get all product categories.
	 *
	 * @return array Array of categories ids.
	 */
	public static function get_all_product_categories() {
		$taxonomy = 'product_cat';
		$terms    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'fields'     => 'ids',
			)
		);
		return $terms;
	}

	/**
	 * Show text to replace add to cart button.
	 *
	 * @param string $replace_text Message to show.
	 */
	public static function show_text( $replace_text ) {

		$args = array(
			'replace_text' => $replace_text,
		);

		wc_get_template(
			'text/style-one.php',
			$args,
			self::get_template_path(),
			self::get_default_template_path()
		);
	}

	/**
	 * Show custom link to replace add to cart button.
	 *
	 * @param string $replace_text Message to show.
	 * @param string $replace_link Link to redirect.
	 */
	public static function show_custom_link( $replace_text, $replace_link ) {

		$args = array(
			'replace_text' => $replace_text,
			'replace_link' => $replace_link,
		);

		wc_get_template(
			'custom-link/style-one.php',
			$args,
			self::get_template_path(),
			self::get_default_template_path()
		);
	}

	/**
	 * Get all product tags.
	 *
	 * @return array Array of countries.
	 */
	public static function get_all_countries() {
		return WC()->countries->get_countries();
	}

	/**
	 * Get all product tags.
	 *
	 * @return array Array of countries.
	 */
	public static function get_all_states() {

		$states_array = array();

		foreach ( self::get_all_countries() as $country_code => $country ) {

			$states = WC()->countries->get_states( $country_code );
			if ( empty( $states ) ) {
				continue;
			} else {
				foreach ( $states as $state_code => $state ) {

					if ( ! isset( $states_array[ $country_code ] ) ) {
						$states_array[ $country_code ] = array();
					}

					$states_array[ $country_code ][ $state_code ] = $state;
				}
			}
		}

		return $states_array;
	}

	/**
	 * Get all product tags.
	 *
	 * @return array Array of tags ids.
	 */
	public static function get_all_product_tags() {

		$taxonomy = 'product_tag';
		$terms    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'fields'     => 'ids',
			)
		);
		return $terms;
	}
}
