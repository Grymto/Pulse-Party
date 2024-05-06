<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/pofily-woo-product-filters/
 * @since      1.0.0
 *
 * @package    pofily-woo-product-filters
 * @subpackage pofily-woo-product-filters/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    pofily-woo-product-filters
 * @subpackage pofily-woo-product-filters/admin
 * @author     Villatheme <support@villatheme.com>
 */
class VIWCPF_Woo_Product_Filters_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $viwcpf_woo_product_filters The ID of this plugin.
	 */

	private $viwcpf_woo_product_filters;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $default_data;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $viwcpf_woo_product_filters The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $viwcpf_woo_product_filters, $version ) {

		$this->viwcpf_woo_product_filters = $viwcpf_woo_product_filters;
		$this->version                    = $version;

		$this->default_data = new VIWCPF_Woo_Product_Filters_Data();
	}

	/**
	 * Function is_edit_page()
	 * function to check if the current page is a post edit page
	 *
	 * @param string $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
	 *
	 * @return boolean
	 * @author Ohad Raz <admin@bainternet.info>
	 *
	 */
	public function is_edit_page( $new_edit = null ) {
		global $pagenow;
		//make sure we are on the backend
		if ( ! is_admin() ) {
			return false;
		}


		if ( $new_edit == "edit" ) {
			return in_array( $pagenow, array( 'post.php', ) );
		} elseif ( $new_edit == "new" ) //check for new post page
		{
			return in_array( $pagenow, array( 'post-new.php' ) );
		} else //check for either new or edit
		{
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Product_Filters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Product_Filters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $pagenow;
		$current_screen = get_current_screen()->id;

		if ( ( $current_screen == 'viwcpf_filter_block' ) || ( $current_screen == 'edit-viwcpf_filter_block' ) || ( $current_screen == 'viwcpf_filter_menu' ) || ( $current_screen == 'edit-viwcpf_filter_menu' ) || ( $current_screen == 'toplevel_page_viwcpf-woocommerce-product-filters' ) || ( $current_screen == 'pofily_page_viwcpf-woocommerce-product-filters-settings' ) ) {


			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-grid', VIWCPF_FREE_DIR_URL . 'assets/css/grid.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-button', VIWCPF_FREE_DIR_URL . 'assets/css/button.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-checkbox', VIWCPF_FREE_DIR_URL . 'assets/css/checkbox.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-form', VIWCPF_FREE_DIR_URL . 'assets/css/form.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-icon', VIWCPF_FREE_DIR_URL . 'assets/css/icon.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-dropdown', VIWCPF_FREE_DIR_URL . 'assets/css/dropdown.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-input', VIWCPF_FREE_DIR_URL . 'assets/css/input.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-tab', VIWCPF_FREE_DIR_URL . 'assets/css/tab.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-table', VIWCPF_FREE_DIR_URL . 'assets/css/table.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-label', VIWCPF_FREE_DIR_URL . 'assets/css/label.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-segment', VIWCPF_FREE_DIR_URL . 'assets/css/segment.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-transition', VIWCPF_FREE_DIR_URL . 'assets/css/transition.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-dimmer', VIWCPF_FREE_DIR_URL . 'assets/css/dimmer.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-modal', VIWCPF_FREE_DIR_URL . 'assets/css/modal.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-loading', VIWCPF_FREE_DIR_URL . 'assets/css/loading.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-accordion', VIWCPF_FREE_DIR_URL . 'assets/css/accordion.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-select2', VIWCPF_FREE_DIR_URL . 'assets/css/select2.min.css', array(), $this->version, 'all' );
			/* wp_enqueue_style( $this->viwcpf_woo_product_filters.'-table',     VIWCPF_FREE_DIR_URL . 'admin/css/dataTables.semanticui.min.css', array(), $this->version, 'all' );*/
			wp_enqueue_style( 'wp-color-picker' );
			if ( WP_DEBUG ) {
				wp_enqueue_style( $this->viwcpf_woo_product_filters . '-style', VIWCPF_FREE_DIR_URL . 'admin/css/woo-product-filters-admin.css', array(), $this->version, 'all' );
			} else {
				wp_enqueue_style( $this->viwcpf_woo_product_filters . '-style', VIWCPF_FREE_DIR_URL . 'admin/css/woo-product-filters-admin.min.css', array(), $this->version, 'all' );

			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Product_Filters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Product_Filters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$current_screen = get_current_screen()->id;

		if ( ( $current_screen == 'viwcpf_filter_block' ) || ( $current_screen == 'edit-viwcpf_filter_block' ) || ( $current_screen == 'viwcpf_filter_menu' ) || ( $current_screen == 'edit-viwcpf_filter_menu' ) || ( $current_screen == 'toplevel_page_viwcpf-woocommerce-product-filters' ) || ( $current_screen == 'pofily_page_viwcpf-woocommerce-product-filters-settings' ) ) {
			/*wp_enqueue_script( $this->viwcpf_woo_product_filters.'-js-form', VIWCPF_FREE_DIR_URL . 'admin/js/form.js', array( 'jquery' ) );*/
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-accordion', VIWCPF_FREE_DIR_URL . 'assets/js/accordion.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-checkbox', VIWCPF_FREE_DIR_URL . 'assets/js/checkbox.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-dropdown', VIWCPF_FREE_DIR_URL . 'assets/js/dropdown.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-transiton', VIWCPF_FREE_DIR_URL . 'assets/js/transition.min.js', array( 'jquery' ) );
			/*wp_enqueue_script( $this->viwcpf_woo_product_filters.'-js-accordion', VIWCPF_FREE_DIR_URL . 'admin/js/accordion.min.js', array( 'jquery' ) );*/
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-tab', VIWCPF_FREE_DIR_URL . 'assets/js/tab.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-address', VIWCPF_FREE_DIR_URL . 'assets/js/address.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-dimmer', VIWCPF_FREE_DIR_URL . 'assets/js/dimmer.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-modal', VIWCPF_FREE_DIR_URL . 'assets/js/modal.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-select', VIWCPF_FREE_DIR_URL . 'assets/js/select2.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-dataTable', VIWCPF_FREE_DIR_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-sematic-dataTable', VIWCPF_FREE_DIR_URL . 'assets/js/dataTables.semanticui.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1 );
			wp_enqueue_script( $this->viwcpf_woo_product_filters . 'wp-color-picker-alpha', VIWCPF_FREE_DIR_URL . 'assets/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ) );

			wp_enqueue_media();
		}
		if ( ( $current_screen == 'viwcpf_filter_block' ) || ( $current_screen == 'edit-viwcpf_filter_block' ) ) {
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-filter-block', VIWCPF_FREE_DIR_URL . 'admin/js/woo-product-filters-block.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->viwcpf_woo_product_filters . '-filter-block', 'viwcpf_ajax', array( 'ajax' => admin_url( "admin-ajax.php" ) ) );
			wp_localize_script( $this->viwcpf_woo_product_filters . '-filter-block', 'viwcpf_default_color', $this->default_data->get_default_color() );
			$i18n_params = array(
				'i18n_min_range_more_than_max_range_error' => esc_html__( 'Please enter in a value less than max range price.', 'pofily-woo-product-filters' ),
				'i18n_max_range_less_than_min_range_error' => esc_html__( 'Please enter in a value more than min range price.', 'pofily-woo-product-filters' ),
				'i18n_new_label'                           => esc_html__( 'New Label', 'pofily-woo-product-filters' ),
				'i18n_enter_tooltip'                       => esc_html__( 'Enter Tooltip', 'pofily-woo-product-filters' ),
				'i18n_choose_color'                        => esc_html__( 'Choose color', 'pofily-woo-product-filters' ),
				'i18n_add_upload_image'                    => esc_html__( 'Add/Upload Image', 'pofily-woo-product-filters' ),
				'i18n_term_name'                           => esc_html__( 'Term name...', 'pofily-woo-product-filters' ),
				'i18n_meta_value'                          => esc_html__( 'Meta value...', 'pofily-woo-product-filters' ),
				'i18n_min'                                 => esc_html__( 'Min', 'pofily-woo-product-filters' ),
				'i18n_max'                                 => esc_html__( 'Max', 'pofily-woo-product-filters' ),
				'i18n_price_symbol'                        => esc_html__( '(' . get_woocommerce_currency_symbol() . ')', 'pofily-woo-product-filters' ),
			);
			wp_localize_script( $this->viwcpf_woo_product_filters . '-filter-block', 'viwcpf_i18n', $i18n_params );
		}
		if ( ( $current_screen == 'viwcpf_filter_menu' ) || ( $current_screen == 'edit-viwcpf_filter_menu' ) ) {
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-filter-menu', VIWCPF_FREE_DIR_URL . 'admin/js/woo-product-filters-menu.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->viwcpf_woo_product_filters . '-filter-menu', 'viwcpf_ajax', array( 'ajax' => admin_url( "admin-ajax.php" ) ) );
		}
		if ( $current_screen == 'pofily_page_viwcpf-woocommerce-product-filters-settings' ) {
			wp_enqueue_script( $this->viwcpf_woo_product_filters . '-filter-setting', VIWCPF_FREE_DIR_URL . 'admin/js/woo-product-filters-setting.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->viwcpf_woo_product_filters . '-filter-setting', 'viwcpf_ajax', array( 'ajax' => admin_url( "admin-ajax.php" ) ) );
		}
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {

	}

	function viwcpf_add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'admin.php?page=viwcpf-woocommerce-product-filters' ) . '">' . esc_html__( 'Settings', 'pofily-woo-product-filters' ) . '</a>',
		);

		return array_merge( $links, $settings_link );
	}

	/**
	 * Register Custom Post Type for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_register_post_type() {
		/*
		 * Post type Filter Blocks
		 * */
		$label = array(
			"name"               => esc_html__( "Filter Blocks", 'pofily-woo-product-filters' ),
			"singular_name"      => esc_html__( "Filter Blocks", 'pofily-woo-product-filters' ),
			"menu_name"          => esc_html__( "Filter Blocks", 'pofily-woo-product-filters' ),
			"all_items"          => esc_html__( "Filter Blocks", 'pofily-woo-product-filters' ),
			"add_new"            => esc_html__( "Add Filter Block", 'pofily-woo-product-filters' ),
			"add_new_item"       => esc_html__( "Add New Filter Block", 'pofily-woo-product-filters' ),
			"edit_item"          => esc_html__( "Edit Filter Block", 'pofily-woo-product-filters' ),
			"new_item"           => esc_html__( "New Filter Block", 'pofily-woo-product-filters' ),
			"view_item"          => esc_html__( "View Filter Block", 'pofily-woo-product-filters' ),
			"view_items"         => esc_html__( "View Filter Blocks", 'pofily-woo-product-filters' ),
			"search_items"       => esc_html__( "Search Filter Blocks", 'pofily-woo-product-filters' ),
			"not_found"          => esc_html__( "No Filter Block found", 'pofily-woo-product-filters' ),
			"not_found_in_trash" => esc_html__( "No Filter Blocks in Trash", 'pofily-woo-product-filters' ),
			"items_list"         => esc_html__( "Filter Blocks List", 'pofily-woo-product-filters' ),

		);
		$args  = array(
			'label'               => esc_html__( "Filter Blocks", 'pofily-woo-product-filters' ),
			'labels'              => $label,
			'description'         => 'Post type Filters Blocks',
			'supports'            => array(
				'title',
				'revisions',
			),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'viwcpf-woocommerce-product-filters',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_position'       => 1,
		);

		register_post_type( 'viwcpf_filter_block', $args );

		/*
		 * Post type Filter Menus
		 * */
		$label = array(
			"name"               => esc_html__( "Filter Menus", 'pofily-woo-product-filters' ),
			"singular_name"      => esc_html__( "Filter Menus", 'pofily-woo-product-filters' ),
			"menu_name"          => esc_html__( "Filter Menus", 'pofily-woo-product-filters' ),
			"all_items"          => esc_html__( "Filter Menus", 'pofily-woo-product-filters' ),
			"add_new"            => esc_html__( "Add Filter Menu", 'pofily-woo-product-filters' ),
			"add_new_item"       => esc_html__( "Add New Filter Menu", 'pofily-woo-product-filters' ),
			"edit_item"          => esc_html__( "Edit Filter Menu", 'pofily-woo-product-filters' ),
			"new_item"           => esc_html__( "New Filter Menu", 'pofily-woo-product-filters' ),
			"view_item"          => esc_html__( "View Filter Menu", 'pofily-woo-product-filters' ),
			"view_items"         => esc_html__( "View Filter Menus", 'pofily-woo-product-filters' ),
			"search_items"       => esc_html__( "Search Filter Menus", 'pofily-woo-product-filters' ),
			"not_found"          => esc_html__( "No Filter Menu found", 'pofily-woo-product-filters' ),
			"not_found_in_trash" => esc_html__( "No Filter Menu in Trash", 'pofily-woo-product-filters' ),
			"items_list"         => esc_html__( "Filter Menus List", 'pofily-woo-product-filters' ),
		);
		$args  = array(
			'labels'              => $label,
			'description'         => 'Post type Filters Menu',
			'supports'            => array(
				'title',
				'revisions',
			),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'viwcpf-woocommerce-product-filters',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 3,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_position'       => 2,
		);

		register_post_type( 'viwcpf_filter_menu', $args );

		$default_option = new VIWCPF_Woo_Product_Filters_Data();
		$default_option->viwcpf_create_default_post();
	}

	/**
	 * Register Menu Setting for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_admin_menu() {
		add_menu_page(
			esc_html__( 'Pofily', 'pofily-woo-product-filters' ),
			esc_html__( 'Pofily', 'pofily-woo-product-filters' ),
			'manage_woocommerce',
			'viwcpf-woocommerce-product-filters',
			array(
				$this,
				'viwcpf_page_setting_function'
			),
			'dashicons-filter',
			3
		);

		add_submenu_page(
			'viwcpf-woocommerce-product-filters',
			esc_html__( 'Settings', 'pofily-woo-product-filters' ),
			esc_html__( 'Settings', 'pofily-woo-product-filters' ),
			'manage_woocommerce',
			'viwcpf-woocommerce-product-filters-settings',
			array(
				$this,
				'viwcpf_page_setting_function'
			)
		);

		add_submenu_page(
			'viwcpf-woocommerce-product-filters',
			esc_html__( 'System status', 'pofily-woo-product-filters' ),
			esc_html__( 'System Status', 'pofily-woo-product-filters' ),
			'manage_woocommerce',
			'viwcpf-system-status',
			array(
				$this,
				'page_callback_system_status'
			)
		);

	}

	public function page_callback_system_status() {
		?>
        <h2><?php esc_html_e( 'System Status', 'pofily-woo-product-filters' ) ?></h2>
        <table cellspacing="0" id="status" class="widefat">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Option name', 'pofily-woo-product-filters' ) ?></th>
                <th><?php esc_html_e( 'Your option value', 'pofily-woo-product-filters' ) ?></th>
                <th><?php esc_html_e( 'Minimum recommended value', 'pofily-woo-product-filters' ) ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-export-label="file_get_contents">file_get_contents</td>
                <td>
					<?php
					if ( function_exists( 'file_get_contents' ) ) {
						?>
                        <mark class="yes">&#10004; <code class="private"></code></mark>
						<?php
					} else {
						?>
                        <mark class="error">&#10005;</mark>'
						<?php
					}
					?>
                </td>
                <td><?php esc_html_e( 'Required', 'pofily-woo-product-filters' ) ?></td>
            </tr>
            <tr>
                <td data-export-label="file_put_contents">file_put_contents</td>
                <td>
					<?php
					if ( function_exists( 'file_put_contents' ) ) {
						?>
                        <mark class="yes">&#10004; <code class="private"></code></mark>
						<?php
					} else {
						?>
                        <mark class="error">&#10005;</mark>
						<?php
					}
					?>

                </td>
                <td><?php esc_html_e( 'Required', 'pofily-woo-product-filters' ) ?></td>
            </tr>
            <tr>
                <td data-export-label="mkdir">mkdir</td>
                <td>
					<?php
					if ( function_exists( 'mkdir' ) ) {
						?>
                        <mark class="yes">&#10004; <code class="private"></code></mark>
						<?php
					} else {
						?>
                        <mark class="error">&#10005;</mark>
						<?php
					}
					?>

                </td>
                <td><?php esc_html_e( 'Required', 'pofily-woo-product-filters' ) ?></td>
            </tr>
			<?php
			$max_execution_time = ini_get( 'max_execution_time' );
			$max_input_vars     = ini_get( 'max_input_vars' );
			$memory_limit       = ini_get( 'memory_limit' );
			?>
            <tr>
                <td data-export-label="<?php esc_attr_e( 'PHP Time Limit', 'pofily-woo-product-filters' ) ?>"><?php esc_html_e( 'PHP Time Limit', 'pofily-woo-product-filters' ) ?></td>
                <td style="<?php if ( $max_execution_time > 0 && $max_execution_time < 300 ) {
					echo esc_attr( 'color:red' );
				} ?>"><?php esc_html_e( $max_execution_time ); ?></td>
                <td><?php esc_html_e( '300', 'pofily-woo-product-filters' ) ?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_attr_e( 'PHP Max Input Vars', 'pofily-woo-product-filters' ) ?>"><?php esc_html_e( 'PHP Max Input Vars', 'pofily-woo-product-filters' ) ?></td>
                <td style="<?php if ( $max_input_vars < 1000 ) {
					echo esc_attr( 'color:red' );
				} ?>"><?php esc_html_e( $max_input_vars ); ?></td>
                <td><?php esc_html_e( '1000', 'pofily-woo-product-filters' ) ?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_attr_e( 'Memory Limit', 'pofily-woo-product-filters' ) ?>"><?php esc_html_e( 'Memory Limit', 'pofily-woo-product-filters' ) ?></td>
                <td style="<?php if ( intval( $memory_limit ) < 64 ) {
					echo esc_attr( 'color:red' );
				} ?>"><?php esc_html_e( $memory_limit ); ?></td>
                <td><?php esc_html_e( '64M', 'pofily-woo-product-filters' ) ?></td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function viwcpf_disable_visibility() {
		echo '<style>div#visibility.misc-pub-section.misc-pub-visibility{display:none}</style>';
	}

	/**
	 * Function setting for viwcpf-woocommerce-product-filters.
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_page_setting_function() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/display-settings-page.php';
	}

	/**
	 * Function Register metabox to detail Filter Block.
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_filter_blocks_meta_box() {
		add_meta_box( 'viwcpf_detail_filter_block', esc_html__( 'More information for the filter block', 'pofily-woo-product-filters' ), array(
			$this,
			'viwcpf_detail_filterBlock'
		), 'viwcpf_filter_block', 'normal', 'high' );
	}

	/**
	 * Function callback Add metabox to detail filter block
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_detail_filterBlock( $post ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/display-detail-filter-block-page.php';
	}

	/**
	 * Function save meta data of detail Filter Block
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_save_detail_filterBlock( $post_id ) {
		if ( ! current_user_can( "edit_post", $post_id ) ) {
			return $post_id;
		}
		if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( isset( $_POST['_viwcpf_filter_block_nonce'] ) ) {
			$viwcpf_filter_block_nonce = sanitize_text_field( $_POST['_viwcpf_filter_block_nonce'] );

			if ( ! wp_verify_nonce( $viwcpf_filter_block_nonce, 'viwcpf_save_filter_block' ) ) {
				return;
			}
		}
		$block_filter_display_type = isset( $_POST['viwcpf_display-type'] ) ? sanitize_text_field( $_POST['viwcpf_display-type'] ) : 'vertical';
		$block_filter_name         = isset( $_POST['filter_block_name'] ) ? sanitize_text_field( $_POST['filter_block_name'] ) : '';
		$show_clear_button         = isset( $_POST['viwcpf-show_clear'] ) ? true : false;
		$show_view_more            = isset( $_POST['viwcpf-show_view_more'] ) ? true : false;
		$show_as_toggle            = isset( $_POST['viwcpf-show_as_toggle'] ) ? true : false;
		if ( $show_as_toggle ) {
			$toggle_style = isset( $_POST['viwcpf_toggle_style'] ) ? sanitize_text_field( $_POST['viwcpf_toggle_style'] ) : sanitize_text_field( 'toggle_style-opened' );
		} else {
			$toggle_style = sanitize_text_field( 'toggle_style-opened' );
		}
		if ( $show_view_more ) {
			$view_more_limit = isset( $_POST['viwcpf-label_limit'] ) ? sanitize_text_field( $_POST['viwcpf-label_limit'] ) : sanitize_text_field( '10' );
		} else {
			$view_more_limit = sanitize_text_field( '10' );
		}

		if ( $block_filter_name == '' ) {
			return;
		}

		$viwcpf_filter_for = isset( $_POST['viwcpf_filter_for'] ) ? sanitize_text_field( $_POST['viwcpf_filter_for'] ) : '';

		$filter_data = array();
		switch ( $viwcpf_filter_for ):

			case 'filter_by_price':
				$price_type_filter     = isset( $_POST['viwcpf_price-type_filter'] ) ? sanitize_text_field( $_POST['viwcpf_price-type_filter'] ) : 'price_range';
				$price_show_count_item = isset( $_POST['price-show_count_items'] ) ? true : false;
				if ( $price_type_filter == 'range' ) {
					$price_type_show = isset( $_POST['viwcpf_price-type_show'] ) ? sanitize_text_field( $_POST['viwcpf_price-type_show'] ) : 'button';


					$price_data = isset( $_POST['viwcpf_price_range'] ) ? wc_clean( $_POST['viwcpf_price_range'] ) : array();
					if ( count( $price_data ) > 0 ) {
						if ( $_POST['viwcpf_price_last_range_limitless'] ) {
							$len_range_price                                 = count( $price_data );
							$price_data[ $len_range_price - 1 ]['limitless'] = sanitize_text_field( $_POST['viwcpf_price_last_range_limitless'] );
						}
					}

				} else {
					$price_min  = isset( $_POST['price-slide_min'] ) ? sanitize_text_field( $_POST['price-slide_min'] ) : '';
					$price_max  = isset( $_POST['price-slide_max'] ) ? sanitize_text_field( $_POST['price-slide_max'] ) : '';
					$price_step = isset( $_POST['price-slide_step'] ) ? sanitize_text_field( $_POST['price-slide_step'] ) : '';

					$price_data      = array(
						'min_price'  => $price_min,
						'max_price'  => $price_max,
						'step_price' => $price_step,
					);
					$price_type_show = 'range_slide';
				}
				$filter_data                    = array(
					'type_filter'    => $price_type_filter,
					'type_show'      => $price_type_show,
					'multiselect'    => false,
					'multi_relation' => 'AND',
					'order_by'       => 'name',
					'order_type'     => 'asc',
				);
				$filter_data['customize_value'] = $price_data;

				if ( $price_type_filter == 'range' ) {
					$filter_data['show_count_item'] = $price_show_count_item;
				} else {
					$filter_data['show_count_item'] = false;
				}
				break;
			case 'filter_by_review':
				$review_type_show       = isset( $_POST['viwcpf_review-type_show'] ) ? sanitize_text_field( $_POST['viwcpf_review-type_show'] ) : 'button';
				$review_show_count_item = isset( $_POST['review-show_count_items'] ) ? true : false;
				$review_show_icon_star  = isset( $_POST['review-show_icon_star'] ) ? true : false;
				$review_multiselect     = true;

				if ( $review_type_show === 'icon_star' ) {
					$review_show_icon_star  = false;
					$review_show_count_item = false;
					$review_multiselect     = false;
				}
				$filter_data = array(
					'type_show'       => $review_type_show,
					'multiselect'     => $review_multiselect,
					'show_count_item' => $review_show_count_item,
					'show_icon_star'  => $review_show_icon_star,
					'multi_relation'  => 'OR',
					'order_by'        => 'name',
					'order_type'      => 'asc',
				);

				break;
			case 'filter_by_sale_or_stock':
				$oai_type_show       = isset( $_POST['viwcpf_onsale-instock_type_show'] ) ? sanitize_text_field( $_POST['viwcpf_onsale-instock_type_show'] ) : 'button'; //Onsale And Instock type show =)))
				$oai_show_count_item = isset( $_POST['onsale-instock-show_count_items'] ) ? true : false; //Onsale And Instock count item =)))
				$show_onsale         = isset( $_POST['show-onsale'] ) ? true : false;
				$show_instock        = isset( $_POST['show-instock'] ) ? true : false;
				$customize_data      = array();
				if ( $show_onsale ) {
					$customize_data[] = array(
						'old_label' => ( 'On sale' ),
						'new_label' => '',
						'tooltip'   => ( 'On sale' ),
					);
				}
				if ( $show_instock ) {
					$customize_data[] = array(
						'old_label' => ( 'In stock' ),
						'new_label' => '',
						'tooltip'   => ( 'In stock' ),
					);
				}


				$filter_data = array(
					'show_onsale'     => $show_onsale,
					'show_instock'    => $show_instock,
					'type_show'       => $oai_type_show, //Onsale And Instock type show =)))
					'multiselect'     => true,
					'customize_value' => $customize_data,
					'show_count_item' => $oai_show_count_item,
					'multi_relation'  => 'AND',
					'order_by'        => 'name',
					'order_type'      => 'asc',
				);
				break;
			case 'filter_by_name_product':
				$placeholder_input_search = isset( $_POST['viwcpf-name-placeholder'] ) ? sanitize_text_field( $_POST['viwcpf-name-placeholder'] ) : ''; //Placeholder input search name product
				$filter_data              = array(
					'placeholder_search' => $placeholder_input_search, //Type default is input search
					'type_show'          => 'search_field', //Type default is input search
					'multiselect'        => false,
					'show_count_item'    => false,
					'multi_relation'     => 'AND',
					'order_by'           => 'name',
					'order_type'         => 'asc',
				);
				break;
			case 'filter_by_metabox':
				$meta_key_filter            = isset( $_POST['viwcpf_meta_key'] ) ? sanitize_text_field( $_POST['viwcpf_meta_key'] ) : '';
				$list_metavalue             = array();
				$meta_filter_number_display = '';
				if ( $meta_key_filter != '' ) {
					$customize_meta_data = array();
					$meta_type_filter    = isset( $_POST['viwcpf_meta_type'] ) ? sanitize_text_field( $_POST['viwcpf_meta_type'] ) : '';
					if ( ( $meta_type_filter == 'string' ) ) {
						$list_metavalue        = isset( $_POST['viwcpf_input_search_meta_value'] ) ? wc_clean( $_POST['viwcpf_input_search_meta_value'] ) : '';
						$meta_filter_show_type = isset( $_POST['viwcpf_meta_string-show_type'] ) ? sanitize_text_field( $_POST['viwcpf_meta_string-show_type'] ) : 'button';
						if ( ( $list_metavalue != '' ) && ( sizeof( $list_metavalue ) > 0 ) ) {
							/*get data meta value*/
							$metavalue_item_data = isset( $_POST[ 'viwcpf_metavalue_' . $meta_key_filter ] ) ? wc_clean( $_POST[ 'viwcpf_metavalue_' . $meta_key_filter ] ) : array();
							/*loop set data meta value to variable $customize_terms_data */
							foreach ( $metavalue_item_data as $metavalue_item ) {
								$customize_meta_data[ $metavalue_item['old_label'] ] = array(
									'old_label' => $metavalue_item['old_label'],
									'new_label' => $metavalue_item['new_label'],
									'tooltip'   => $metavalue_item['tooltip'],
								); //save data of meta value
							}
						}
						$meta_filter_multiselect    = isset( $_POST['viwcpf_meta-multi_select'] ) ? true : false;
						$meta_filter_multi_relation = isset( $_POST['viwcpf_meta-multi_relation'] ) ? sanitize_text_field( $_POST['viwcpf_meta-multi_relation'] ) : 'AND';
					} else if ( $meta_type_filter == 'numberic' ) {
						$meta_filter_number_display = isset( $_POST['viwcpf_meta_numberic-show_type'] ) ? sanitize_text_field( $_POST['viwcpf_meta_numberic-show_type'] ) : 'range';
						if ( $meta_filter_number_display == 'range' ) {
							$meta_filter_show_type = 'button';
							$customize_meta_data   = isset( $_POST['viwcpf_meta_range'] ) ? wc_clean( $_POST['viwcpf_meta_range'] ) : array();
							if ( count( $customize_meta_data ) > 0 ) {
								if ( $_POST['viwcpf_meta_last_range_limitless'] ) {
									$len_range_meta                                          = count( $customize_meta_data );
									$customize_meta_data[ $len_range_meta - 1 ]['limitless'] = sanitize_text_field( $_POST['viwcpf_meta_last_range_limitless'] );
								}
							}
						} else {
							$meta_filter_show_type = $meta_filter_number_display;
							$meta_range_min        = isset( $_POST['viwcpf_meta-range_slide_min'] ) ? sanitize_text_field( $_POST['viwcpf_meta-range_slide_min'] ) : '';
							$meta_range_max        = isset( $_POST['viwcpf_meta-range_slide_max'] ) ? sanitize_text_field( $_POST['viwcpf_meta-range_slide_max'] ) : '';
							$meta_range_step       = ! empty( $_POST['viwcpf_meta-range_slide_step'] ) ? sanitize_text_field( $_POST['viwcpf_meta-range_slide_step'] ) : '1';

							$customize_meta_data = array(
								'meta_range_min'  => $meta_range_min,
								'meta_range_max'  => $meta_range_max,
								'meta_range_step' => $meta_range_step,
							);
						}
						/*Important Note: when type is NUMBERIC then multiselect will hidden*/
						$meta_filter_multiselect    = false;
						$meta_filter_multi_relation = 'AND';
					}
					$meta_filter_show_count_item = isset( $_POST['viwcpf_meta-show_count_items'] ) ? true : false;
					if ( $meta_filter_show_type == 'range_slide' ) {
						$meta_filter_show_count_item = false;
					}

					$meta_filter_order_by   = isset( $_POST['viwcpf_meta-order_by'] ) ? sanitize_text_field( $_POST['viwcpf_meta-order_by'] ) : 'name';
					$meta_filter_order_type = isset( $_POST['viwcpf_meta-order_type'] ) ? sanitize_text_field( $_POST['viwcpf_meta-order_type'] ) : 'asc';

					/*Fiter data of Filter by metadata*/
					$filter_data = array(
						'meta_key_filter'            => $meta_key_filter,
						'list_metavalue'             => $list_metavalue,
						'meta_type_filter'           => $meta_type_filter,
						'meta_filter_number_display' => $meta_filter_number_display,
						'type_show'                  => $meta_filter_show_type,
						'customize_value'            => $customize_meta_data,
						'multiselect'                => $meta_filter_multiselect,
						'show_count_item'            => $meta_filter_show_count_item,
						'multi_relation'             => $meta_filter_multi_relation,
						'order_by'                   => $meta_filter_order_by,
						'order_type'                 => $meta_filter_order_type,
					);
				}
				break;
			default:
				break;
		endswitch;

		$viwcpf_filter_block = array(
			'name'        => $block_filter_name,
			'filter_for'  => $viwcpf_filter_for,
			'filter_data' => $filter_data,
			'settings'    => array(
				'display_type'    => $block_filter_display_type,
				'show_clear'      => $show_clear_button,
				'show_as_toggle'  => $show_as_toggle,
				'toggle_style'    => $toggle_style,
				'show_view_more'  => $show_view_more,
				'view_more_limit' => $view_more_limit
			)
		);
		if ( $viwcpf_filter_for !== 'filter_by_taxonomy' ) {
			update_post_meta( $post_id, "viwcpf_filter_block", $viwcpf_filter_block );
		}
	}

	/**
	 * Function ajax save meta data of detail Filter Block
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_ajax_update_filterBlock() {
		$post_id = isset( $_POST['post_ID'] ) ? sanitize_text_field( $_POST['post_ID'] ) : '';
		$status  = '';

		if ( $post_id != '' ) {
			if ( ! current_user_can( "edit_post", $post_id ) ) {
				$status = "error can't edit";
			}
			if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
				$status = 'error';
			}
			if ( isset( $_POST['_viwcpf_filter_block_nonce'] ) ) {
				$viwcpf_filter_block_nonce = sanitize_text_field( $_POST['_viwcpf_filter_block_nonce'] );

				if ( wp_verify_nonce( $viwcpf_filter_block_nonce, 'viwcpf_save_filter_block' ) ) {

					$viwcpf_filter_block = isset( $_POST['viwcpf_filter_block'] ) ? wc_clean( $_POST['viwcpf_filter_block'] ) : array();
					if ( is_array( $viwcpf_filter_block ) && count( $viwcpf_filter_block ) ) {
						$old_viwcpf_filter_block = get_post_meta( $post_id, "viwcpf_filter_block", true );
						$new_viwcpf_filter_block = wp_parse_args( $old_viwcpf_filter_block, $viwcpf_filter_block );

						update_post_meta( $post_id, "viwcpf_filter_block", $viwcpf_filter_block );
						$status = 'success';
					} else {
						$status = 'error data';
					}

				} else {
					$status = 'error verify nonce';
				}

			} else {
				$status = 'error nonce';
			}

		} else {
			$status = "error not found block";
		}
		$response = array(
			'status' => $status
		);
		wp_send_json( $response );
		die();
	}

	/**
	 * Function Register metabox to detail Filter Menu.
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_filter_menu_meta_box() {
		add_meta_box( 'viwcpf_detail_filter_menu', esc_html__( 'More information for the filter menu', 'pofily-woo-product-filters' ), array(
			$this,
			'viwcpf_detail_filterMenu'
		), 'viwcpf_filter_menu', 'normal', 'high' );
	}

	/**
	 * Function callback Add metabox to detail Filter Menu
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_detail_filterMenu( $post ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/display-detail-filter-menu-page.php';
	}

	/**
	 * Function save meta data of detail Filter Menu
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_save_detail_filterMenu( $post_id ) {
		global $post;
		if ( ! current_user_can( "edit_post", $post_id ) ) {
			return $post_id;
		}
		if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( isset( $_POST['_viwcpf_filter_menu_nonce'] ) ) {
			$viwcpf_filter_menu_nonce = sanitize_text_field( $_POST['_viwcpf_filter_menu_nonce'] );

			if ( ! wp_verify_nonce( $viwcpf_filter_menu_nonce, 'viwcpf_save_filter_menu' ) ) {
				return;
			}
		}

		$viwcpf_blocks_selected    = isset( $_POST['viwcpf_blocks_selected'] ) ? sanitize_text_field( $_POST['viwcpf_blocks_selected'] ) : '';
		$viwcpf_using_ajax         = isset( $_POST['viwcpf-using_ajax'] ) ? true : false;
		$viwcpf_show_button_submit = isset( $_POST['viwcpf-show_button_submit'] ) ? true : false;
		$viwcpf_block_relation     = isset( $_POST['viwcpf-block_relation'] ) ? sanitize_text_field( $_POST['viwcpf-block_relation'] ) : 'AND';

		$viwcpf_display_conditions = isset( $_POST['viwcpf-display_conditions'] ) ? wc_clean( $_POST['viwcpf-display_conditions'] ) : array();
		$viwcpf_show_in_modal      = isset( $_POST['viwcpf-show_in_modal'] ) ? true : false;
		$viwcpf_show_reset_button  = isset( $_POST['viwcpf-show_reset_button'] ) ? true : false;
		if ( $viwcpf_show_reset_button ) {
			$viwcpf_reset_button_position = isset( $_POST['viwcpf-reset_button_position'] ) ? sanitize_text_field( $_POST['viwcpf-reset_button_position'] ) : 'before_filter';
		} else {
			$viwcpf_reset_button_position = 'before_filter';
		}

		$viwcpf_filter_menu = array(
			'viwcpf_blocks_selected'       => $viwcpf_blocks_selected,
			'viwcpf_using_ajax'            => $viwcpf_using_ajax,
			'viwcpf_show_button_submit'    => $viwcpf_show_button_submit,
			'viwcpf_block_relation'        => $viwcpf_block_relation,
			'viwcpf_show_in_modal'         => $viwcpf_show_in_modal,
			'viwcpf_show_reset_button'     => $viwcpf_show_reset_button,
			'viwcpf_reset_button_position' => $viwcpf_reset_button_position,
			'viwcpf_display_conditions'    => $viwcpf_display_conditions,
		);

		update_post_meta( $post_id, "viwcpf_filter_menu", $viwcpf_filter_menu );

	}

	/**
	 * Function search term by taxonomy of Filter Block
	 *  $key_search == -1 show all term
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_search_term() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$key_search = sanitize_text_field( $_POST['keysearch'] );
		$tax_search = sanitize_text_field( $_POST['tax_search'] );
		if ( $key_search === '-1' ) {
			$arr_tax = get_terms( array(
				'taxonomy'   => $tax_search,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'fields'     => 'all',
			) );
		} else {
			$arr_tax = get_terms( array(
				'taxonomy'   => $tax_search,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'search'     => $key_search,
				'hide_empty' => true
			) );
		}

		$items = array();
		if ( count( $arr_tax ) ) {
			foreach ( $arr_tax as $tax_item ) {
				$item    = array(
					'id'   => $tax_item->term_id,
					'text' => $tax_item->name
				);
				$items[] = $item;
			}
		}
		wp_send_json( $items );
		die();
	}

	/**
	 * Function search term by taxonomy of Filter Block
	 *  $key_search == -1 show all term
	 *
	 * @since    1.0.0
	 */
	public function viwcpf_refresh_block_filter() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$exclude_str_id = sanitize_text_field( $_POST['exclude_str_id'] );
		$items          = '';

		$arr_blocks_selected  = explode( ',', $exclude_str_id );
		$args_block           = array(
			'post_type'      => 'viwcpf_filter_block',
			'post_status'    => 'publish',
			'post__not_in'   => $arr_blocks_selected,
			'posts_per_page' => - 1,
		);
		$filters_blocks_query = new WP_Query( $args_block );

		if ( $filters_blocks_query->have_posts() ):
			// The Loop
			while ( $filters_blocks_query->have_posts() ) : $filters_blocks_query->the_post();

				$items .= '<div class="vi-ui segment item_block add_block" data-block_id="' . esc_attr( get_the_ID() ) . '" data-block_name="' . esc_attr( get_the_title() ) . '" data-block_url="' . esc_url( get_edit_post_link() ) . '">
                    <h4>' . esc_html__( get_the_title(), 'pofily-woo-product-filters' ) . '</h4>
                    <div class="wrapp_btn">
                        <a href="' . esc_url( get_edit_post_link() ) . '" class="vi-ui edit_block icon blue button mini compact"> <i class="edit icon"></i></a>
                    </div>
                </div>';

			endwhile;
		endif;
		// Reset Post Data
		wp_reset_postdata();

		wp_send_json( $items );
		die();
	}


	public function custom_post_columns( $columns ) {

		$columns['short-code'] = esc_html( 'Short Code' );

		return $columns;
	}

	public function show_custom_columns( $name ) {
		global $post;
		switch ( $name ) {
			case 'short-code':
				?>
                <div class="vi-ui icon input fluid">
                    <input type="text" class="viwcpf_shortcode_show" readonly
                           value="[VIWCPF_SHORTCODE id_menu=<?php echo "'" . esc_attr( $post->ID ) . "'"; ?>]">
                    <i class="copy icon"></i>
                    <span class="viwcpf_copied_tooltip" style="">Copied</span>
                </div>
				<?php
				break;
			default:
				break;
		}
	}

	public function shortcode_after_title_detail_filter_menu() {
		$current_screen = get_current_screen()->id;
		global $post;
		if ( ( $current_screen != 'viwcpf_filter_menu' ) ) {
			return;
		}

		?>
        <div class="inside">

            <div class="shortcode">
                <div class="vi-ui left labeled icon input fluid">
                    <label class="vi-ui label"
                           for="viwcpf_shortcode_show"><?php esc_html_e( 'Shortcode:', 'pofily-woo-product-filters' ); ?></label>
                    <input type="text" id="viwcpf_shortcode_show" class="viwcpf_shortcode_show" readonly
                           value="[VIWCPF_SHORTCODE id_menu=<?php echo "'" . esc_attr( $post->ID ) . "'"; ?>]">
                    <i class="copy icon"></i>
                    <span class="viwcpf_copied_tooltip"
                          style=""><?php esc_html_e( 'Copied', 'pofily-woo-product-filters' ); ?></span>
                </div>
            </div>
        </div>
		<?php
	}

	public function register_widget() {
		$VIWCPF_Free_Widget_Filter_Menu = new VIWCPF_Free_Widget_Filter_Menu();
		register_widget( $VIWCPF_Free_Widget_Filter_Menu );
	}

	public function viwcpf_save_settings() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		$args = array(
			'off_canvas'         => array(
				'general' => array(
					'position'    => 'bottom_left',
					'effect_open' => 'slide',
					'auto_open'   => 'on'
				),
				'icon'    => array(
					'box_shadow'       => 'on',
					'size'             => '1',
					'size_hover'       => '1',
					'icon_radius'      => '50%',
					'color'            => '#ffffff',
					'color_hover'      => '#78a938',
					'background'       => '#78a938',
					'background_hover' => '#ffffff',
					'horizontal'       => '25px',
					'vertical'         => '35px',
				),
				'content' => array(),
			),
			'show_active_labels' => '',
			'active_position'    => 'before_filters',
			'option_style'       => 'custom_style',
			'area'               => array(
				'color' => array(
					'title'      => '#434343',
					'background' => '#ffffff',
					'accent'     => '#0c0c0c',
				),
			),
			'label'              => array(
				'size'  => array(
					'font_size'     => '16px',
					'border_width'  => '1',
					'border_radius' => '4',
				),
				'color' => array(
					'background'        => '#ffffff',
					'background_hover'  => '#ebebeb',
					'background_active' => '#ebebeb',
					'text'              => '#000000',
					'text_hover'        => '#000000',
					'text_active'       => '#000000',
				),
			),
			'color_swatches'     => array(
				'btn_style'     => array(
					'btn_width'           => 22,
					'btn_height'          => 22,
					'btn_border_radius'   => '50%',
					'btn_color_separator' => 1,
				),
				'color_default' => '#fe2740',
			),
			'display_metakey'    => array(),
		);
		if ( $page !== 'viwcpf-woocommerce-product-filters-settings' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_POST['_viwcpf_filter_setting_nonce'] ) || ! wp_verify_nonce( $_POST['_viwcpf_filter_setting_nonce'], 'viwcpf_save_filter_setting' ) ) {
			return;
		}


		if ( isset( $_POST['viwcpf-reset-default'] ) ) {
			update_option( 'viwcpf_setting_params', $args );

			return;
		}
		if ( isset( $_POST['viwcpf-save-settings'] ) ) {

			$viwcpf_settings     = wc_clean( $_POST['viwcpf_setting'] );
			$viwcpf_new_settings = wp_parse_args( $viwcpf_settings, $args );
			update_option( 'viwcpf_setting_params', $viwcpf_new_settings );

		}


	}

	public function expanded_alowed_tags() {
		$my_allowed = wp_kses_allowed_html( 'post' );

		// form fields - input
		$my_allowed['input'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
		);
		// select
		$my_allowed['select'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
		);
		// select options
		$my_allowed['option'] = array(
			'selected' => array(),
		);
		// style
		$my_allowed['style'] = array(
			'types' => array(),
		);

		return $my_allowed;
	}

	public function viwcpf_duplicate_block_filter_link( $actions, $post ) {

		if ( ! current_user_can( 'edit_posts' ) ) {
			return $actions;
		}
		if ( $post->post_type == "viwcpf_filter_block" ) {
			$url = wp_nonce_url( add_query_arg( array(
				'action' => 'viwcpf_duplicate_block_as_draft',
				'post'   => $post->ID,
			), 'admin.php' ), basename( __FILE__ ), 'duplicate_nonce' );

			$actions['duplicate'] = '<a href="' . esc_url( $url ) . '" title="Duplicate this email" rel="permalink">' . esc_html__( 'Duplicate', 'woo-coupon-reminders' ) . '</a>';
			unset ( $actions['view'] );
		}

		return $actions;
	}

	function viwcpf_duplicate_block_as_draft() {

		// check if post ID has been provided and action
		if ( empty( $_GET['post'] ) ) {
			wp_die( 'No block filter to duplicate has been provided!' );
		}

		// Nonce verification
		if ( ! isset( $_GET['duplicate_nonce'] ) || ! wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		// Get the original post id
		$post_id = wc_clean(absint( $_GET['post'] ));

		// And all the original post data then
		$post = get_post( $post_id );

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		// if post data exists (I am sure it is, but just in a case), create the post duplicate
		if ( $post ) {

			// new post data array
			$args = array(
				'post_author' => $new_post_author,
				'post_status' => 'draft',
				'post_title'  => $post->post_title,
				'post_type'   => $post->post_type,

			);

			// insert the post by wp_insert_post() function
			$new_post_id = wp_insert_post( $args );

			// duplicate all post meta

			$viwcpf_filter_block = get_post_meta( $post_id, 'viwcpf_filter_block', true );

			if ( isset( $viwcpf_filter_block ) && is_array( $viwcpf_filter_block ) ) {
				update_post_meta( $new_post_id, 'viwcpf_filter_block', $viwcpf_filter_block );
			}
			// finally, redirect to the edit post screen for the new draft
			wp_safe_redirect( add_query_arg( array(
				'action' => 'edit',
				'post'   => $new_post_id
			), admin_url( 'post.php' ) ) );
			exit;
			// or we can redirect to all posts with a message
			//			wp_safe_redirect(
			//				add_query_arg(
			//					array(
			//						'post_type' => ( 'viwcpf_filter_block' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
			//						'saved' => 'block_filter_duplication_created' // just a custom slug here
			//					),
			//					admin_url( 'edit.php?post_type=viwcpf_filter_block' )
			//				)
			//			);
			//			exit;

		} else {
			wp_die( 'Post creation failed, could not find original post.' );
		}

	}

}
