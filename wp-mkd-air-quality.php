<?php

/**
 * Plugin Name:       MKD Air Quality
 * Plugin URI:        https://darkog.com/blog/air-quality-mk/
 * Description:       Show air quality data for multiple air quality stations in Macedonia
 * Version:           1.1.2
 * Author:            Darko Gjorgjijoski
 * Author URI:        https://darkog.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mkd-air-quality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined('WPINC')) {
    die;
}

define('MKD_AIR_QUALITY_VERSION', '1.1.2');

function activate_mkd_air_quality()
{
    require_once plugin_dir_path(__FILE__).'includes/class-mkd-air-quality-activator.php';
    MKD_Air_Quality_Activator::activate();
}

function deactivate_mkd_air_quality()
{
    require_once plugin_dir_path(__FILE__).'includes/class-mkd-air-quality-deactivator.php';
    MKD_Air_Quality_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_mkd_air_quality');
register_deactivation_hook(__FILE__, 'deactivate_mkd_air_quality');

require plugin_dir_path(__FILE__).'includes/class-mkd-air-quality.php';

$plugin = new MKD_Air_Quality();
$plugin->run();
