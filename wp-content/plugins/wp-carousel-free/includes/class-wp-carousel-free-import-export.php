<?php
/**
 * Custom import export.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Wp_Carousel_free.
 * @subpackage WP_Carousel_free/includes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom import export.
 */
class Wp_Carousel_Free_Import_Export {

	/**
	 * Export
	 *
	 * @param  mixed $shortcode_ids Export Wp carousel shortcode ids.
	 * @return object
	 */
	public function export( $shortcode_ids ) {
		$export = array();
		if ( ! empty( $shortcode_ids ) ) {

			$post_in    = 'all_shortcodes' === $shortcode_ids ? '' : $shortcode_ids;
			$args       = array(
				'post_type'        => 'sp_wp_carousel',
				'post_status'      => array( 'inherit', 'publish' ),
				'orderby'          => 'modified',
				'suppress_filters' => 1, // wpml, ignore language filter.
				'posts_per_page'   => -1,
				'post__in'         => $post_in,
			);
			$shortcodes = get_posts( $args );
			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					$shortcode_export = array(
						'title'       => $shortcode->post_title,
						'original_id' => $shortcode->ID,
						'meta'        => array(),
					);
					foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
						$shortcode_export['meta'][ $metakey ] = maybe_unserialize( $value[0] );
					}
					$str  = isset( $shortcode_export['meta']['sp_wpcp_upload_options'] ) ? maybe_unserialize( $shortcode_export['meta']['sp_wpcp_upload_options'] ) : '';
					$data = maybe_unserialize( $str );
					if ( 'image-carousel' === $data['wpcp_carousel_type'] ) {
						$image_gallery      = explode( ',', $data['wpcp_gallery'] );
						$gallery_attachment = array();
						foreach ( $image_gallery as $gallery_img ) {
							if ( $gallery_img ) {
								$gallery_attachment[] = wp_get_attachment_image_src( $gallery_img, 'full' )[0];
							}
						}
						$shortcode_export['gallery_img_url'] = $gallery_attachment;
					}
					$export['shortcode'][] = $shortcode_export;

					unset( $shortcode_export );
				}
				$export['metadata'] = array(
					'version' => WPCAROUSELF_VERSION,
					'date'    => gmdate( 'Y/m/d' ),
				);
			}
			return $export;
		}
	}

	/**
	 * Export Wp_carousel by ajax.
	 *
	 * @return void
	 */
	public function export_shortcodes() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'wpcf_options_nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Invalid nonce verification.', 'wp-carousel-free' ),
				),
				401
			);
		}
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$shortcode_ids = isset( $_POST['wpcf_ids'] ) ? wp_unslash( $_POST['wpcf_ids'] ) : ''; // phpcs:ignore
		// Sanitize "post" request of field.
		if ( is_array( $shortcode_ids ) ) {
			$shortcode_ids = array_map( 'sanitize_text_field', $shortcode_ids );
		} else {
			$shortcode_ids = sanitize_text_field( $shortcode_ids );
		}
		$export = $this->export( $shortcode_ids );

		if ( is_wp_error( $export ) ) {
			wp_send_json_error(
				array(
					'message' => $export->get_error_message(),
				),
				400
			);
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			echo wp_json_encode( $export, JSON_PRETTY_PRINT );
			die;
		}

		wp_send_json( $export, 200 );
	}
	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  String $url remote url.
	 * @param  Int    $parent_post_id parent post id.
	 * @return Int    Attachment ID
	 */
	public function insert_attachment_from_url( $url, $parent_post_id = null ) {

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once ABSPATH . WPINC . '/class-http.php';
		}
		$attachment_title = sanitize_file_name( pathinfo( $url, PATHINFO_FILENAME ) );
		// Does the attachment already exist ?
		$attachment_id = post_exists( $attachment_title, '', '', 'attachment' );
		if ( $attachment_id ) {
			return $attachment_id;
		}

		$http     = new \WP_Http();
		$response = $http->request( $url );
		if ( 200 !== $response['response']['code'] ) {
			return false;
		}
		$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path     = $upload['file'];
		$file_name     = basename( $file_path );
		$file_type     = wp_check_filetype( $file_name, null );
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment.
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;

	}

	/**
	 * Import Image and shortcode.
	 *
	 * @param  mixed $shortcodes Import logo and carousel shortcode array.
	 *
	 * @throws \Exception .
	 * @return object
	 */
	public function import( $shortcodes ) {
		$errors = array();
		foreach ( $shortcodes as $index => $shortcode ) {
			$errors[ $index ] = array();
			$new_shortcode_id = 0;
			try {
				$new_shortcode_id = wp_insert_post(
					array(
						'post_title'  => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
						'post_status' => 'publish',
						'post_type'   => 'sp_wp_carousel',
					),
					true
				);
				// For image Gallery.
				$image_gallery = isset( $shortcode['gallery_img_url'] ) ? $shortcode['gallery_img_url'] : '';
				if ( ! empty( $image_gallery ) ) {
					$gallery_id = array();
					foreach ( $image_gallery as $img_url ) {
						$image_id = $this->insert_attachment_from_url( $img_url, $new_shortcode_id );
						if ( $image_id ) {
							$gallery_id[] = $image_id;
						}
					}
					$gallery_img_url_id                          = implode( ',', $gallery_id );
					$option_data                                 = $shortcode['meta']['sp_wpcp_upload_options'];
					$option_data['wpcp_gallery']                 = $gallery_img_url_id;
					$shortcode['meta']['sp_wpcp_upload_options'] = $option_data;
				}
				if ( is_wp_error( $new_shortcode_id ) ) {
					throw new Exception( $new_shortcode_id->get_error_message() );
				}

				if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
					foreach ( $shortcode['meta'] as $key => $value ) {
						update_post_meta(
							$new_shortcode_id,
							$key,
							$value
						);
					}
				}
			} catch ( Exception $e ) {
				array_push( $errors[ $index ], $e->getMessage() );

				// If there was a failure somewhere, clean up.
				wp_trash_post( $new_shortcode_id );
			}

			// If no errors, remove the index.
			if ( ! count( $errors[ $index ] ) ) {
				unset( $errors[ $index ] );
			}

			// External modules manipulate data here.
			do_action( 'sp_wp_carousel_shortcode_imported', $new_shortcode_id );
		}

		$errors = reset( $errors );
		return isset( $errors[0] ) ? new WP_Error( 'import_wp_carousel_error', $errors[0] ) : '';
	}

	/**
	 * Import WP-Carousel by ajax.
	 *
	 * @return void
	 */
	public function import_shortcodes() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'wpcf_options_nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error: Invalid nonce verification.', 'wp-carousel-free' ),
				),
				401
			);
		}
		$data       = isset( $_POST['shortcode'] ) ? wp_kses_data( wp_unslash( $_POST['shortcode'] ) ) : '';
		$data       = json_decode( $data );
		$data       = json_decode( $data, true );
		$shortcodes = $data['shortcode'];
		if ( ! $data ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nothing to import.', 'wp-carousel-free' ),
				),
				400
			);
		}

		$status = $this->import( $shortcodes );

		if ( is_wp_error( $status ) ) {
			wp_send_json_error(
				array(
					'message' => $status->get_error_message(),
				),
				400
			);
		}

		wp_send_json_success( $status, 200 );
	}
}
