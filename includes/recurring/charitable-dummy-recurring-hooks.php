<?php
/**
 * Charitable Recurring Dummy standard Hooks.
 *
 * Action/filter hooks used for adding support for recurring donations to Dummy gateway.
 *
 * @package     Charitable Dummy/Functions/Recurring Donations
 * @version     1.0.1
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Maybe process a recurring donation.
 *
 * @see     Charitable_Dummy_Recurring::maybe_process_recurring_donation()
 */
add_filter( 'charitable_process_donation_dummy', array( Charitable_Dummy_Recurring::get_instance(), 'maybe_process_recurring_donation' ), 2, 3 );

/**
 * Create a plan in the gateway.
 *
 * @see     Charitable_Dummy_Recurring::create_recurring_donation_plan()
 */
add_filter( 'charitable_recurring_create_gateway_plan_dummy', array( Charitable_Dummy_Recurring::get_instance(), 'create_recurring_donation_plan' ), 10, 4 );

/**
 * Donation can be suspended in gateway.
 *
 * @see     Charitable_Dummy_Recurring::can_suspend()
 */
add_filter( 'charitable_recurring_can_suspend', array( Charitable_Dummy_Recurring::get_instance(), 'can_suspend' ), 10, 2 );

/**
 * Suspend donation in the gateway.
 *
 * @see     Charitable_Dummy_Recurring::suspend()
 */
add_filter( 'charitable_recurring_suspend_dummy_donation', array( Charitable_Dummy_Recurring::get_instance(), 'suspend' ) );

/**
 * Donation can be cancelled in gateway.
 *
 * @see     Charitable_Dummy_Recurring::can_cancel()
 */
add_filter( 'charitable_recurring_can_cancel', array( Charitable_Dummy_Recurring::get_instance(), 'can_cancel' ), 10, 2 );

/**
 * Cancel donation in the gateway.
 *
 * @see     Charitable_Dummy_Recurring::cancel()
 */
add_filter( 'charitable_recurring_cancel_dummy_donation', array( Charitable_Dummy_Recurring::get_instance(), 'cancel' ) );

/**
 * Donation can be reactivated in gateway.
 *
 * @see     Charitable_Dummy_Recurring::can_reactivate()
 */
add_filter( 'charitable_recurring_can_reactivate', array( Charitable_Dummy_Recurring::get_instance(), 'can_reactivate' ), 10, 2 );

/**
 * Reactivate donation in the gateway.
 *
 * @see     Charitable_Dummy_Recurring::reactivate()
 */
add_filter( 'charitable_recurring_reactivate_dummy_donation', array( Charitable_Dummy_Recurring::get_instance(), 'reactivate' ) );
