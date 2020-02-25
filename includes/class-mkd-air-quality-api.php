<?php

/**
 * Used as a helper class to get data from the Air api.
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKDAQAPI {

	/**
	 * The regions cache
	 * @var null
	 */
	static protected $regions = null;

	/**
	 * The stations cache
	 * @var null
	 */
	static protected $stations = null;


	/**
	 * The units cache
	 * @var null
	 */
	static protected $units = null;


	/**
	 * Possible Modes and Units
	 */
	const TIMEMODE_DAY = 'Day';
	const TIMEMODE_WEEK = 'Week';
	const TIMEMODE_MONTH = 'Month';
	const UNIT_CO = 'CO';
	const UNIT_NO2 = 'NO2';
	const UNIT_O3 = 'O3';
	const UNIT_PM10 = 'PM10';
	const UNIT_PM10D = 'PM10D';
	const UNIT_PM25 = 'PM25';
	const UNIT_SO2 = 'SO2';

	/**
	 * Cache timeout
	 * @var mixed|void
	 */
	private $timeout;

	/**
	 * Cache TTL
	 * @var int
	 */
	private $cache_ttl;

	/**
	 * Mkd_Air_Quality_Api constructor.
	 */
	public function __construct() {
		$this->timeout   = apply_filters( 'mkdaiq_timeout', 120 );
		$this->cache_ttl = apply_filters( 'mkdaiq_cache_ttl', 60 * 30 ); // 30 minutes by default.
	}

	/*
	 * @var string
	 */
	private $query_str = 'http://airquality.moepp.gov.mk/graphs/site/pages/MakeGraph.php?graph=StationLineGraph&station=%s&parameter=%s&endDate=%s&timeMode=%s&background=false&lang=mk';


	/**
	 * Returns the endpoint
	 *
	 * @param $station
	 * @param $end_date
	 * @param  string  $parameter
	 * @param  string  $time_mode
	 *
	 * @return string
	 */
	public function get_endpoint( $station, $end_date, $parameter = 'PM10', $time_mode = 'Week' ) {
		return sprintf( $this->query_str, $station, $parameter, $end_date, $time_mode );
	}


	/**
	 * Return data about quality
	 *
	 * Example parameters:
	 * 1.) Kicevo
	 * 2.) 2020-02-19
	 * 3.) PM10
	 * 4.) Week
	 *
	 * @param  string  $station  (can be one station or comma separated stations)
	 * @param  string  $end_date
	 * @param  string  $parameter
	 * @param  string  $time_mode
	 *
	 * @param  bool  $flush_cache
	 *
	 * @return array|WP_Error
	 * @throws Exception
	 */
	public function query( $station, $end_date, $parameter = 'PM10', $time_mode = 'Week', $flush_cache = false ) {

		$cache_key = $this->get_cache_key( $station, $end_date, $parameter, $time_mode );
		if ( $flush_cache ) {
			delete_transient( $cache_key );
		}
		$data = get_transient( $cache_key );
		if ( false === $data ) {
			$endpoint = $this->get_endpoint( $station, $end_date, $parameter, $time_mode );
			//error_log($endpoint);
			$data     = wp_remote_get( $endpoint, array( 'timeout' => 500 ) );
			if ( is_wp_error( $data ) ) {
				throw new \Exception( $data->get_error_message() );
			} else {
				$data = $data['body'];
				$data = @json_decode( $data, true );
				if ( false === $data ) {
					throw new \Exception( 'Could not decode JSON data.' );
				} else {
					set_transient( $cache_key, $data, $this->cache_ttl );
				}

			}
		}

		return $data;
	}

	public static function purge_cache() {
		global $wpdb;
		foreach ( array( '_transient_timeout_mkdaiq_%', '_transient_mkdaiq_%' ) as $match ) {
			$prepared = $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $match );
			$wpdb->query( $prepared );
		}
	}


	/**
	 * Returns the cache key for the parameters
	 *
	 * @param $station
	 * @param $end_date
	 * @param $parameter
	 * @param $time_mode
	 *
	 * @return string
	 */
	private function get_cache_key( $station, $end_date, $parameter, $time_mode ) {
		return 'mkdaiq_' . md5( "{$station}{$end_date}{$parameter}{$time_mode}{$this->cache_ttl}" );
	}

	/**
	 * Returns the list of the regions
	 * @return mixed|null
	 */
	public static function get_regions() {
		if ( is_null( self::$regions ) || ! is_array( self::$regions ) ) {
			self::$regions = include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'regions.php';
		}

		return self::$regions;
	}


	/**
	 * Returns the list of stations
	 *
	 * @param  string  $allowed
	 *
	 * @return mixed|null
	 */
	public static function get_stations( $allowed = 'all' ) {
		if ( is_null( self::$stations ) || ! is_array( self::$stations ) ) {
			self::$stations = include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'stations.php';
		}

		if ( $allowed === 'all' ) {
			return self::$stations;
		} else {
			$stations         = array();
			$allowed_stations = explode( ',', $allowed );
			foreach ( $allowed_stations as $allowed_station ) {
				if ( isset( self::$stations[ $allowed_station ] ) ) {
					$stations[ $allowed_station ] = self::$stations[ $allowed_station ];
				}
			}

			return $stations;
		}
	}

	/**
	 * Returns the list of the units
	 * @return mixed|null
	 */
	public static function get_units() {
		if ( is_null( self::$units ) || ! is_array( self::$units ) ) {
			self::$units = include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'units.php';
		}

		return self::$units;
	}

}