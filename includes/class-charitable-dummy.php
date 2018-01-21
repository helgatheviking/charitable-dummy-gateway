<?php
/**
 * The main Charitable Stripe class.
 *
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package     Charitable Stripe
 * @copyright   Copyright (c) 2018, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Dummy' ) ) :

	/**
	 * Charitable_Dummy
	 *
	 * @since   1.0.0
	 */
	class Charitable_Dummy {

		/**
		 * The plugin version.
		 */
		const VERSION = '1.0.0';

		/**
		 * The plugin database version.
		 */
		const DB_VERSION = '20180115';

		/**
		 * The product name.
		 */
		const NAME = 'Charitable Dummy';

		/**
		 * The plugin author name.
		 */
		const AUTHOR = 'Kathy Darling';

		/**
		 * The one and only class instance.
		 *
		 * @var 	Charitable_Dummy
		 * @static
		 * @access 	private
		 */
		private static $instance = null;

		/**
		 * The root file of the plugin.
		 *
		 * @var     string
		 * @access  private
		 */
		private $plugin_file;

		/**
		 * The root directory of the plugin.
		 *
		 * @var     string
		 * @access  private
		 */
		private $directory_path;

		/**
		 * The root directory of the plugin as a URL.
		 *
		 * @var     string
		 * @access  private
		 */
		private $directory_url;

		/**
		 * Create class instance.
		 *
		 * @param 	string $plugin_file The path to the main plugin file.
		 * @return  void
		 * @since   1.0.0
		 */
		public function __construct( $plugin_file ) {
			$this->plugin_file    = $plugin_file;
			$this->directory_path = plugin_dir_path( $plugin_file );
			$this->directory_url  = plugin_dir_url( $plugin_file );

			add_action( 'charitable_start', array( $this, 'start' ), 1 );
		}

		/**
		 * Returns the original instance of this class.
		 *
		 * @return  Charitable_Dummy
		 * @since   1.0.0
		 */
		public static function get_instance() {
			return self::$instance;
		}

		/**
		 * Run the startup sequence on the charitable_start hook.
		 *
		 * This is only ever executed once.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function start() {
			/* If we've already started (i.e. run this function once before), do not pass go. */
			if ( $this->started() ) {
				return;
			}

			/* Set static instance. */
			self::$instance = $this;

			$this->load_dependencies();

			$this->setup_licensing();

			$this->setup_i18n();

			$this->maybe_start_admin();

			$this->attach_hooks_and_filters();

			// Hook in here to do something when the plugin is first loaded.
			do_action( 'charitable_dummy_start', $this );
		}

		/**
		 * Include necessary files.
		 *
		 * @return  void
		 * @access  private
		 * @since   1.0.0
		 */
		private function load_dependencies() {
			require_once( $this->get_path( 'includes' ) . 'i18n/class-charitable-dummy-i18n.php' );
			require_once( $this->get_path( 'includes' ) . 'gateway/class-charitable-gateway-dummy.php' );
			require_once( $this->get_path( 'includes' ) . 'gateway/charitable-dummy-gateway-hooks.php' );

			/* Recurring Donations */
			if ( class_exists( 'Charitable_Recurring' ) ) {
				require_once( $this->get_path( 'includes' ) . 'recurring/class-charitable-dummy-recurring.php' );
				require_once( $this->get_path( 'includes' ) . 'recurring/charitable-dummy-recurring-hooks.php' );
			}
		}

		/**
		 * Set up hook and filter callback functions.
		 *
		 * @return  void
		 * @access  private
		 * @since   1.0.0
		 */
		private function attach_hooks_and_filters() {}

		/**
		 * Set up licensing for the extension.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_licensing() {}

		/**
		 * Set up the internationalisation for the plugin.
		 *
		 * @return  void
		 * @access  private
		 * @since   0.1.0
		 */
		private function setup_i18n() {
			if ( class_exists( 'Charitable_i18n' ) ) {

				require_once( $this->get_path( 'includes' ) . 'i18n/class-charitable-dummy-i18n.php' );

				Charitable_Dummy_i18n::get_instance();
			}
		}

		/**
		 * Load the admin-only functionality.
		 *
		 * @return  void
		 * @access  private
		 * @since   1.0.0
		 */
		private function maybe_start_admin() {
			if ( ! is_admin() ) {
				return;
			}

			require_once( $this->get_path( 'includes' ) . 'admin/class-charitable-dummy-admin.php' );
			require_once( $this->get_path( 'includes' ) . 'admin/charitable-dummy-admin-hooks.php' );
		}

		/**
		 * Register Stripe scripts.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_scripts() {}

		/**
		 * Set the error message for invalid amount when a donation form is submitted.
		 *
		 * This function can be removed once Charitable 1.4 is out and about.
		 *
		 * @param   string[] $vars Javascript vars.
		 * @return  string[] $vars
		 * @access  public
		 * @since   1.0.0
		 */
		public function set_js_error_messages( $vars ) {}

		/**
		 * Returns whether we are currently in the start phase of the plugin.
		 *
		 * @return  bool
		 * @access  public
		 * @since   1.0.0
		 */
		public function is_start() {
			return current_filter() === 'charitable_dummy_start';
		}

		/**
		 * Returns whether the plugin has already started.
		 *
		 * @return  bool
		 * @access  public
		 * @since   1.0.0
		 */
		public function started() {
			return did_action( 'charitable_dummy_start' ) || current_filter() === 'charitable_dummy_start';
		}

		/**
		 * Returns the plugin's version number.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_version() {
			return self::VERSION;
		}

		/**
		 * Returns plugin paths.
		 *
		 * @param   string $type If empty, returns the path to the plugin.
		 * @param   bool   $absolute_path If true, returns the file system path. If false, returns it as a URL.
		 * @return  string
		 * @since   1.0.0
		 */
		public function get_path( $type = '', $absolute_path = true ) {
			$base = $absolute_path ? $this->directory_path : $this->directory_url;

			switch ( $type ) {
				case 'includes' :
					$path = $base . 'includes/';
					break;

				case 'admin' :
					$path = $base . 'includes/admin/';
					break;

				case 'templates' :
					$path = $base . 'templates/';
					break;

				case 'assets' :
					$path = $base . 'assets/';
					break;

				case 'directory' :
					$path = $base;
					break;

				default :
					$path = $this->plugin_file;

			}//end switch

			return $path;
		}

		/**
		 * Throw error on object clone.
		 *
		 * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
		 *
		 * @since   1.0.0
		 * @access  public
		 * @return  void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'charitable-dummy' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @return  void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'charitable-dummy' ), '1.0.0' );
		}
	}

endif;
