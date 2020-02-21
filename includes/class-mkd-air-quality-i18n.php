<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://darkog.com
 * @since      1.0.0
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mkd-air-quality',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
