<?php
/**
 * Add recurring donations support.
 *
 * @version     1.0.2
 * @package     Charitable Dummy/Classes/Charitable_Dummy_Recurring
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Dummy_Recurring' ) ) :

	/**
	 * Dummy Payment Gateway support
	 *
	 * @since       1.0.0
	 */
	class Charitable_Dummy_Recurring {

		/**
		 * The single instance of this class.
		 *
		 * @var     Charitable_Dummy_Recurring|null
		 * @access  private
		 * @static
		 */
		private static $instance = null;

		/**
		 * Returns and/or create the single instance of this class.
		 *
		 * @return  Charitable_Dummy_Recurring
		 * @since   1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add subscription data for the donation to the transaction object.
		 *
		 * @param   mixed                         $return The result of the gateway processing.
		 * @param   int                           $donation_id The donation ID.
		 * @param   Charitable_Donation_Processor $processor The Donation Processor helper.
		 * @return  boolean
		 * @since   1.0.0
		 */
		public function maybe_process_recurring_donation( $return, $donation_id, Charitable_Donation_Processor $processor ) {

			/* Bail straight away if no donation plan is set. */
			if ( ! $processor->get_donation_data_value( 'donation_plan', false ) ) {
				return $return;
			}

			$recurring_id = $processor->get_donation_data_value( 'donation_plan' );

			if ( ! $recurring_id ) {
				return $return;
			}

			$recurring 	  = charitable_get_donation( $recurring_id );

			/* Save the subscription ID. */
			$sub_id = time();
			$recurring->set_gateway_subscription_id( $sub_id );

			$recurring->update_donation_log( sprintf( __( 'Dummy subscription ID: <code>%s</code>', 'charitable-dummy' ),
				$sub_id
			) );

			$recurring->update_status( 'charitable-active' );

			// Schedule first renewal.
			if ( is_callable( array( $recurring, 'get_renewal_date' ) ) ) {
				wp_schedule_single_event( $recurring->get_renewal_date( 'U' ), 'charitable_recurring_process_dummy_renewal', array( 'donation' => $recurring ) );
			}

			return true;

		}


		/**
		 * Get a donation by gateway transaction ID.
		 *
		 * @param 	string $transaction_id The gateway transaction ID.
		 * @return  int|null
		 * @access  private
		 * @since   1.0.0
		 */
		private function get_donation_by_gateway_transaction_id( $transaction_id ) {

			if ( function_exists( 'charitable_get_donation_by_transaction_id' ) ) {
				return charitable_get_donation_by_transaction_id( $transaction_id );
			}

			global $wpdb;

			$sql = "SELECT post_id 
					FROM $wpdb->postmeta 
					WHERE meta_key = '_gateway_transaction_id' 
					AND meta_value = %s";

			return $wpdb->get_var( $wpdb->prepare( $sql, $transaction_id ) );

		}

		/**
		 * Determines if the donation can be suspended
		 *
		 * @param   bool $can
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function can_suspend( $can, $donation ) {
			if ( $can && ! empty( $donation->get_gateway_subscription_id() ) && charitable_recurring_is_approved_status( $donation->get_status() ) ) {
				$can = true;
			}
			return $can;
		}

		/**
		 * Suspends a recurring donation.
		 *
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function suspend( $donation ) {
			return true;
		}

		/**
		 * Determines if the donation can be cancelled
		 *
		 * @param   bool $can
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function can_cancel( $can, $donation ) {
			return $can;
		}

		/**
		 * Cancels a recurring donation.
		 *
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function cancel( $donation ) {
			return true;
		}

		/**
		 * Determines if the donation can be reactivated
		 *
		 * @param   bool $can
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function can_reactivate( $can, $donation ) {
			if ( $donation->get_gateway() === 'dummy' && ! empty( $donation->get_gateway_subscription_id() ) && 'charitable-suspended' == $donation->get_status() ) {
				$can = true;
			}
			return $can;
		}

		/**
		 * Reactivates a recurring donation.
		 *
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @return  boolean
		 * @since   1.0.1
		 */
		public function reactivate( $donation ) {
			return true;
		}

		/**
		 * Trigger renewal on cron and schedule next task.
		 *
		 * @param   obj Charitable_Recurring_Donation $donation
		 * @since   1.1.0
		 */
		public function renew( $donation ) {

			// Only renew if active.
			if ( $donation->has_status( 'charitable-active' ) ) {

				$donation->create_renewal_donation( [ 'status' => 'charitable-completed' ] );
				$donation->renew();

				// Schedule next renewal if still active.
				if ( $donation->has_status( 'charitable-active' ) && is_callable( array( $donation, 'get_expiration_date' ) ) ) {
					wp_schedule_single_event( $donation->get_expiration_date( 'U' ), 'charitable_recurring_process_dummy_renewal', array( 'donation' => $donation ) );
				}

			}

		}
	}

endif;
