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

	const TEXT_DOMAIN = 'wp-mkd-air-quality';

	/**
	 * The settings instance
	 * @since    1.0.0
	 * @var MKD_Air_Quality_Settings
	 */
	private $settings;


	/**
	 * MKD_Air_Quality_i18n constructor.
	 */
	public function __construct() {
		$this->settings = new MKD_Air_Quality_Settings();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			self::TEXT_DOMAIN,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Setup the plugin default language
	 *
	 * @param $locale
	 * @param $domain
	 *
	 * @return string
	 */
	public function setup_plugin_locale( $locale, $domain ) {
		if ( $domain === self::TEXT_DOMAIN ) {
			$language = $this->settings->get( 'language', 'default' );
			if ( $language !== 'default' ) {
				$locale = $language;
			}
			$locale = apply_filters( 'mkdaiq_locale', $locale );
		}

		return $locale;
	}
}
