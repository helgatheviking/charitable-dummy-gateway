<?php
/**
 * Charitable Dummy Gateway Hooks.
 *
 * Action/filter hooks used for handling payments through the Dummy gateway.
 *
 * @package     Charitable Dummy/Hooks/Gateway
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Register our new gateway.
 *
 * @see     Charitable_Gateway_Dummy::register_gateway()
 */
add_filter( 'charitable_payment_gateways', array( 'Charitable_Gateway_Dummy', 'register_gateway' ) );

/**
 * Set up Dummy JS or Dummy Checkout in the donation form.
 *
 * @see     Charitable_Gateway_Dummy::setup_scripts()
 */
add_action( 'charitable_form_after_fields', array( 'Charitable_Gateway_Dummy', 'maybe_setup_scripts_in_donation_form' ) );

/**
 * Maybe enqueue the Dummy JS/Checkout scripts after a campaign loop, if modal donations are in use.
 *
 * @see     Charitable_Gateway_Dummy::maybe_setup_scripts_in_campaign_loop()
 */
add_action( 'charitable_campaign_loop_after', array( 'Charitable_Gateway_Dummy', 'maybe_setup_scripts_in_campaign_loop' ) );

/**
 * Include the Dummy token field in the donation form.
 *
 * @see     Charitable_Gateway_Dummy::add_hidden_token_field()
 */
add_filter( 'charitable_donation_form_hidden_fields', array( 'Charitable_Gateway_Dummy', 'add_hidden_token_field' ) );

/**
 * Also make sure that the Dummy token is picked up in the values array.
 *
 * @see     Charitable_Gateway_Dummy::set_submitted_dummy_token()
 */
add_filter( 'charitable_donation_form_submission_values', array( 'Charitable_Gateway_Dummy', 'set_submitted_dummy_token' ), 10, 2 );

/**
 * Validate the donation form submission before processing.
 *
 * @see     Charitable_Gateway_Dummy::validate_donation()
 */
add_filter( 'charitable_validate_donation_form_submission_gateway', array( 'Charitable_Gateway_Dummy', 'validate_donation' ), 10, 3 );

/**
 * Process the donation.
 *
 * @see     Charitable_Gateway_Dummy::process_donation()
 */
add_filter( 'charitable_process_donation_dummy', array( 'Charitable_Gateway_Dummy', 'process_donation' ), 10, 3 );