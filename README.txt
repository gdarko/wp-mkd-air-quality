=== MKD Air Quality ===
Contributors: darkog
Donate link: https://darkog.com
Tags: airquality, mk, skopje, macedonia, bitola, tetovo, veles
Requires at least: 4.5.0
Tested up to: 5.3
Requires PHP: 5.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show Air Quality data for Macedonia on your website

== Description ==

### Example Usage

Just a few examples out of a lot combinations :)

#### Line Chart

Defaults: (`stations_selector=1`, `station=Centar`, `timemode=Week`, `date=today`, `unit=PM10`, `xlabels=0`)

PM10 data for today, show the time labels on the X axis. Default station is Centar.
`[mkdaiq_chart timemode='Day' xlabels=1]`

PM10 data for the last 7 days, hide the time labels on the X axis. Default station is Rektorat
`[mkdaiq_chart station='Rektorat' unit='PM10' timemode='Week']`

Show Carbon monoxide levels from Kicevo for the past 7 days. Hide the other stations dropdown
`[mkdaiq_chart station='Kicevo' stations_selector=0  unit='CO' timemode='Week']`

PM10 from Bitola1 on the 2020 New Year's day. show the time labels on the X axis.
`[mkdaiq_chart station='Bitola1' unit='PM10' timemode='Day' date='2020-01-01' xlabels=1]`

#### MAP

Defaults: (`date=today`, `unit=PM10`, `zoom=8`, `units_selector=1`)

Draw PM10 map for today, hide the units selector.
`[mkdaiq_map date='today' units_selector=0 unit='PM10']`

Draw PM10 map with zoom 8 for 2020 New Year's day and show units field.
`[mkdaiq_map date='2020-01-01' zoom=8]`


### TODO
* Most polluted places
* Air quality table

== Installation ==

1. Upload `wp-mkd-air-quality` to the `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to contribute?  =

To contribute please submit pull request or issue on the Github repository.

== Screenshots ==

1. Line Chart
2. Map

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.