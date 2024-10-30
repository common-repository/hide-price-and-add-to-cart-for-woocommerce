<?php
/**
 * Main class of plugin to initiate the plugin.
 *
 * - Includes files.
 * - Define constants.
 * - Register post types.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

/**
 * WMHP_Hide_Price class.
 */
class WMHP_Hide_Price {

	/**
	 * Constructor of class.
	 */
	public function __construct() {

		$this->init();
		$this->include_files();

		add_action( 'init', array( $this, 'check_woocommerce_active' ), 5 );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'wp_loaded', array( $this, 'load_text_domain' ) );
	}

	/**
	 * Register Text domain.
	 */
	public function load_text_domain() {
		if ( function_exists( 'load_plugin_textdomain' ) ) {
			load_plugin_textdomain( 'wmhp_hide_price', false, WMHP_PLUGIN_PATH . '/languages' );
		}
	}

	/**
	 * Check the installation of WooCommerce plugin.
	 */
	public function check_woocommerce_active() {

		if ( ! in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true ) ) {
			$this->deactivate_plugin();
			add_action( 'admin_notices', array( $this, 'plugin_deactived_notice' ) );
		}
	}

	/**
	 * Deactivate the plugin.
	 */
	public function deactivate_plugin() {

		if ( ! is_multisite() ) {

			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			deactivate_plugins( WMHP_PLUGIN_FILE );
		}
	}

	/**
	 * Display notice of deactivating the plugin.
	 */
	public function plugin_deactived_notice() {
		?>
		<div id="message" class="error">
			<p>
				<strong>
					WooCommerce Hide price and add to cart
				</strong>
				<?php esc_html_e( 'plugin is currently deactivated. The WooCommerce plugin is required for this particular plugin to function', 'wmhp_hide_price' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Define constant of plugin.
	 */
	public function init() {

		if ( ! defined( 'WMHP_PLUGIN_PATH' ) ) {
			define( 'WMHP_PLUGIN_PATH', plugin_dir_path( WMHP_PLUGIN_FILE ) );
		}

		if ( ! defined( 'WMHP_PLUGIN_URL' ) ) {
			define( 'WMHP_PLUGIN_URL', plugin_dir_url( WMHP_PLUGIN_FILE ) );
		}

		if ( ! defined( 'WMHP_PLUGIN_VERSION' ) ) {
			define( 'WMHP_PLUGIN_VERSION', '1.0.0' );
		}
	}

	/**
	 * Include files of plugin.
	 */
	private function include_files() {

		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-gf.php';
		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-ajax.php';

		if ( is_admin() ) {
			include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-admin.php';
		}

		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-rules.php';
		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-price.php';
		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-atc-btn.php';
		include_once WMHP_PLUGIN_PATH . 'includes/class-wmhp-rules.php';
	}

	/**
	 * Register post types of plugin.
	 */
	public function register_post_types() {

		register_post_type(
			'wmhp_hide_price',
			apply_filters(
				'wmhp_hide_price_register_post_type',
				array(
					'labels'              => array(
						'name'                  => __( 'Hide Price Rules', 'wmhp_hide_price' ),
						'singular_name'         => __( 'Hide Price Rule', 'wmhp_hide_price' ),
						'all_items'             => __( 'Hide Price Rules', 'wmhp_hide_price' ),
						'menu_name'             => _x( 'Hide Price Rules', 'Admin menu name', 'wmhp_hide_price' ),
						'add_new'               => __( 'Add New', 'wmhp_hide_price' ),
						'add_new_item'          => __( 'Add new Rule', 'wmhp_hide_price' ),
						'edit'                  => __( 'Edit', 'wmhp_hide_price' ),
						'edit_item'             => __( 'Edit Rule', 'wmhp_hide_price' ),
						'new_item'              => __( 'New Rule', 'wmhp_hide_price' ),
						'view_item'             => __( 'View Rule', 'wmhp_hide_price' ),
						'view_items'            => __( 'View Rules', 'wmhp_hide_price' ),
						'search_items'          => __( 'Search Rules', 'wmhp_hide_price' ),
						'not_found'             => __( 'No Rules found', 'wmhp_hide_price' ),
						'not_found_in_trash'    => __( 'No Rules found in trash', 'wmhp_hide_price' ),
						'parent'                => __( 'Parent Rule', 'wmhp_hide_price' ),
						'featured_image'        => __( 'Rule image', 'wmhp_hide_price' ),
						'set_featured_image'    => __( 'Set Rule image', 'wmhp_hide_price' ),
						'remove_featured_image' => __( 'Remove Rule image', 'wmhp_hide_price' ),
						'use_featured_image'    => __( 'Use as Rule image', 'wmhp_hide_price' ),
						'insert_into_item'      => __( 'Insert into Rule', 'wmhp_hide_price' ),
						'uploaded_to_this_item' => __( 'Uploaded to this Rule', 'wmhp_hide_price' ),
						'filter_items_list'     => __( 'Filter Rules', 'wmhp_hide_price' ),
						'items_list_navigation' => __( 'Rules navigation', 'wmhp_hide_price' ),
						'items_list'            => __( 'Rules list', 'wmhp_hide_price' ),
						'item_link'             => __( 'Rule Link', 'wmhp_hide_price' ),
						'item_link_description' => __( 'A link to a Rule.', 'wmhp_hide_price' ),
					),
					'description'         => __( 'This is where you can add hide price rules in this store.', 'wmhp_hide_price' ),
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => 'woocommerce',
					'menu_icon'           => 'dashicons-archive',
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => true,
					'supports'            => array( 'title', 'excerpt', 'thumbnail' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
				)
			)
		);
	}
}

new WMHP_Hide_Price();
