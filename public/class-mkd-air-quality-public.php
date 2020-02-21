<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://darkog.com
 * @since      1.0.0
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin front-end views and enqueues the public-facing stylesheet and JavaScript.
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/public
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The api resource
	 * @var MKDAQAPI
	 */
	private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of the plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->api         = new MKDAQAPI();

	}


	/**
	 * Register all the shortcodes
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'mkdaiq_chart', array( $this, 'shortcode_linechart' ) );
		add_shortcode( 'mkdaiq_map', array( $this, 'shortcode_map' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( 'leaflet', plugin_dir_url( __FILE__ ) . 'resources/leaflet/leaflet.css', array(),
			$this->version, 'all' );

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version,
			'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( 'leaflet', plugin_dir_url( __FILE__ ) . 'resources/leaflet/leaflet.js', array( 'jquery' ),
			time(), true );

		wp_register_script( 'chartjs', plugin_dir_url( __FILE__ ) . 'resources/chartjs/Chart.min.js', array( 'jquery' ),
			time(), true );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ), time(),
			true );

		wp_localize_script( $this->plugin_name, 'MKDAIQ', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'mkdaiq_nonce' ),
			'strings'  => array(
				'not_measured' => __('Not measured', 'mkd-air-quality'),
				'measurements_not_found' => __('No measurements found.', 'mkd-air-quality'),
				'unable_to_initialize' => __( 'Unable to initialize the Air Quality element.', 'mkd-air-quality' ),
			)
		) );

	}

	/**
	 * The basic display shortcode
	 *
	 * eg. [mkdaiq_chart allowed_stations='all' default_station='Centar']
	 *
	 *
	 * @param $args
	 *
	 * @return false|string
	 * @since    1.0.0
	 */
	public function shortcode_linechart( $args ) {

		wp_enqueue_style( 'leaflet' );
		wp_enqueue_script( 'leaflet' );

		wp_enqueue_script( 'chartjs' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		$defaults = array(
			'allowed_stations' => 'all',
			'default_station'  => 'Centar',
			'default_timemode' => 'Week', // Week or Day
			'date'             => '', // defaults to today's date.
			'unit'             => 'PM10',
			'date_labels'           => 0,
		);
		$args     = shortcode_atts( $defaults, $args );

		// Filter stations
		$stations = MKDAQAPI::get_stations( $args['allowed_stations'] );

		// validate default time mode
		if ( ! in_array( $args['default_timemode'],
			array( MKDAQAPI::TIMEMODE_DAY, MKDAQAPI::TIMEMODE_WEEK, MKDAQAPI::TIMEMODE_MONTH ) ) ) {
			$args['default_timemode'] = MKDAQAPI::TIMEMODE_WEEK;
		}
		// validate default station
		if ( count( $stations ) > 0 && ! isset( $stations[ $args['default_station'] ] ) ) {
			$args['default_station'] = $stations[0];
		}
		// Validate end date
		if ( empty( $args['date'] ) ) {
			$args['date'] = date( 'Y-m-d' );
		}

		$path = plugin_dir_path( __FILE__ ) . 'partials/linechart.php';
		ob_start();
		if ( ! empty( $args ) ) {
			extract( $args );
		}
		include $path;

		return ob_get_clean();
	}


	/**
	 * Outputs air quality map
	 *
	 * eg. [mkdaiq_map units="PM10,CO"]
	 *
	 * @param $args
	 *
	 * @return false|string
	 */
	public function shortcode_map( $args ) {

		$defaults = array(
			'date' => 'today',
			'unit' => 'PM10',
		);

		$args          = shortcode_atts( $defaults, $args );
		$args['units'] = MKDAQAPI::get_units();

		if ( $args['date'] === 'today' ) {
			$args['date'] = date( 'Y-m-d' );
		}

		wp_enqueue_style( 'leaflet' );
		wp_enqueue_script( 'leaflet' );

		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );


		$path = plugin_dir_path( __FILE__ ) . 'partials/map.php';
		ob_start();
		if ( ! empty( $args ) ) {
			extract( $args );
		}
		include $path;

		return ob_get_clean();

	}

}
