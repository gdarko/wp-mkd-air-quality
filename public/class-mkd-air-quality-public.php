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
	 * The data helper
	 * @var MKD_Air_Quality_Helper
	 */
	private $data_helper;

	private $is_debug;

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
		$this->data_helper = new MKD_Air_Quality_Helper();
		$this->is_debug = defined( 'WP_DEBUG' ) && WP_DEBUG;

	}


	/**
	 * Register all the shortcodes
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'mkdaiq_chart', array( $this, 'shortcode_linechart' ) );
		add_shortcode( 'mkdaiq_map', array( $this, 'shortcode_map' ) );
		add_shortcode( 'mkdaiq_rank', array( $this, 'shortcode_rank' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( 'leaflet', plugin_dir_url( __FILE__ ) . 'resources/leaflet/leaflet.css', array(), $this->version, 'all' );

		$stylesheet = $this->is_debug ? 'css/style.css' : 'css/style.min.css';
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) .$stylesheet, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( 'leaflet', plugin_dir_url( __FILE__ ) . 'resources/leaflet/leaflet.js', array( 'jquery' ), null, true );
		wp_register_script( 'chartjs', plugin_dir_url( __FILE__ ) . 'resources/chartjs/Chart.min.js', array( 'jquery' ), null, true );

		$js = $this->is_debug ? 'js/script.js' : 'js/script.min.js';
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . $js, array( 'jquery' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'MKDAIQ', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'mkdaiq_nonce' ),
			'strings'  => array(
				'micrograms'             => 'µg/m3',
				'not_measured'           => __( 'Not measured', 'wp-mkd-air-quality' ),
				'measurements_not_found' => __( 'No measurements found.', 'wp-mkd-air-quality' ),
				'unable_to_initialize'   => __( 'Unable to initialize the Air Quality element.', 'wp-mkd-air-quality' ),
			),
			'config'   => array(
				'colors' => apply_filters( 'mkdaiq_chart_colors', array(
					'#21e3f6',
					'#f68f7a',
					'#e6cae2',
					'#f6c63e',
					'#81f689',
					'#f66b9e',
					'#5442f6',
					'#b7000c'
				) )
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
			'station'           => 'Centar',
			'timemode'          => 'Week', // Week or Day
			'date'              => 'today', // defaults to today's date.
			'unit'              => 'PM10',
			'xlabels'           => 0,
			'stations_selector' => 1,
		);
		$args     = shortcode_atts( $defaults, $args );

		// Filter stations
		$stations = MKDAQAPI::get_stations();

		// Regions
		$regions = MKDAQAPI::get_regions();

		// validate default time mode
		if ( ! in_array( $args['timemode'],
			array( MKDAQAPI::TIMEMODE_DAY, MKDAQAPI::TIMEMODE_WEEK, MKDAQAPI::TIMEMODE_MONTH ) ) ) {
			$args['timemode'] = MKDAQAPI::TIMEMODE_WEEK;
		}
		// validate default station
		if ( count( $stations ) > 0 && ! isset( $stations[ $args['station'] ] ) ) {
			$args['station'] = $stations[0];
		}
		// Validate end date
		if ( empty( $args['date'] ) || $args['date'] === 'today' ) {
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

		wp_enqueue_style( 'leaflet' );
		wp_enqueue_script( 'leaflet' );
		wp_enqueue_script( 'chartjs' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		$defaults = array(
			'date'           => 'today',
			'unit'           => 'PM10',
			'units_selector' => 1,
			'zoom'           => 8,
		);

		$args          = shortcode_atts( $defaults, $args );
		$args['units'] = MKDAQAPI::get_units();

		if ( $args['date'] === 'today' ) {
			$args['date'] = date( 'Y-m-d' );
		}

		$path = plugin_dir_path( __FILE__ ) . 'partials/map.php';
		ob_start();
		if ( ! empty( $args ) ) {
			extract( $args );
		}
		include $path;

		return ob_get_clean();

	}

	/**
	 * Pollution rank
	 *
	 * @param $args
	 *
	 * @return false|string
	 */
	public function shortcode_rank( $args ) {

		$defaults = array(
			'date'     => 'today',
			'timemode' => 'Day',
			'unit'     => 'PM10',
			'type'     => 'last', // last or average
			'order'    => 1
		);

		$args       = shortcode_atts( $defaults, $args );
		$stations   = MKDAQAPI::get_stations();
		$stations_q = implode( ',', array_keys( $stations ) );
		if ( $args['date'] === 'today' || empty( $args['date'] ) ) {
			$args['date'] = date( 'Y-m-d' );
		}

		$unit     = $args['unit'];
		$date     = $args['date'];
		$timemode = $args['timemode'];
		$type     = $args['type'];


		try {
			$data = $this->api->query( $stations_q, $args['date'], $args['unit'], $args['timemode'] );
			if ( ! isset( $data['measurements'] ) || empty( $data['measurements'] ) ) {
				$data = array();
			}
		} catch ( Exception $e ) {
			$data = array();
		}

		$stationValues = array();
		$stationIndicators = array();
		if(!empty($data)) {
			$stationData = array();
			foreach ( $data['measurements'] as $time => $s ) {
				foreach ( $s as $key => $value ) {
					if($value === '') {
						continue;
					}
					if ( ! isset( $stationData[ $key ] ) ) {
						$stationData[ $key ] = array();
					}
					array_push( $stationData[ $key ], floatval( $value ) );
				}
			}
			foreach($stationData as $key => $values) {
				if($args['type'] === 'average') {
					$stationTotal = array_sum( $values );
					$stationCount = count( $values );
					if ( $stationTotal === 0 || $stationCount === 0 ) {
						continue;
					}
					$stationValue = (float) $stationTotal / $stationCount;
				} else {
					$stationValue = null;
					for ( $i = count( $values ) - 1; $i >= 0; $i -- ) {
						if ( is_numeric( $values[ $i ] ) && $values[ $i ] > 0 ) {
							$stationValue = $values[ $i ];
							break;
						}
					}
					if ( is_null( $stationValue ) ) {
						continue;
					}
				}
				$stationValues[ $key ]     = $stationValue;
				$stationIndicators[ $key ] = $this->data_helper->get_quality_range( $stationValue, $unit );
			}
			if ( $args['order'] ) {
				asort( $stationValues, SORT_NUMERIC );
				$stationValues = array_reverse( $stationValues, true );
			}

		}

		// Labels
		$format= get_option('date_format');
		$dt = DateTime::createFromFormat('Y-m-d', $date);
		$date_str = wp_date($format, $dt->getTimestamp());
		$footer = array( $date_str );
		if ( $type === 'last' ) {
			array_push( $footer, __( 'Last Value', 'wp-mkd-air-quality' ) );
		} else {
			array_push( $footer, __( 'Average Value', 'wp-mkd-air-quality' ) );
		}
		if ( $timemode === 'Day' ) {
			array_push( $footer, __( 'Daily', 'wp-mkd-air-quality' ) );
		} elseif ( $timemode === 'Week' ) {
			array_push( $footer, __( 'Weekly', 'wp-mkd-air-quality' ) );
		} elseif ( $timemode === 'Month' ) {
			array_push( $footer, __( 'Monthly', 'wp-mkd-air-quality' ) );
		}

		$path = plugin_dir_path( __FILE__ ) . 'partials/rank.php';
		ob_start();
		include $path;

		return ob_get_clean();
	}

}
