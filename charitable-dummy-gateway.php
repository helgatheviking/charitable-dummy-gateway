<?php
/**
 * Plugin Name: 		Charitable - Dummy Payment Gateway
 * Plugin URI: 			https://github.com/helgatheviking/charitable-dummy-gateway
 * Description: 		Adds the ability to test Charitable donations without spending any money.
 * Version: 			1.0.2
 * Author: 				Kathy Darling
 * Author URI: 			https://www.kathyisawesome.com/
 * Requires at least: 	5.0.0
 * Tested up to: 		5.2.0
 *
 * Text Domain: 		charitable-dummy
 * Domain Path: 		/languages/
 *
 * @package 			Charitable Dummy Gateway
 * @category 			Core
 * @author 				Kathy Darling
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Load plugin class, but only if Charitable is activated.
 *
 * @return 	boolean Whether the class is loaded.
 * @since 	1.0.0
 */
function charitable_dummy_load() {
	require_once( 'includes/class-charitable-dummy.php' );

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {

		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {
			require_once 'includes/admin/class-charitable-extension-activation.php';
		}

		if ( is_admin() ) {
			$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
			$activation = $activation->run();
		}

		return false;

	} else {

		new Charitable_Dummy( __FILE__ );

		return true;

	}
}

add_action( 'plugins_loaded', 'charitable_dummy_load', 1 );
