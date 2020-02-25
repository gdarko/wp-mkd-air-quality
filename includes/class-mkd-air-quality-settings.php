<?php

/**
 * Settings manipulation
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_Settings {

	private $data = null;

	/**
	 * @param $key
	 * @param  null  $default
	 *
	 * @return mixed|null
	 */
	public function get( $key, $default = null ) {

		if ( ! is_array( $this->data ) ) {
			$this->load();
		}

		if ( ! is_array( $this->data ) ) {
			$this->data = $this->defaults();
		}

		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : $default;
	}

	/**
	 * Load the options
	 */
	private function load() {
		$this->data = get_option( 'mkdaiq_settings' );
	}

	/**
	 * Save the options
	 */
	public function save() {
		update_option( 'mkdaiq_settings', $this->data, true );
	}


	/**
	 * Set single option
	 *
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		if ( ! is_array( $this->data ) ) {
			$this->load();
		}
		if ( ! is_array( $this->data ) ) {
			$this->data = $this->defaults();
		}
		$this->data[ $key ] = $value;
	}


	/**
	 * Default options
	 * @return array
	 */
	private function defaults() {
		return array(
			'language' => 'mk_MK',
		);
	}

	/**
	 * Has settings?
	 * @return bool
	 */
	public function any() {
		$this->load();
		if ( ! is_array( $this->data ) || empty( $this->data ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Setup the defaults
	 */
	public function setup_defaults() {
		$this->data = array();
		foreach ( $this->defaults() as $key => $value ) {
			$this->set( $key, $value );
		}
		$this->save();
	}

}
