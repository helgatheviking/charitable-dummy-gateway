<?php
/**
 * Sets up translations for Charitable Dummy.
 *
 * @package     Charitable Dummy/Classes/i18n
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Dummy_i18n' ) ) :

	/**
	 * Charitable_Dummy_i18n
	 *
	 * @since       1.0.0
	 */
	class Charitable_Dummy_i18n extends Charitable_i18n {

		/**
		 * The single instance of this class.
		 *
		 * @var     Charitable_Dummy_i18n|null
		 * @access  private
		 * @static
		 */
		private static $instance = null;

		/**
		 * Text domain for the plugin.
		 *
		 * @var     string
		 * @access 	protected
		 */
		protected $textdomain = 'charitable-dummy';

		/**
		 * Set up the class.
		 *
		 * @access  private
		 * @since   1.0.0
		 */
		private function __construct() {
			$this->languages_directory = apply_filters( 'charitable_dummy_languages_directory', 'charitable-dummy/languages' );
			$this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
			$this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

			$this->load_textdomain();
		}

		/**
		 * Returns and/or create the single instance of this class.
		 *
		 * @return  Charitable_Dummy_i18n
		 * @access  public
		 * @since   1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Charitable_Dummy_i18n();
			}

			return self::$instance;
		}
	}

endif;
