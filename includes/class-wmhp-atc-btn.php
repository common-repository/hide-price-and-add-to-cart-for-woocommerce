<?php
/**
 * Functionality to remove and replace add to cart button.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Atc_Btn class.
 */
class WMHP_Atc_Btn {

	/**
	 * Active rule ID that can be used to trace which rule is applied on product.
	 *
	 * @var int
	 */
	public $active_rule_id = 0;

	/**
	 * All rules to replace add to cart button.
	 *
	 * @var array
	 */
	public $rules = array();

	/**
	 * Constructor of class.
	 */
	public function __construct() {

		$this->rules = new WMHP_Rules();

		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'replace_loop_add_to_cart' ), 100, 2 );

		add_action( 'woocommerce_init', array( $this, 'add_hooks' ) );
	}

	/**
	 * Add hooks to add actions for each type of product.
	 */
	public function add_hooks() {

		foreach ( wc_get_product_types() as $product_type => $type_name ) {

			$action   = 'woocommerce_' . $product_type . '_add_to_cart';
			$callback = 'replace_single_add_to_cart';

			add_action( $action, array( $this, $callback ), 5, 1 );
		}
	}

	/**
	 * Remove and add hooks to replace the add to cart button
	 */
	public function replace_single_add_to_cart() {

		global $product;

		$hook     = 'woocommerce_' . $product->get_type() . '_add_to_cart';
		$action   = 'woocommerce_' . $product->get_type() . '_add_to_cart';
		$priority = 30;

		if ( $product->is_type( 'variable' ) ) {
			$hook     = 'woocommerce_single_variation';
			$action   = 'woocommerce_single_variation_add_to_cart_button';
			$priority = '20';
		}

		if ( $product->is_type( 'subscription' ) ) {
			$action = 'WC_Subscriptions::subscription_add_to_cart';
		}

		$rules = $this->rules->get_atc_rules();

		foreach ( $rules as $rule_id ) {

			if ( ! WMHP_GF::is_role_applicable( $rule_id ) ) {
				continue;
			}

			if ( ! WMHP_GF::is_rule_applicable( $rule_id, $product ) ) {
				continue;
			}

			remove_action( $hook, $action, $priority );
			$this->active_rule_id = $rule_id;
			add_action( $hook, array( $this, 'hp_single_add_to_cart' ) );
			return;
		}
	}

	/**
	 * Hide single add to cart button and print text.
	 */
	public function hp_single_add_to_cart() {

		global $product;

		if ( empty( $rule_id ) || 'wmhp_hide_price' !== get_post_type( $rule_id ) ) {
			$rule_id = $this->active_rule_id;
		}

		$atc_options = (string) get_post_meta( $rule_id, 'wmhp_hide_atc_options', true );

		if ( 'text' == $atc_options ) {

			echo wp_kses_post( $this->get_rule_atc_replace_text( $rule_id, $product ) );

		} elseif ( 'link' == $atc_options ) {

			$replace_text = $this->get_rule_atc_replace_text( $rule_id, $product );
			$replace_link = (string) get_post_meta( $rule_id, 'wmhp_replace_atc_link', true );
			WMHP_GF::show_custom_link( $replace_text, $replace_link );

		}
	}

	/**
	 * Replace loop add to cart button.
	 *
	 * @param string     $link Link of add to cart button.
	 * @param WC_Product $product Object of WC_Product class.
	 *
	 * @return string
	 */
	public function replace_loop_add_to_cart( $link, $product ) {

		$rules = $this->rules->get_atc_rules();

		foreach ( $rules as $rule_id ) {

			if ( ! WMHP_GF::is_role_applicable( $rule_id ) ) {
				continue;
			}

			if ( ! WMHP_GF::is_rule_applicable( $rule_id, $product ) ) {
				continue;
			}

			$atc_options = (string) get_post_meta( $rule_id, 'wmhp_hide_atc_options', true );

			if ( 'text' == $atc_options ) {

				return $this->get_rule_atc_replace_text( $rule_id, $product );

			} elseif ( 'link' == $atc_options ) {

				$replace_text = $this->get_rule_atc_replace_text( $rule_id, $product );
				$replace_link = (string) get_post_meta( $rule_id, 'wmhp_replace_atc_link', true );

				ob_start();
				WMHP_GF::show_custom_link( $replace_text, $replace_link );
				return ob_get_clean();

			}

			return $this->get_rule_atc_replace_text( $rule_id, $product );
		}

		return $link;
	}

	/**
	 * Get text to replace the add to cart button.
	 *
	 * @param int        $rule_id ID of rule.
	 * @param WC_Product $product Object of product.
	 *
	 * @return string Text to replace the add to cart button.
	 */
	public function get_rule_atc_replace_text( $rule_id, $product ) {

		$replace_text = (string) get_post_meta( $rule_id, 'wmhp_replace_atc_text', true );

		return apply_filters( 'woomanagers_replace_add_to_cart_text', $replace_text, $rule_id, $product );
	}
}

// Instant fo class to activate the class hooks.
new WMHP_Atc_Btn();
