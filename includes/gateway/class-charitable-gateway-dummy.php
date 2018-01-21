<?php
/**
 * Dummy Gateway class
 *
 * @version     1.1.3
 * @package     Charitable/Classes/Charitable_Gateway_Dummy
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Gateway_Dummy' ) ) :

	/**
	 * Dummy Gateway.
	 *
	 * @since       1.0.0
	 */
	class Charitable_Gateway_Dummy extends Charitable_Gateway {

		/**
		 * The gateway ID.
		 *
		 * @var     string
		 */
		const ID = 'dummy';

		/**
		 * The key to use to store a customer ID.
		 *
		 * @var     string
		 */
		const DUMMY_CUSTOMER_ID_KEY = 'dummy_customer_id';

		/**
		 * The key to use to store a customer ID.
		 *
		 * @var     string
		 */
		const DUMMY_CUSTOMER_ID_KEY_TEST = 'dummy_customer_id_test';

		/**
		 * The array of charges to make in a single transaction.
		 *
		 * @var     array
		 */
		private $charges = array();

		/**
		 * Instantiate the gateway class, defining its key values.
		 *
		 * @access  public
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->name = apply_filters( 'charitable_gateway_dummy_name', __( 'Dummy Gateway', 'charitable-dummy' ) );

			$this->defaults = array(
				'label' => __( 'Dummy Payment', 'charitable-dummy' ),
			);

			$this->supports = array(
				'1.3.0',
				'recurring',
			);

		}

		/**
		 * Register the Dummy payment gateway class.
		 *
		 * @param   string[] $gateways The list of registered gateways.
		 * @return  string[]
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function register_gateway( $gateways ) {
			$gateways['dummy'] = 'Charitable_Gateway_Dummy';
			return $gateways;
		}

		/**
		 * Register gateway settings.
		 *
		 * @param   array $settings The existing settings to display for the Dummy settings page.
		 * @return  array
		 * @access  public
		 * @since   1.0.0
		 */
		public function gateway_settings( $settings ) {

			return $settings;
		}

		/**
		 * Returns the current gateway's ID.
		 *
		 * @return  string
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function get_gateway_id() {
			return self::ID;
		}

		/**
		 * Return the keys to use.
		 *
		 * This will return the test keys if test mode is enabled. Otherwise, returns
		 * the production keys.
		 *
		 * @param   boolean $force_test_mode Forces the test API keys to be used.
		 * @return  string[]
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_keys( $force_test_mode = false ) {
			return array();
		}

		/**
		 * Load Dummy JS or Dummy Checkout, as well as our handling scripts.
		 *
		 * @return  boolean
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function enqueue_scripts() {
			return true;
		}


		/**
		 * Load Dummy JS or Dummy Checkout, as well as our handling scripts.
		 *
		 * @uses    Charitable_Gateway_Dummy::enqueue_scripts()
		 *
		 * @param   Charitable_Donation_Form $form The current form object.
		 * @return  boolean
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function maybe_setup_scripts_in_donation_form( $form ) {

			if ( ! is_a( $form, 'Charitable_Donation_Form' ) ) {
				return false;
			}

			if ( 'make_donation' !== $form->get_form_action() ) {
				return false;
			}

			return self::enqueue_scripts();
		}

		/**
		 * Enqueue the Dummy JS/Checkout scripts after a campaign loop if modal donations are in use.
		 *
		 * @uses    Charitable_Gateway_Dummy::enqueue_scripts()
		 *
		 * @return  boolean
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function maybe_setup_scripts_in_campaign_loop() {

			if ( 'modal' !== charitable_get_option( 'donation_form_display', 'separate_page' ) ) {
				return false;
			}

			return self::enqueue_scripts();
		}

		/**
		 * Return the submitted value for a gateway field.
		 *
		 * @param   string  $key The key of the value we want to get.
		 * @param   mixed[] $values An values in which to search.
		 * @return  string|false
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_gateway_value( $key, $values ) {

			if ( isset( $values['gateways']['dummy'][ $key ] ) ) {
				return $values['gateways']['dummy'][ $key ];
			}

			return false;
		}

		/**
		 * Return the submitted value for a gateway field.
		 *
		 * @param   string                        $key The key of the value we want to get.
		 * @param   Charitable_Donation_Processor $processor The Donation Processor helper object.
		 * @return  string|false
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_gateway_value_from_processor( $key, Charitable_Donation_Processor $processor ) {
			return $this->get_gateway_value( $key, $processor->get_donation_data() );
		}

		/**
		 * Add hidden token field to the donation form.
		 *
		 * @param   array $fields The donation form's hidden fields.
		 * @return  array $fields
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function add_hidden_token_field( $fields ) {

			if ( Charitable_Gateways::get_instance()->is_active_gateway( self::get_gateway_id() ) ) {
				$fields['dummy_token'] = '';
			}

			return $fields;

		}

		/**
		 * If a Dummy token was submitted, set it to the gateways array.
		 *
		 * @param   array $fields The filtered values from the donation form submission.
		 * @param   array $submitted The raw POST data.
		 * @return  array
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function set_submitted_dummy_token( $fields, $submitted ) {

			$token = isset( $submitted['dummy_token'] ) ? $submitted['dummy_token'] : false;
			$fields['gateways']['dummy']['token'] = $token;
			return $fields;

		}

		/**
		 * Validate the submitted credit card details.
		 *
		 * @param   boolean $valid Whether the donation is valid.
		 * @param   string  $gateway The chosen gateway.
		 * @param   mixed[] $values The filtered values from the donation form submission.
		 * @return  boolean
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function validate_donation( $valid, $gateway, $values ) {

			if ( 'dummy' !== $gateway ) {
				return $valid;
			}

			if ( ! isset( $values['gateways']['dummy'] ) ) {
				return false;
			}

			return $valid;

		}

		/**
		 * Process the donation with the gateway, seamlessly over the Dummy API.
		 *
		 * @param   mixed                         $return The result of the gateway processing.
		 * @param   int                           $donation_id The donation ID.
		 * @param   Charitable_Donation_Processor $processor The Donation Processor helper.
		 * @return  boolean
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function process_donation( $return, $donation_id, $processor ) {

			$donation    = new Charitable_Donation( $donation_id );
			$donation->update_status( 'charitable-completed' );

			return true;
		}


		/**
		 * Get the donation amount in the smallest common currency unit.
		 *
		 * @param 	float       $amount   The donation amount in dollars.
		 * @param 	string|null $currency The currency of the donation. If null, the site currency will be used.
		 * @return  int
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function get_amount( $amount, $currency = null ) {

			/* Unless it's a zero decimal currency, multiply the currency x 100 to get the amount in cents. */
			if ( self::is_zero_decimal_currency( $currency ) ) {
				$amount = $amount * 1;
			} else {
				$amount = $amount * 100;
			}

			return $amount;
		}

		/**
		 * Returns whether the currency is a zero decimal currency.
		 *
		 * @param 	string $currency The currency for the charge. If left blank, will check for the site currency.
		 * @return  bool
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function is_zero_decimal_currency( $currency = null ) {
			if ( is_null( $currency ) ) {
				$currency = charitable_get_currency();
			}

			return in_array( strtoupper( $currency ), self::get_zero_decimal_currencies() );
		}

		/**
		 * Return all zero-decimal currencies supported by Dummy.
		 *
		 * @return  array
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function get_zero_decimal_currencies() {
			return array(
				'BIF',
				'CLP',
				'DJF',
				'GNF',
				'JPY',
				'KMF',
				'KRW',
				'MGA',
				'PYG',
				'RWF',
				'VND',
				'VUV',
				'XAF',
				'XOF',
				'XPF',
			);
		}

	}

endif;
