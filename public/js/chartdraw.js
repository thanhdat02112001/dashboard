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