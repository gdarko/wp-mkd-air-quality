(function(){
    /**
     * Converts date
     * From eg: 20200213 00
     * @param date
     * @return Date
     */
    window.MKDAIQ_convertDate = function (date) {
        var parts = date.split(" ");
        var year = parts[0].substring(0, 4);
        var month = parts[0].substring(4, 6);
        var day = parts[0].substring(6, 8);
        var hour = parts[1] + ':' + '00';
        var str = year + '-' + month + '-' + day + ' ' + hour;
        return new Date(str);
    };

    /**
     * Collect the station data
     * @param station
     * @param measurements
     * @returns {[]}
     * @constructor
     */
    window.MKDAIQ_StationData = function(station, measurements) {

        var values = [];
        var j = 0;
        for(var i in measurements) {
            if(!measurements.hasOwnProperty(i)) {
                continue;
            }
            values.splice(j++, 0, {value: measurements[i][station], 'date': i});
        }
        return values;
    };

    /**
     * Station average (if null - not measured)
     * @param stationData
     * @returns {null|number}
     * @constructor
     */
    window.MKDAIQ_LastResult = function (stationData) {

        var lastResult = null;
        for(var i = stationData.length - 1; i >= 0; i--) {
            var value = stationData[i].value;
            if(value === '' || isNaN(value)) {
                continue;
            }else {
                lastResult = value;
                break;
            }
        }
        return lastResult;
    };

    /**
     *
     * @param value
     * @param unit
     * @returns {null}
     * @constructor
     */
    window.MKDAIQ_Quality_Range = function(value, unit) {

        var color = null;
        var ranges = unit.index;
        var from = null;
        var to = null;
        value = parseFloat(value);
        for(var i in ranges) {
            if(!ranges.hasOwnProperty(i)) {
                continue;
            }

            from = parseFloat(ranges[i]['from']);
            to = parseFloat(ranges[i]['to']);

            if(value >= from && value <= to){
                return ranges[i];
            }
        }
        return null;

    };

    /**
     * Generates map pin popup
     * @return {string}
     */
    window.MKDAIQ_Popup = function(station, stationData, lastResult, unit, date) {

        var html = '<div class="mkdaiq-map-pupup">';
        var value;
        var style;
        if(stationData.length > 0) {
            var stationName = station + ' ('+ unit + ')';
            html += '<div class="mkdaiq-map-head"><h3>'+stationName+'</h3><h4>'+date+'</h4></div>';
            html += '<div class="mkdaiq-map-body">';
            html += '<table class="mkdaiq-map-data">';
            stationData = stationData.reverse();
            for(var i in stationData) {

                var _date = window.MKDAIQ_convertDate(stationData[i].date);
                value = stationData[i].value;
                if(value === '') {
                    value = MKDAIQ.strings.not_measured;
                } else {
                    value += ' ' + MKDAIQ.strings.micrograms;
                }
                style = i > 5 ? 'display:none;' : '';
                html += '<tr class="mkdaiq-map-data-entry" style="' + style + '">';
                html += '<td class="mkdaiq-map-data-time">';
                html += _date.toLocaleTimeString();
                html += '</td>';
                html += '<td class="mkdaiq-map-data-value">';
                html += value;
                html += '</td>';
                html += '</tr>';
            }
            html += '</table>';
            html += '<p class="mkdaiq-map-expand-results"><a href="#">Show full results</a>';
            html += '</div>';
        } else {
            html += '<p>'+MKDAIQ.strings.measurements_not_found+'</p>';
        }
        html += '</div>';
        return html;

    }
})(jQuery);

/**
 * Line chart
 */
(function ($) {

    $.fn.mkdaiqLC = function () {

    	if(typeof Chart === 'undefined') {
    		return;
		}

        var self = this;

        // Vars
        var main_element = self.find('.mkdaiq-chart');
        var station_filter = self.find('.mkdaiq-select-station');
        var default_station = self.data('default-station');
        var default_unit = self.data('default-unit');
        var timemode = self.data('default-timemode');
        var date = self.data('date');
        var x_labels = parseInt(self.data('date-labels'));
        var stations_selector = self.data('stations-selector');

        var chartInstance = null;

        // Methods
        self.initializeChart = function (station, timemode, unit, date) {

            var ctx = main_element.get(0);

            $.ajax({
                url: MKDAIQ.ajax_url + '?action=mkdaiq_query&nonce=' + MKDAIQ.nonce,
                cache: false,
                type: 'GET',
                data: {station: station, timemode: timemode, unit: unit, end_date: date},
                beforeStart: function () {
                    main_element.html('Loading...');
                },
                success: function (response) {

                    var colors = MKDAIQ.config.colors;

                    if (response.success) {
                        var measurements = response.data.measurements;
                        var datasets = [];
                        var stations = station.split(',');
                        var values, labels, s;
                        var e = 0;
                        for(var i in stations) {

                            if (!stations.hasOwnProperty(i)) {
                                continue;
                            }

                            values = [];
                            labels = [];
                            s = stations[i];

                            for (var j in measurements) {
                                if (!measurements.hasOwnProperty(j)) {
                                    continue;
                                }
                                var value = measurements[j][s];
                                var date = window.MKDAIQ_convertDate(j);
                                labels.push(date.toLocaleString());
                                values.push({
                                    t: date,
                                    y: parseFloat(value),
                                });
                            }

                            datasets.push({
                                label: s,
                                data: values,
                                fill: false,
                                borderColor: colors[e],
                                lineTension: 0.1
                            });

                            e++;
                        }

                        var options = {
                            responsive: true,
                            hoverMode: 'index',
                            stacked: false,
							scales: {
								xAxes: [{
									afterTickToLabelConversion: function(data){
										var ticks = data.ticks;
										ticks.forEach(function (lbls, i) {
											if (i % 2 === 1){
												ticks[i] = '';
											}
										});
									},
                                    ticks: {
                                        maxRotation: 90,
                                        minRotation: 90,
                                        display: x_labels // (true/false)
                                    }
								}]
							},
                            tooltips: {
                                callbacks: {
                                    label: (item) => `${item.yLabel} ${MKDAIQ.strings.micrograms} (${unit})`,
                                },
                            },
                            legend: {
                                display: true
                            }
                        };


                        var data = {
                            labels: labels,
                            datasets: datasets
                        };

                        if (!chartInstance) {
                            chartInstance = new Chart(ctx, {
                                type: 'line',
                                data: data,
                                options: options
                            });
                        } else {
                            chartInstance.data = data;
							chartInstance.options = options;
                            chartInstance.update();
                        }


                    } else {
                        console.error(response.data);
                    }
                },
                error: function (e) {
                    console.error('MKDAIQ: Unable to initialize.');
                }
            });

        };

        // Handle Initialization
        self.initializeChart(default_station, timemode, default_unit, date);

        // Handme Events
        if (station_filter.length > 0) {
            station_filter.on('change', function (e) {
                var new_station = $(this).val();
                self.initializeChart(new_station, timemode, default_unit, date);
            });
        }

    };

    $('.mkdaiq-linechart-element').each(function () {
        $(this).mkdaiqLC();
    })


})(jQuery);

/**
 * MAP
 */
(function($){


    $(document).on('click', '.mkdaiq-map-expand-results', function(e){
        e.preventDefault();
        var $wrap = $(this).closest('.mkdaiq-map-pupup');
        var $rows = $wrap.find('.mkdaiq-map-data-entry');
        $rows.show();
        $(this).hide();
    });


    $.fn.mkdaiqMAP = function () {

        if(typeof L === 'undefined') {
            return;
        }

        var self = this;

        // Vars
        var main_element = self.find('.mkdaiq-map');
        var date_filter = self.find('.mkdaiq-select-date');
        var unit_filter = self.find('.mkdaiq-select-unit');
        var unit = self.data('unit');
        var date = self.data('date');
        var zoom = self.data('zoom');

        /**
         * Leaflet instance
         * @type L.Map
         */
        var mapInstance = null;

        // Methods
        self.initializeMap = function (station, timemode, unit, date) {

            var center = [41.5559, 21.7365];
            if(!zoom) {
                zoom = 8;
            }

            var map_id = main_element.attr('id');
            $.ajax({
                url: MKDAIQ.ajax_url + '?action=mkdaiq_query_map_data&nonce=' + MKDAIQ.nonce,
                cache: false,
                type: 'GET',
                data: {station: station, timemode: timemode, unit: unit, end_date: date},
                beforeStart: function () {
                    main_element.html('Loading...');
                },
                success: function (response) {

                    if(mapInstance !== null) {
                        center = mapInstance.getCenter();
                        zoom = mapInstance.getZoom();
                        mapInstance.off();
                        mapInstance.remove();
                    }

                    if (response.success) {

                        mapInstance = L.map(map_id).setView(center, zoom);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: ''
                        }).addTo(mapInstance);

                        var unitInfo = response.data.units[unit];

                        var lat,
                            lng,
                            name,
                            stationData,
                            lastResult,
                            qualityRange,
                            qualitySlug,
                            customIcon,
                            marker,
                            popup = null;

                        for (var i in response.data.stations) {

                            if (!response.data.stations.hasOwnProperty(i)) {
                                continue;
                            }
                            lat = response.data.stations[i]['lat'];
                            lng = response.data.stations[i]['lng'];
                            name = response.data.stations[i]['name'];
                            stationData = window.MKDAIQ_StationData(i, response.data.measurements);
                            lastResult = window.MKDAIQ_LastResult(stationData);
                            qualityRange = window.MKDAIQ_Quality_Range(lastResult, unitInfo);
                            qualitySlug = qualityRange !== null && qualityRange.hasOwnProperty('slug') ? qualityRange['slug'] : 'undefined';
                            customIcon = L.divIcon({
                                className: 'mkdaiq-pin mkdaiq-color-' + qualitySlug,
                                iconSize: new L.Point(30, 30)
                            });
                            marker = L.marker([lat, lng], {icon: customIcon}).addTo(mapInstance);
                            popup = marker.bindPopup(window.MKDAIQ_Popup(name, stationData, lastResult, unit, date), {
                                maxWidth: 300
                            });
                        }

                    } else {
                        console.error(response.data);
                    }
                },
                error: function (e) {
                    console.error('MKDAIQ: Unable to initialize.');
                }
            });

        };

        // Handle Initialization
        self.initializeMap(null, null, unit, date);

        // Handme Events
        if (unit_filter.length > 0) {
            unit_filter.on('change', function (e) {
                var new_unit = $(this).val();
                self.initializeMap(null, null, new_unit, date);
            });
        }
    };

    $('.mkdaiq-map-element').each(function () {
        $(this).mkdaiqMAP();
    })

})(jQuery);