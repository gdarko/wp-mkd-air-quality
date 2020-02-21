<?php

/**
 * Handles all the AJAX related actions
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_AJAX {

	const NONCE_DEFAULT = 'mkdaiq_nonce';

	/**
	 * The API instance
	 * @var MKDAQAPI
	 */
	private $api;

	/**
	 * MKD_Air_Quality_AJAX constructor.
	 */
	public function __construct() {
		$this->api = new MKDAQAPI();
	}

	/**
	 * Ajax query handler.
	 */
	public function query() {

		if ( ! $this->check_referrer( self::NONCE_DEFAULT, 'nonce' ) ) {
			wp_send_json_error();
		}

		$station  = isset( $_GET['station'] ) ? sanitize_text_field( $_GET['station'] ) : '';
		$date     = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
		$unit     = isset( $_GET['unit'] ) ? sanitize_text_field( $_GET['unit'] ) : '';
		$timemode = isset( $_GET['timemode'] ) ? sanitize_text_field( $_GET['timemode'] ) : '';
		$flush    = false;

		try {
			$data = $this->api->query( $station, $date, $unit, $timemode, $flush );
			wp_send_json_success( $data );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

		die;

	}


	public function query_map_data() {

		if ( ! $this->check_referrer( self::NONCE_DEFAULT, 'nonce' ) ) {
			wp_send_json_error();
		}

		$timemode = isset( $_GET['timemode'] ) && ! empty( $_GET['timemode'] ) ? sanitize_text_field( $_GET['timemode'] ) : 'Day';
		$date     = isset( $_GET['end_date'] ) && ! empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : date( 'Y-m-d' );
		$unit     = isset( $_GET['unit'] ) && ! empty( $_GET['unit'] ) ? sanitize_text_field( $_GET['unit'] ) : 'PM10';

		$flush = false;

		$stations = MKDAQAPI::get_stations();
		$station  = implode( ',', array_keys( $stations ) );

		try {
			$data = $this->api->query( $station, $date, $unit, $timemode, $flush );
			$data['stations'] = MKDAQAPI::get_stations();
			$data['units'] = MKDAQAPI::get_units();
			wp_send_json_success( $data );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

		die;

	}


	/**
	 * Performs referrer check
	 *
	 * @param $nonce_name
	 * @param $query_parameter_key
	 *
	 * @return bool|int
	 */
	private function check_referrer( $nonce_name, $query_parameter_key ) {
		return check_ajax_referer( $nonce_name, $query_parameter_key, false );
	}

}