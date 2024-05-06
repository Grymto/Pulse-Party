<?php
/**
 * Fired during plugin updates
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.7
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/includes
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 */
class WP_Carousel_Free_Updates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.1.7'  => 'updates/update-2.1.7.php',
		'2.4.7'  => 'updates/update-2.4.7.php',
		'2.4.11' => 'updates/update-2.4.11.php',
		'2.4.12' => 'updates/update-2.4.12.php',
		'2.6.0'  => 'updates/update-2.6.0.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.1.7
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ), 11 );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.1.7
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'wp_carousel_free_version' );

		if ( false === $installed_version ) {
			update_option( 'wp_carousel_free_version', WPCAROUSELF_VERSION );
			update_option( 'wp_carousel_free_db_version', WPCAROUSELF_VERSION );
		}

		if ( version_compare( $installed_version, WPCAROUSELF_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 2.1.7
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 2.1.7
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'wp_carousel_free_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'wp_carousel_free_version', $version );
			}
		}

		update_option( 'wp_carousel_free_version', WPCAROUSELF_VERSION );

	}

}
new WP_Carousel_Free_Updates();
