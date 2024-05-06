<?php
/**
 * Framework sanitize
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wpcf_sanitize_replace_a_to_b' ) ) {
	/**
	 *
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @param  string $value string.
	 *
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_replace_a_to_b( $value ) {
		return str_replace( 'a', 'b', $value );
	}
}


if ( ! function_exists( 'wpcf_sanitize_title' ) ) {
	/**
	 *
	 * Sanitize title
	 *
	 * @param  string $value string.
	 *
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_title( $value ) {
		return sanitize_title( $value );
	}
}

if ( ! function_exists( 'wpcf_sanitize_number_array_field' ) ) {
	/**
	 *
	 * Sanitize number array
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_number_array_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key = sanitize_key( $key );
			if ( 'unit' === $key || 'units' === $key ) {
				$new_array[ $sanitize_key ] = wp_filter_nohtml_kses( $value );
			} else {
				$new_array[ $sanitize_key ] = intval( $value );
			}
		}
		return $new_array;
	}
}

if ( ! function_exists( 'wpcf_sanitize_number_field' ) ) {
	/**
	 *
	 * Sanitize number
	 *
	 * @param  mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_number_field( $value ) {
		if ( empty( $value ) ) {
			return 0;
		} else {
			return intval( $value );
		}
	}
}

if ( ! function_exists( 'wpcf_sanitize_border_field' ) ) {
	/**
	 *
	 * Sanitize border field
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_border_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key = sanitize_key( $key );
			if ( 'style' == $key || strpos( $key, 'color' ) !== false ) {
				$new_array[ $sanitize_key ] = sanitize_text_field( $value );
			} elseif ( ! empty( $value ) ) {
				$new_array[ $sanitize_key ] = intval( $value );
			}
		}
		return $new_array;
	}
}

if ( ! function_exists( 'wpcf_sanitize_color_group_field' ) ) {
	/**
	 *
	 * Sanitize color group field
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcf_sanitize_color_group_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key               = sanitize_key( $key );
			$new_array[ $sanitize_key ] = sanitize_text_field( $value );
		}
		return $new_array;
	}
}
