<?php
/**
 * Ajax Controller of plugin.
 *
 * - Search products.
 * - Search users.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Ajax class.
 */
class WMHP_Ajax {

	/**
	 * Constructor of class.
	 */
	public function __construct() {
		add_action( 'wp_ajax_wmhp_search_users', array( $this, 'search_users' ) );
		add_action( 'wp_ajax_wmhp_search_products', array( $this, 'search_products' ) );
	}

	/**
	 * Search products.
	 */
	public function search_products() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'wp_nonce' ) ) {
			die( esc_html__( 'Unauthorized access.', 'wmhp_hide_price' ) );
		}

		$search = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$args = array(
			'post_type'   => array( 'product' ),
			'post_status' => 'publish',
			's'           => $search,
			'orderby'     => 'relevance',
			'order'       => 'ASC',
			'numberposts' => 25,
		);

		$products      = get_posts( $args );
		$products_data = array();

		if ( ! empty( $products ) ) {

			foreach ( $products as $product ) {

				$title           = $product->post_title;
				$products_data[] = array( $product->ID, $title );
			}
		}

		wp_send_json( $products_data );
	}

	/**
	 * Search users.
	 */
	public function search_users() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'wp_nonce' ) ) {
			die( esc_html__( 'Unauthorized access.', 'wmhp_hide_price' ) );
		}

		$s = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$users = new WP_User_Query(
			array(
				'search'         => '*' . esc_html( $s ) . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
				),
				'orderby'        => 'relevance',
				'order'          => 'ASC',
			)
		);

		$users_found = $users->get_results();
		$users       = array();

		if ( ! empty( $users_found ) ) {
			foreach ( $users_found as $user ) {
				$title   = $user->display_name . '(' . $user->user_email . ')';
				$users[] = array( $user->ID, $title );
			}
		}

		wp_send_json( $users );
	}
}

new WMHP_Ajax();
