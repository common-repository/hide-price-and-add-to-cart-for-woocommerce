<?php
/**
 * Handle admin configurations, meta-boxes and save post meta of CPTs.
 *
 * - Enqueue admin scripts.
 * - Add meta boxes.
 * - Save meta of custom post type meta-boxes.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Admin class.
 */
class WMHP_Admin {

	/**
	 * Meta keys to save data.
	 *
	 * @var array
	 */
	private $meta_keys = array();

	/**
	 * Constructor of class.
	 */
	public function __construct() {

		$this->init();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_wmhp_hide_price', array( $this, 'save_meta' ), 100, 2 );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'wmhp_admin_style', WMHP_PLUGIN_URL . 'assets/css/admin.css', array(), WMHP_PLUGIN_VERSION );
		wp_enqueue_script( 'wmhp_admin_script', WMHP_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), WMHP_PLUGIN_VERSION, true );

		$data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'wp_nonce' ),
		);
		wp_localize_script( 'wmhp_admin_script', 'wmhp_vars', $data );

		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			wp_enqueue_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ), array(), '5.7.2' );
			wp_enqueue_script( 'select2', plugins_url( 'assets/js/select2/select2.min.js', WC_PLUGIN_FILE ), array( 'jquery' ), '4.0.3', true );
		}
	}

	/**
	 * Save meta of hide price rules.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Object of WP_Post class.
	 */
	public function save_meta( $post_id, $post = false ) {

		global $wp_object_cache;

		if ( isset( $wp_object_cache->group_ops['wmhp_cache_group_rules'] ) ) {
			$wp_object_cache->group_ops['wmhp_cache_group_rules'] = array();
		}

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

		// Don't save meta boxes for revisions or autosaves.
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['woomangers_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woomangers_meta_nonce'] ) ), 'woomangers_save_data' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		foreach ( $this->meta_keys as $meta_key => $type ) {

			if ( 'array' == $type ) {

				$post_data = isset( $_POST[ $meta_key ] ) ? sanitize_meta( '', $_POST[ $meta_key ], '' ) : array();
				update_post_meta( $post_id, $meta_key, $post_data );

			} elseif ( 'checkbox' == $type ) {

				$post_data = isset( $_POST[ $meta_key ] ) ? sanitize_text_field( $_POST[ $meta_key ] ) : '';
				update_post_meta( $post_id, $meta_key, $post_data );

			} elseif ( isset( $_POST[ $meta_key ] ) ) {

				$post_data = sanitize_text_field( $_POST[ $meta_key ] );
				update_post_meta( $post_id, $meta_key, $post_data );
			}
		}
	}

	/**
	 * Initiate the class data members.
	 */
	public function init() {

		$this->meta_keys = array(
			'wmhp_users'              => 'array',
			'wmhp_users_roles'        => 'array',
			'wmhp_all_products'       => 'checkbox',
			'wmhp_products'           => 'array',
			'wmhp_categories'         => 'array',
			'wmhp_tags'               => 'array',
			'wmhp_hide_atc_options'   => 'string',
			'wmhp_replace_atc_link'   => 'string',
			'wmhp_hide_price'         => 'checkbox',
			'wmhp_replace_price_text' => 'string',
			'wmhp_hide_atc'           => 'checkbox',
			'wmhp_replace_atc_text'   => 'string',
		);
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes() {

		add_meta_box(
			'wmhp_hide_price',
			__( 'Hide price & Add to cart', 'wmhp_hide_price' ),
			array( $this, 'add_hide_price_metabox' ),
			'wmhp_hide_price',
			'advanced',
			'high'
		);

		$wmhp_user_roles = get_option( 'wmhp_enable_userroles', 'yes' );

		if ( 'yes' == $wmhp_user_roles ) {
			add_meta_box(
				'wmhp_user_roles',
				__( 'Users & Roles', 'wmhp_hide_price' ),
				array( $this, 'add_user_role_metabox' ),
				'wmhp_hide_price',
				'advanced',
				'high'
			);
		}

		$wmhp_enable_products = get_option( 'wmhp_enable_products', 'yes' );

		if ( 'yes' == $wmhp_enable_products ) {
			add_meta_box(
				'wmhp_products',
				__( 'Products & Taxanomies', 'wmhp_hide_price' ),
				array( $this, 'add_products_metabox' ),
				'wmhp_hide_price',
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Add users and roles metabox html.
	 */
	public function add_user_role_metabox() {
		include_once WMHP_PLUGIN_PATH . 'includes/admin/meta-boxes/user-roles.php';
	}

	/**
	 * Add products, categories and brands metabox html.
	 */
	public function add_products_metabox() {
		include_once WMHP_PLUGIN_PATH . 'includes/admin/meta-boxes/products.php';
	}

	/**
	 * Add options for hide price and add to cart button.
	 */
	public function add_hide_price_metabox() {
		include_once WMHP_PLUGIN_PATH . 'includes/admin/meta-boxes/hide-price.php';
	}
}

new WMHP_Admin();
