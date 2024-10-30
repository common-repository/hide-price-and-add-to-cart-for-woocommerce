<?php
/**
 * Get rules to hide price and add to cart.
 *
 * - Get all rules.
 * - Get hide price rules.
 * - Get Hide add to cart rules.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Rules class.
 */
class WMHP_Rules {

	/**
	 * Slug for hooks used in class.
	 *
	 * @var string
	 */
	private static $hook_slug;

	/**
	 * Slug for cache keys used in class.
	 *
	 * @var string
	 */
	private static $cache_slug;

	/**
	 * Group name to store cache.
	 *
	 * @var string
	 */
	private static $cache_group;

	/**
	 * Constructor of class.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init function to set initial values of data members.
	 */
	public function init() {
		self::$hook_slug   = 'wmhp_hide_price_';
		self::$cache_slug  = 'wmhp_cache_';
		self::$cache_group = 'wmhp_cache_group_rules';
	}

	/**
	 * Get all rules of hide price and add to cart.
	 *
	 * @return array
	 */
	public function get_rules() {

		$cache_key  = self::$cache_slug . 'rules';
		$cache_data = wp_cache_get( $cache_key, self::$cache_group );

		if ( ! empty( $cache_data ) ) {
			return $cache_data;
		}

		$data = get_posts(
			array(
				'post_type'   => 'wmhp_hide_price',
				'post_status' => 'publish',
				'numberposts' => -1,
				'fields'      => 'ids',
			)
		);

		wp_cache_set( $cache_key, $data, self::$cache_group );

		return apply_filters( self::$hook_slug . __FUNCTION__, $data );
	}

	/**
	 * Filter products hide price rules.
	 *
	 * @return array
	 */
	public function get_price_rules() {

		$cache_key  = self::$cache_slug . 'price_rules';
		$cache_data = wp_cache_get( $cache_key, self::$cache_group );

		if ( ! empty( $cache_data ) ) {
			return $cache_data;
		}

		$price_rules = array();

		foreach ( (array) $this->get_rules() as $rule_id ) {

			$hide_price = get_post_meta( $rule_id, 'wmhp_hide_price', true );

			if ( 'yes' === $hide_price ) {
				$price_rules[] = $rule_id;
			}
		}

		wp_cache_set( $cache_key, $price_rules, self::$cache_group );

		return apply_filters( self::$hook_slug . __FUNCTION__, $price_rules );
	}

	/**
	 * Filter replace add to cart button rules.
	 *
	 * @return array
	 */
	public function get_atc_rules() {

		$cache_key  = self::$cache_slug . 'atc_rules';
		$cache_data = wp_cache_get( $cache_key, self::$cache_group );

		if ( ! empty( $cache_data ) ) {
			return $cache_data;
		}

		$atc_rules = array();

		foreach ( (array) $this->get_rules() as $rule_id ) {

			$hide_atc = get_post_meta( $rule_id, 'wmhp_hide_atc', true );

			if ( 'yes' === $hide_atc ) {
				$atc_rules[] = $rule_id;
			}
		}

		wp_cache_set( $cache_key, $atc_rules, self::$cache_group );

		return apply_filters( self::$hook_slug . __FUNCTION__, $atc_rules );
	}
}
