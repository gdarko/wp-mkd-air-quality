<?php

/**
 * The file that defines air qualiy units
 *
 *
 * @link http://air.moepp.gov.mk/?page_id=113&lang=en
 * @copyright  Darko Gjorgjijoski <dg@darkog.com>
 * @since      1.0.0
 * @license    GPLv2
 */

return array(
	'CO'    => array(
		'name_mk'              => 'Јаглерод Моноксид (CO)',
		'name'                 => __('Carbon Monoxide (CO)', 'wp-mkd-air-quality'),
		'unit'                 => 'mg/m&#179;',
		'shortName'            => 'CO',
		'shortNameNoSubscript' => 'CO',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 5,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 5,
				'to'   => 7.5,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 7.5,
				'to'   => 10,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 10,
				'to'   => 20,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 20,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'NO2'   => array(
		'name_mk'              => 'Азот Диоксид (NO2)',
		'name'                 => __('Nitrogen Dioxide (NO2)', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'NO&#8322;',
		'shortNameNoSubscript' => 'NO2',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 50,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 50,
				'to'   => 100,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 100,
				'to'   => 200,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 200,
				'to'   => 400,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 400,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'O3'    => array(
		'name_mk'              => 'Озон (O3)',
		'name'                 => __('Ozone (O3)', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'O&#8323;',
		'shortNameNoSubscript' => 'O3',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 60,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 60,
				'to'   => 120,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 120,
				'to'   => 180,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 180,
				'to'   => 240,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 240,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'PM10'  => array(
		'name_mk'              => 'ПМ честички (PM10)',
		'name'                 => __('Particulate Matter (PM10)', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'PM&#8321;&#8320;',
		'shortNameNoSubscript' => 'PM10',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 25,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 25,
				'to'   => 50,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 50,
				'to'   => 90,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 90,
				'to'   => 180,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 180,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'PM10D' => array(
		'name_mk'              => 'ПМ честички (PM10) Дневно',
		'name'                 => __('Particulate matter (PM10) Daily', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'PM&#8321;&#8320;',
		'shortNameNoSubscript' => 'PM10D',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 25,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 25,
				'to'   => 50,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 50,
				'to'   => 90,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 90,
				'to'   => 180,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 180,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'PM25'  => array(
		'name_mk'              => 'ПМ честички (PM2.5)',
		'name'                 => __('Particulate Matter (PM2.5)', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'PM&#8322;.&#8325;',
		'shortNameNoSubscript' => 'PM2.5',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 15,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 15,
				'to'   => 30,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 30,
				'to'   => 55,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 55,
				'to'   => 110,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 110,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
	'SO2'   => array(
		'name_mk'              => 'Сулфур Диоксид (SO2)',
		'name'                 => __('Sulfur Dioxide (SO2)', 'wp-mkd-air-quality'),
		'unit'                 => 'µg/m&#179;',
		'shortName'            => 'SO&#8322;',
		'shortNameNoSubscript' => 'SO2',
		'index'                => array(
			array(
				'name' => __( 'Very low', 'wp-mkd-air-quality' ),
				'from' => 0,
				'to'   => 50,
				'slug' => 'verylow',
			),
			array(
				'name' => __( 'Low', 'wp-mkd-air-quality' ),
				'from' => 50,
				'to'   => 100,
				'slug' => 'low',
			),
			array(
				'name' => __( 'Medium', 'wp-mkd-air-quality' ),
				'from' => 100,
				'to'   => 350,
				'slug' => 'medium',
			),
			array(
				'name' => __( 'High', 'wp-mkd-air-quality' ),
				'from' => 350,
				'to'   => 500,
				'slug' => 'high',
			),
			array(
				'name' => __( 'Very High', 'wp-mkd-air-quality' ),
				'from' => 500,
				'to' => PHP_INT_MAX,
				'slug' => 'veryhigh',
			)
		),
	),
);