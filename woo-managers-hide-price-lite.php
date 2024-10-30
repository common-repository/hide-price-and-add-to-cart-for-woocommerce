<?php
/**
 * Plugin Name: Hide price and add to cart Lite
 * Plugin URI: https://woomanagers.com/
 * Description: Hide price and add to cart for WooCommerce Products.
 * Version: 1.0.0
 * Author: WooManagers
 * Author URI: https://woomanagers.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wmhp_hide_price
 * Requires Plugins: woocommerce
 * Domain Path: /languages/
 * Requires at least: 5.9
 * Requires PHP: 7.2
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WMHP_PLUGIN_FILE' ) ) {
	define( 'WMHP_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'WMHP_Hide_Price' ) ) {
	include_once plugin_dir_path( WMHP_PLUGIN_FILE ) . 'includes/class-wmhp-hide-price.php';
}
