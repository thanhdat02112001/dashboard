$.ajax({
    type: 'GET',
    url: '/gmvgrowth',
    success: function(res) {
        Highcharts.chart('column-chart', {
            chart: {
                zoomType: 'xy',
                marginBottom: 70,
                height: (10 / 16 * 120) + '%',
                style: {
                    fontFamily: 'Open Sans'
                },
                spacingTop: 60,
                backgroundColor: '#13173c',
            },
            title: {
                text: 'GMV and transaction growth chart',
                verticalAlign: 'top',
                style: {
                    fontWeight: 'bold',
                    fontSize: '16px',
                    color: '#fff',
                },
                y: -15,
                x: -20,
            },
            xAxis: [{
                categories: res.categories,
                crosshair: true,
                style: {
                    color: '#fff',
                }
            }],
            colors: ['#159e5c', '#1174d7'],
            tooltip: {
                shared: true
            },
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}M',

                },
                title: {
                    text: 'GMV (VND)',
                    style: {
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Transactions',
                    style: {
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            legend: {
                layout: 'horizontal',
                align: 'top',
                verticalAlign: 'top',
                itemMarginTop: 0,
                itemStyle: {
                    color: '#fff'
                }
            },
            series: [{
                name: 'GMV',
                type: 'column',
                data: res.columns,
                tooltip: {
                    valueSuffix: ' M'
                }

            }, {
                name: 'Transactions',
                yAxis: 1,
                type: 'spline',
                data: res.line,
            }]
        });
    }
})

// Create the chart
$.ajax({
    type: 'GET',
    url: '/gmv-proportion',
    success: function(res) {
        console.log(res)
        Highcharts.chart('chart-pie', {
            chart: {
                type: 'pie',
                marginBottom: 0,
                height: (10 / 16 * 175) + '%',
                style: {
                    fontFamily: 'Open Sans'
                },
                spacingTop: 20,
                backgroundColor: '#13173c',
            },
            title: {
                text: 'The GMV Proportion',
                verticalAlign: 'top',
                style: {
                    fontWeight: 'bold',
                    fontSize: '16px',
                    color: '#fff',
                },
                y: 20,
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                },
                point: {
                    valueSuffix: '%'
                }
            },

            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y:.1f}%'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            },
            legend: {
                layout: 'horizontal',
                align: 'bottom',
                verticalAlign: 'bottom',
                itemMarginBottom: 0,
                itemStyle: {
                    color: '#fff'
                }
            },
            series: [{
                name: "GMV Proportion",
                colorByPoint: true,
                data: res
            }],
        });
    }
})

$.ajax({
    type: 'get',
    url: '/trans-status-of-brands',
    success: function(res) {
        Highcharts.chart('horizional-barchart', {
            chart: {
                type: 'bar',
                marginBottom: 70,
                height: (10 / 16 * 120) + '%',
                style: {
                    fontFamily: 'Open Sans'
                },
                spacingTop: 60,
                backgroundColor: '#13173c',
            },
            title: {
                text: 'Transaction status by issuing bank and card bank',
                verticalAlign: 'top',
                style: {
                    fontWeight: 'bold',
                    fontSize: '16px',
                    color: '#fff',
                },
            },
            xAxis: {
                categories: res.categories
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Goals'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: res.data
        });
    }
})

$.ajax({
    type: 'get',
    url: '/issueBank',
    success: function(res) {
        Highcharts.chart('issue-bank', {
            colorAxis: {
                minColor: '#FFFFFF',
                maxColor: Highcharts.getOptions().colors[0]
            },
            series: [{
                type: 'treemap',
                layoutAlgorithm: 'squarified',
                data: res
            }],
            title: {
                text: 'Transaction status by issuing bank and card bank'
            }
        });
    }
})

$.ajax({
    type: 'get',
    url: '/errorDetail',
    success: function(res) {
        console.log(res);
        Highcharts.chart('error-detail', {
            chart: {
                type: 'columnrange',
                inverted: true
            },

            accessibility: {
                description: 'Image description: A column range chart compares the monthly temperature variations throughout 2017 in Vik I Sogn, Norway. The chart is interactive and displays the temperature range for each month when hovering over the data. The temperature is measured in degrees Celsius on the X-axis and the months are plotted on the Y-axis. The lowest temperature is recorded in March at minus 10.2 Celsius. The lowest range of temperatures is found in December ranging from a low of minus 9 to a high of 8.6 Celsius. The highest temperature is found in July at 26.2 Celsius. July also has the highest range of temperatures from 6 to 26.2 Celsius. The broadest range of temperatures is found in May ranging from a low of minus 0.6 to a high of 23.1 Celsius.'
            },

            title: {
                text: 'Total error of system'
            },

            subtitle: {
                text: ''
            },

            xAxis: {
                categories: ['Error 1', 'Error 2', 'Error 3', 'Error 4', 'Error 5', 'Error 6',
                    'Error 7', 'Error 8'
                ]
            },

            yAxis: {
                title: {
                    text: 'Number of errors'
                }
            },

            tooltip: {
                valueSuffix: 'error'
            },

            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        format: '{y} errors'
                    }
                }
            },

            legend: {
                enabled: false
            },

            series: [{
                name: 'Error Detail',
                data: res
            }]

        });
    }
})


$.ajax({
    type: 'get',
    url: '/rateTransaction',
    success: function(res) {
        var datesTotal = res.total.date,
            datesSuccess = res.success.date,
            datesError = res.error.date;
        console.log(res);
        Highcharts.chart('rate-transaction', {
            chart: {
                type: 'areaspline'
            },
            title: {
                text: 'Moose and deer hunting in Norway, 2000 - 2021'
            },
            subtitle: {
                align: 'center',
                text: 'Source: <a href="https://www.ssb.no/jord-skog-jakt-og-fiskeri/jakt" target="_blank">SSB</a>'
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 120,
                y: 70,
                floating: true,
                borderWidth: 1,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    formatter: function() {
                        return Highcharts.dateFormat('%Y-%m-%d', this.value);
                    }
                },
                tickPositioner: function() {
                    return datesTotal.map(function(date) {
                        return Date.parse(date);
                    });
                }
            },
            yAxis: {
                title: {
                    text: 'Quantity'
                }
            },
            tooltip: {
                shared: true,
                headerFormat: '<b>Hunting season starting autumn {point.x}</b><br>'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    pointStart: 2000
                },
                areaspline: {
                    fillOpacity: 0.5
                }
            },
            series: [{
                    data: (function() {
                        return datesTotal.map(function(date, i) {
                            return [Date.parse(date), res.total.data[i]];
                        });
                    })(),
                    name: 'Total Transaction'
                },
                {
                    data: (function() {
                        return datesError.map(function(date, i) {
                            return [Date.parse(date), res.error.data[i]];
                        });
                    })(),
                    name: 'Error Transaction'
                },
                {
                    data: (function() {
                        return datesSuccess.map(function(date, i) {
                            return [Date.parse(date), res.success.data[i]];
                        });
                    })(),
                    name: 'Success Transaction'
                },
            ]
        });
    }
})