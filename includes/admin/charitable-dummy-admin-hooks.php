<?php
/**
 * Charitable Dummy admin hooks.
 *
 * @package     Charitable Dummy /Functions/Admin
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Add a direct link to the Extensions settings page from the plugin row.
 *
 * @see     Charitable_Dummy_Admin::add_plugin_action_links()
 */
add_filter( 'plugin_action_links_' . plugin_basename( Charitable_Dummy::get_instance()->get_path() ), array( Charitable_Dummy_Admin::get_instance(), 'add_plugin_action_links' ) );
