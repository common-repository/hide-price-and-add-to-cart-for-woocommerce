<?php
/**
 * Hide price of products on single and archive pages.
 *
 * - Hide Price hooks and actions.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Price class.
 */
class WMHP_Price {

	/**
	 * Rules for hide price.
	 *
	 * @var object
	 */
	public $rules = array();

	/**
	 * Constructor of class.
	 */
	public function __construct() {

		$this->rules = new WMHP_Rules();

		add_filter( 'woocommerce_get_price_html', array( $this, 'replace_price_html' ), 100, 2 );
	}

	/**
	 * Hide price for selected rules.
	 *
	 * @param string     $price_html  HTML of product price.
	 * @param WC_Product $product     Object of product.
	 *
	 * @return string price or text to replace to the price.
	 */
	public function replace_price_html( $price_html, $product ) {

		$price_rules = $this->rules->get_price_rules();

		foreach ( $price_rules as $rule_id ) {

			if ( ! WMHP_GF::is_role_applicable( $rule_id ) ) {
				continue;
			}

			if ( ! WMHP_GF::is_rule_applicable( $rule_id, $product ) ) {
				continue;
			}

			return $this->get_rule_price_replace_text( $rule_id, $product );
		}

		return $price_html;
	}

	/**
	 * Get text to replace the price of product.
	 *
	 * @param string     $rule_id  ID of rule.
	 * @param WC_Product $product  Object of product.
	 *
	 * @return string price or text to replace to the price.
	 */
	public function get_rule_price_replace_text( $rule_id, $product ) {

		$replace_text = (string) get_post_meta( $rule_id, 'wmhp_replace_price_text', true );

		return apply_filters( 'woomanagers_replace_price_text', $replace_text, $rule_id, $product );
	}
}

new WMHP_Price();
