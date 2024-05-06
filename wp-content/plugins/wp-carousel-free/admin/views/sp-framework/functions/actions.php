<?php
/**
 * Framework Actions
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.



if ( ! function_exists( 'wpcf_reset_ajax' ) ) {
	/**
	 *
	 * Reset Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_reset_ajax() {

		$nonce  = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$unique = ( ! empty( $_POST['unique'] ) ) ? sanitize_text_field( wp_unslash( $_POST['unique'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wpcf_backup_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wp-carousel-free' ) ) );
		}

		// Success.
		delete_option( $unique );

		wp_send_json_success();

	}
	add_action( 'wp_ajax_wpcf-reset', 'wpcf_reset_ajax' );
}


if ( ! function_exists( 'wpcf_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_chosen_ajax() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( $_POST['query_args'] ) : array();

		if ( ! wp_verify_nonce( $nonce, 'wpcf_chosen_ajax_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wp-carousel-free' ) ) );
		}

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'wp-carousel-free' ) ) );
		}

		$capability = apply_filters( 'wpcf_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'wp-carousel-free' ) ) );
		}

		// Success.
		$options = SP_WPCF_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );

	}
	add_action( 'wp_ajax_wpcf-chosen', 'wpcf_chosen_ajax' );
}

if ( ! function_exists( 'wpcf_get_option' ) ) {

	/**
	 * Get option
	 *
	 * @param  mixed $option_name name.
	 * @param  mixed $default default.
	 * @return mixed
	 */
	function wpcf_get_option( $option_name = '', $default = '' ) {

		$options = apply_filters( 'wpcf_get_option', get_option( 'sp_wpcp_settings' ), $option_name, $default );

		if ( isset( $option_name ) && isset( $options[ $option_name ] ) ) {
			return $options[ $option_name ];
		} else {
			return ( isset( $default ) ) ? $default : null;
		}

	}
}

if ( ! function_exists( 'wpcf_get_all_option' ) ) {
	/**
	 *
	 * Get all option
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_get_all_option() {
		return get_option( '_wpcf_options' );
	}
}
