<?php

/**
 * Used as a helper class to do various conversions and calculations on serverside.
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_Helper {

	/**
	 * Returns the quality range based on air quality value and unit.
	 *
	 * @param $value
	 * @param $unit
	 *
	 * @return string
	 */
	public function get_quality_range( $value, $unit ) {
		$value = floatval( $value );
		$units = MKDAQAPI::get_units();
		if ( ! isset( $units[ $unit ] ) ) {
			return 'undefined';
		}
		$unit = $units[ $unit ];
		foreach ( $unit['index'] as $range ) {
			$from = $range['from'];
			$to   = $range['to'];
			if ( $value >= $from && $value <= $to ) {
				return $range['slug'];
			}
		}
		return 'undefined';
	}
}