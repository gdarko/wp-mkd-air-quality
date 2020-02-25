<?php

/**
 * Defines the tasks that needs to run with the cron system
 *
 * @link       https://darkog.com
 * @since      1.0.0
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 */

/**
 * Defines the tasks that needs to run with the cron system
 *
 * @since      1.0.0
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/includes
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_Cron {

	/**
	 * List of Cron hooks for this plugin
	 * @var array
	 */
	private $schedules = array();

	/**
	 * MKD_Air_Quality_Cron constructor.
	 */
	public function __construct() {
		$this->schedules = array(
			'mkdaiq_self_clean' => array(
				'interval' => 'daily',
				'callback' => 'self_clean'
			)
		);
	}


	/**
	 * Schedules the defined hooks
	 */
	public function schedule_hooks() {
		foreach ( $this->schedules as $hook => $schedule ) {
			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $schedule['interval'], $hook );
			}
		}
	}

	/**
	 * Attach the cron hooks
	 */
	public function attach_hooks() {
		foreach ( $this->schedules as $hook => $schedule ) {
			add_action( $hook, array( $this, $schedule['callback'] ) );
		}

	}

	/**
	 * Self clean the database cache daily.
	 *
	 * @hooks into mkdaiq_self_clean
	 */
	public function self_clean() {
		MKDAQAPI::purge_cache();
	}
}
