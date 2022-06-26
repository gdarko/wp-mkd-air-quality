=== MKD Air Quality ===
Contributors: darkog
Tags: airquality, skopje, macedonia
Requires at least: 4.5.0
Tested up to: 6.0
Requires PHP: 5.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show air quality data for multiple air quality stations in Macedonia

== Description ==

The Air Quality data is provided by the Ministry of environment and physical planning of R. Macedonia and will only work for the country.

The data is measured by physical stations across the country and is provided via RESTful service. More information can be found on the AIR Quality portal:

* <a href="http://air.moepp.gov.mk/?page_id=1351&lang=en">Governemnt Air Quality Portal</a>

The data is cached for 30 minutes by the plugin and it's refreshed hourly.

Friendly Note: The plugin is country specific and will not work with any other countries.

## Examples

Just a few examples out of a lot combinations :)

### Line Chart

Defaults: (`stations_selector=1`, `station=Centar`, `timemode=Week`, `date=today`, `unit=PM10`, `xlabels=0`)

* PM10 data for today, show the time labels on the X axis. Default station is Centar.
`[mkdaiq_chart timemode='Day' xlabels=1]`
* PM10 data for the last 7 days, hide the time labels on the X axis. Default station is Rektorat
`[mkdaiq_chart station='Rektorat' unit='PM10' timemode='Week']`
* Show Carbon monoxide levels from Kicevo for the past 7 days. Hide the other stations dropdown
`[mkdaiq_chart station='Kicevo' stations_selector=0  unit='CO' timemode='Week']`
* PM10 from Bitola1 on the 2020 New Year's day. show the time labels on the X axis.
`[mkdaiq_chart station='Bitola1' unit='PM10' timemode='Day' date='2020-01-01' xlabels=1]`

### MAP

Defaults: (`date=today`, `unit=PM10`, `zoom=8`, `units_selector=1`)

* Draw PM10 map for today, hide the units selector.
`[mkdaiq_map date='today' units_selector=0 unit='PM10']`
* Draw PM10 map with zoom 8 for 2020 New Year's day and show units field.
`[mkdaiq_map date='2020-01-01' zoom=8]`

### RANK

Defaults: (`date=today`, `unit=PM10`, `type=last`, `order=1`)

* Draw PM10 rank table for today based on the last known result ordered by most polluted
`[mkdaiq_rank date='today' timemode='Day' type='last' order='1']`
* Draw PM10 rank table for the last 7 days based on the average result ordered by most polluted
`[mkdaiq_rank date='today' timemode='Week' type='average' order='1']`

## Credits
Icons by flaticon

== Installation ==

1. Upload `wp-mkd-air-quality` to the `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to contribute?  =

To contribute please submit pull request or issue on the <a href="https://github.com/gdarko/wp-mkd-air-quality">Github repository</a>.

== Screenshots ==

1. Line Chart (Multiple)
2. Line Chart (Single)
3. Map (OSM)
4. Rank Table
5. Settings

== Changelog ==

= 1.1.2 =
* Updated readme
* Fix compatibility with PHP8
* Fix Javascript errors in admin UI

= 1.0.1 =
* Fix loading chart when the map shortcode is above the chart.

= 1.0.0 =
* Initial Version