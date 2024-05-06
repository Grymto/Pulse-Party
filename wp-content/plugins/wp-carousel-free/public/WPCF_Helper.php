<?php
/**
 * The WPCF_Helper class to manage all public facing stuffs.
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public
 */

if ( ! class_exists( 'WPCF_Helper' ) ) {
	/**
	 * The WPCF_Helper class.
	 *
	 * @since 2.3.4
	 */
	class WPCF_Helper {

		/**
		 * Holds the class object.
		 *
		 * @since 2.3.4
		 * @var object
		 */
		public static $instance;

		/**
		 * Contain the version class object.
		 *
		 * @since 2.3.4
		 * @var object
		 */
		public $version;

		/**
		 * Holds the carousel data.
		 *
		 * @since 2.3.4
		 * @var array
		 */
		public $data;

		/**
		 * YouTube video support.
		 *
		 * @since 2.3.4
		 * @var boolean
		 */
		public $youtube = false;

		/**
		 * Vimeo video support.
		 *
		 * @since 2.3.4
		 * @var boolean
		 */
		public $vimeo = false;

		/**
		 * The post ID.
		 *
		 * @var string $post_id The post id of the carousel shortcode.
		 */
		public $post_id;


		/**
		 * Allows for accessing single instance of class. Class should only be constructed once per call.
		 *
		 * @since 2.3.4
		 * @static
		 * @return WPCF_Helper instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Custom Template locator.
		 *
		 * @param  mixed $template_name template name .
		 * @param  mixed $template_path template path .
		 * @param  mixed $default_path default path .
		 * @return string
		 */
		public static function wpcf_locate_template( $template_name, $template_path = '', $default_path = '' ) {
			if ( ! $template_path ) {
				$template_path = 'wp-carousel-free/templates';
			}

			if ( ! $default_path ) {
				$default_path = WPCAROUSELF_PATH . 'public/templates/';
			}

			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
					$template_name,
				)
			);

			// Get default template.
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}
			// Return what we found.
			return $template;
		}
		/**
		 * Section title
		 *
		 * @param  mixed $post_id post id.
		 * @param  mixed $section_title show/hide section title.
		 * @param  mixed $main_section_title section title.
		 * @return void
		 */
		public static function section_title( $post_id, $section_title, $main_section_title ) {
			if ( $section_title ) {
				ob_start();
				include self::wpcf_locate_template( 'section-title.php' );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo apply_filters( 'sp_wpcp_section_title', ob_get_clean() );
			}
		}

		/**
		 * Image tag of the single item.
		 *
		 * @param  boolean $lazy_load_image true or false.
		 * @param  string  $wpcp_layout layout types.
		 * @param  string  $img_url image main url.
		 * @param  string  $title_attr image title attr.
		 * @param  string  $width image width.
		 * @param  string  $height image height.
		 * @param  string  $alt_text image alt text.
		 * @param  string  $lazy_img_url lazy load image.
		 * @return string
		 */
		public static function get_item_image( $lazy_load_image, $wpcp_layout, $img_url, $title_attr, $width, $height, $alt_text, $lazy_img_url ) {
			if ( 'false' !== $lazy_load_image && 'carousel' === $wpcp_layout ) {
				$image = sprintf( '<img class="wcp-lazy swiper-lazy" data-src="%1$s" src="%6$s" %2$s alt="%3$s" width="%4$s" height="%5$s">', $img_url, $title_attr, $alt_text, $width, $height, $lazy_img_url );
			} else {
				$image = sprintf( '<img class="skip-lazy" src="%1$s"%2$s alt="%3$s" width="%4$s" height="%5$s">', $img_url, $title_attr, $alt_text, $width, $height );
			}
			return $image;
		}
		/**
		 * Preloader
		 *
		 * @param  int  $post_id post id.
		 * @param  bool $preloader show preload.
		 * @return void
		 */
		public static function preloader( $post_id, $preloader ) {
			$preloader_image = WPCAROUSELF_URL . 'public/css/spinner.svg';
			if ( ! empty( $preloader_image ) && $preloader ) {
				ob_start();
				include self::wpcf_locate_template( 'preloader.php' );
				$prloader = apply_filters( 'sp_wpcp_preloader', ob_get_clean() );
				echo wp_kses_post( $prloader );
			}
		}

		/**
		 * All loop items.
		 *
		 * @param  array  $upload_data upper upload data.
		 * @param  array  $shortcode_data bottom metabox.
		 * @param  string $carousel_type carousel type.
		 * @param  array  $post_id carousel post id.
		 * @return void
		 */
		public static function get_item_loops( $upload_data, $shortcode_data, $carousel_type, $post_id ) {
			$show_slide_image = isset( $shortcode_data['show_image'] ) ? $shortcode_data['show_image'] : '';
			$show_img_title   = isset( $shortcode_data['wpcp_post_title'] ) ? $shortcode_data['wpcp_post_title'] : '';
			$image_link_show  = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] : 'l_box';
			$wpcp_layout      = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			$lazy_load_img    = apply_filters( 'wpcp_lazy_load_img', WPCAROUSELF_URL . 'public/css/spinner.svg' );
			$lazy_load_image  = isset( $shortcode_data['wpcp_image_lazy_load'] ) ? $shortcode_data['wpcp_image_lazy_load'] : 'false';

			$_image_title_att      = isset( $shortcode_data['_image_title_attr'] ) ? $shortcode_data['_image_title_attr'] : '';
			$show_image_title_attr = ( $_image_title_att ) ? 'true' : 'false';
			$image_sizes           = isset( $shortcode_data['wpcp_image_sizes'] ) ? $shortcode_data['wpcp_image_sizes'] : 'medium';
			$post_order_by         = isset( $shortcode_data['wpcp_post_order_by'] ) ? $shortcode_data['wpcp_post_order_by'] : '';
			$post_order            = isset( $shortcode_data['wpcp_post_order'] ) ? $shortcode_data['wpcp_post_order'] : '';
			$grid_column           = '';
			if ( 'grid' === $wpcp_layout ) {
				$column_number     = isset( $shortcode_data['wpcp_number_of_columns'] ) ? $shortcode_data['wpcp_number_of_columns'] : '';
				$column_lg_desktop = isset( $column_number['lg_desktop'] ) && ! empty( $column_number['lg_desktop'] ) ? $column_number['lg_desktop'] : '5';
				$column_desktop    = isset( $column_number['desktop'] ) && ! empty( $column_number['desktop'] ) ? $column_number['desktop'] : '4';
				$column_sm_desktop = isset( $column_number['laptop'] ) && ! empty( $column_number['laptop'] ) ? $column_number['laptop'] : '3';
				$column_tablet     = isset( $column_number['tablet'] ) && ! empty( $column_number['tablet'] ) ? $column_number['tablet'] : '2';
				$column_mobile     = isset( $column_number['mobile'] ) && ! empty( $column_number['mobile'] ) ? $column_number['mobile'] : '1';
				$grid_column       = "wpcpro-col-xs-$column_mobile wpcpro-col-sm-$column_tablet  wpcpro-col-md-$column_sm_desktop wpcpro-col-lg-$column_desktop wpcpro-col-xl-$column_lg_desktop";
			}

			if ( 'carousel' === $wpcp_layout ) {
				$grid_column = 'swiper-slide';
			}

			if ( 'product-carousel' === $carousel_type ) {
				$show_quick_view_button = isset( $shortcode_data['quick_view'] ) ? $shortcode_data['quick_view'] : true;
				$show_product_name      = $shortcode_data['wpcp_product_name'];
				$show_product_price     = $shortcode_data['wpcp_product_price'];
				$show_product_rating    = $shortcode_data['wpcp_product_rating'];
				$show_product_cart      = $shortcode_data['wpcp_product_cart'];
				$product_query          = self::wpcp_query( $upload_data, $shortcode_data, $post_id );
				if ( $product_query->have_posts() ) {
					while ( $product_query->have_posts() ) :
						$product_query->the_post();
						global $product, $woocommerce;
						require self::wpcf_locate_template( 'loop/product-type.php' );
					endwhile;
					wp_reset_postdata();
				} else {
					echo '<h2 class="sp-not-found-any-post" >' . esc_html__( 'No products found', 'wp-carousel-free' ) . '</h2>';
				}
			}
			if ( 'post-carousel' === $carousel_type ) {
				$show_post_content = $shortcode_data['wpcp_post_content_show'];
				$show_post_date    = $shortcode_data['wpcp_post_date_show'];
				$show_post_author  = $shortcode_data['wpcp_post_author_show'];

				$post_query = self::wpcp_query( $upload_data, $shortcode_data, $post_id );
				if ( $post_query->have_posts() ) {
					while ( $post_query->have_posts() ) :
						$post_query->the_post();
						require self::wpcf_locate_template( 'loop/post-type.php' );
					endwhile;
					wp_reset_postdata();
				} else {
					echo '<h2 class="wpcp-no-post-found" >' . esc_html__( 'No posts found', 'wp-carousel-free' ) . '</h2>';
				}
			}
			if ( 'image-carousel' === $carousel_type ) {
				$gallery_ids         = $upload_data['wpcp_gallery'];
				$the_image_title_at  = isset( $shortcode_data['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_logo_link_nofollow'] : '';
				$image_link_nofollow = $the_image_title_at ? ' rel="nofollow"' : '';
				if ( empty( $gallery_ids ) ) {
					return;
				}
				$image_orderby = isset( $shortcode_data['wpcp_image_order_by'] ) ? $shortcode_data['wpcp_image_order_by'] : '';
				$attachments   = explode( ',', $gallery_ids );
				( ( 'rand' === $image_orderby ) ? shuffle( $attachments ) : '' );
				if ( is_array( $attachments ) || is_object( $attachments ) ) :
					foreach ( $attachments as $attachment ) {
						require self::wpcf_locate_template( 'loop/image-type.php' );
					} // End foreach.
				endif;
			}
		}
		/**
		 * Get pagination
		 *
		 * @param  array $upload_data shortcode upper metabox.
		 * @param  array $shortcode_data  shortcode bottom metabox.
		 * @param  int   $post_id shortcode id.
		 * @return void
		 */
		public static function get_pagination( $upload_data, $shortcode_data, $post_id ) {
			$wpcp_pagination = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;

			$wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			if ( $wpcp_pagination && 'carousel' !== $wpcp_layout ) {
				$carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
				if ( 'post-carousel' === $carousel_type || 'product-carousel' === $carousel_type ) {
					// $wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
					$wpcp_query = self::wpcp_query( $upload_data, $shortcode_data, $post_id );

					$total_pages = $wpcp_query->max_num_pages;
					// Full wp pagination example.
					$wppaged    = 'paged' . $post_id;
					$args       = array(
						'format'       => '?' . $wppaged . '=%#%',
						// 'format'       => '?paged=%#%',
						'current'      => isset( $_GET[ "$wppaged" ] ) ? $_GET[ "$wppaged" ] : 1,
						'total'        => $total_pages,
						'prev_next'    => true,
						'next_text'    => '<i class="fa fa-angle-right"></i>',
						'prev_text'    => '<i class="fa fa-angle-left"></i>',
						// 'type'         => $type,
						'show_all'     => true,
						'aria_current' => true,
					);
					$page_links = paginate_links( $args );

					include self::wpcf_locate_template( 'pagination.php' );
				}
			}
		}
		/**
		 * Post and product query
		 *
		 * @param  array $upload_data upper upload data.
		 * @param  array $shortcode_data bottom metabox.
		 * @param  mixed $post_id shortcode id.
		 * @return object
		 */
		public static function wpcp_query( $upload_data, $shortcode_data, $post_id ) {
			$carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';

			// Order orderby.
			$post_order_by   = isset( $shortcode_data['wpcp_post_order_by'] ) ? $shortcode_data['wpcp_post_order_by'] : '';
			$post_order      = isset( $shortcode_data['wpcp_post_order'] ) ? $shortcode_data['wpcp_post_order'] : '';
			$post_per_page   = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 10;
			$wpcp_pagination = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;
			$wpcp_layout     = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			$final_args      = array();
			if ( 'post-carousel' === $carousel_type ) {
					$number_of_total_posts = isset( $upload_data['number_of_total_posts'] ) && ! empty( $upload_data['number_of_total_posts'] ) ? $upload_data['number_of_total_posts'] : -1;
					$include_current_post  = apply_filters( 'sp_wpcp_include_current_post', false );
					$args                  = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'fields'         => 'ids',
						'orderby'        => $post_order_by,
						'order'          => $post_order, // If used random order, Randomly limited ids come from all ids.
						'posts_per_page' => $number_of_total_posts,
						'post__not_in'   => array( get_the_ID() ),
					);
					if ( $include_current_post ) {
						unset( $args['post__not_in'] );
					}
					// Get array of all queried members id.
					$queried_post_ids      = get_posts( $args );
					$number_of_total_posts = count( $queried_post_ids );
					if ( ! empty( $queried_post_ids ) ) {
						if ( 'carousel' !== $wpcp_layout && $wpcp_pagination ) {
							$wppaged    = 'paged' . $post_id;
							$paged      = isset( $_GET[ "$wppaged" ] ) ? $_GET[ "$wppaged" ] : 1;
							$final_args = array(
								'post_type'           => 'post',
								'post_status'         => 'publish',
								'order'               => $post_order,
								'orderby'             => $post_order_by,
								'posts_per_page'      => $post_per_page,
								'paged'               => $paged,
								'ignore_sticky_posts' => 1,
								'suppress_filters'    => apply_filters( 'sp_wpcp_suppress_filters', false ),
								'post__in'            => $queried_post_ids,
							);
						} else {
							$final_args = array(
								'post_type'           => 'post',
								'post_status'         => 'publish',
								'order'               => $post_order,
								'orderby'             => $post_order_by,
								'ignore_sticky_posts' => 1,
								'suppress_filters'    => apply_filters( 'sp_wpcp_suppress_filters', false ),
								'post__in'            => $queried_post_ids,
								'posts_per_page'      => $number_of_total_posts,
							);
						}
					}
			}
			if ( 'product-carousel' === $carousel_type && ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) ) {
				$number_of_total_products = isset( $upload_data['wpcp_total_products'] ) && ! empty( $upload_data['wpcp_total_products'] ) ? $upload_data['wpcp_total_products'] : -1;
				$default_args             = array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $number_of_total_products,
					'fields'              => 'ids',
					'orderby'             => $post_order_by,
					'order'               => $post_order,
					'meta_query'          => array(
						array(
							'key'     => '_stock_status',
							'value'   => 'outofstock',
							'compare' => 'NOT IN',
						),
					),
				);

				$queried_post_ids      = get_posts( $default_args );
				$number_of_total_posts = count( $queried_post_ids );

				if ( ! empty( $queried_post_ids ) ) {

					if ( 'carousel' !== $wpcp_layout && $wpcp_pagination ) {
						$wppaged    = 'paged' . $post_id;
						$paged      = isset( $_GET[ "$wppaged" ] ) ? $_GET[ "$wppaged" ] : 1;
						$final_args = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'order'               => $post_order,
							'orderby'             => $post_order_by,
							'posts_per_page'      => $post_per_page,
							'paged'               => $paged,
							'ignore_sticky_posts' => 1,
							'suppress_filters'    => apply_filters( 'sp_wpcp_suppress_filters', false ),
							'post__in'            => $queried_post_ids,
						);
					} else {
						$final_args = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'ignore_sticky_posts' => 1,
							'order'               => $post_order,
							'orderby'             => $post_order_by,
							'suppress_filters'    => apply_filters( 'sp_wpcp_suppress_filters', false ),
							'post__in'            => $queried_post_ids,
							'posts_per_page'      => $number_of_total_posts,
						);
					}
				}
			}
			$post_query = new WP_Query( $final_args );

			return $post_query;
		}

	}

}

