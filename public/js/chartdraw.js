$.ajax({
    type: 'GET',
    url: '/gmvgrowth',
    success: function(res) {
        Highcharts.chart('column-chart', {
            chart: {
                zoomType: 'xy',
                marginBottom: 70,
                height: (10 / 16 * 80) + '%',
                style: {
                    fontFamily: 'Open Sans',
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
                plotBackgroundColor: '',
                plotBorderWidth: null,
                plotShadow: false,
                height: (10 / 16 * 100) + '%',
                type: 'pie',
                // borderRadius: '10px',
                style: {
                    fontFamily: 'Open Sans'
                },
                spacingTop: 20,
                backgroundColor: '#13173c'
            },
            title: {
                text: 'The GMV proportion',
                verticalAlign: 'top',
                style: {
                    fontWeight: 'bold',
                    fontSize: '16px',
                    color: '#fff',
                },
                y: 20,
            },
            tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
              point: {
                valueSuffix: '%'
              }
            },
            plotOptions: {
              pie: {
                innerSize : '60%',
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: false
                },
                showInLegend: false
              },
            },
            
            series: [{
              name: 'GMV Proportion',
                colorByPoint: true,
              data: res,
            }]
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
                height: (10 / 16 * 80) + '%',
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
                max: 100,
            },
            legend: {
                reversed: true,
                itemStyle: {
                    color: '#fff'
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            // res.data
            series: [{
                'name': 'cancel',
                'data' : [15, 20, 45, 20]
            },
            {
                'name': 'abc',
                'data' : [20, 20, 15, 30]
            },
            {
                'name': 'success',
                'data' : [65, 60, 40, 50]
            },
        ]
        });
    }
})

function rateTransaction(query = '') {
    $.ajax({
        type: 'get',
        url: '/rateTransaction' + query,
        success: function(res) {
            var datesTotal = res.total.date,
                datesSuccess = res.success.date,
                datesError = res.error.date;
            console.log(res);
            Highcharts.chart('rate-transaction', {
                chart: {
                    type: 'areaspline',
                    marginBottom: 70,
                    height: (10 / 16 * 60) + '%',
                    style: {
                        fontFamily: 'Open Sans',
                    },
                    spacingTop: 60,
                    backgroundColor: '#13173c',
                },
                title: {
                    text: 'The coversion rate of transactions',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '16px',
                        color: '#fff',
                    },
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
}

function issueBank(query = '') {
    $.ajax({
        type: 'get',
        url: '/issueBank' + query,
        success: function(res) {
            Highcharts.chart('issue-bank', {
                chart: {
                    backgroundColor: '#13173c',
                },
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
                    text: 'Transaction status by issuing bank and card bank',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '16px',
                        color: '#fff',
                    },
                }
            });
        }
    })
}

function errorDetail(query = '') {
    $.ajax({
        type: 'get',
        url: '/errorDetail' + query,
        success: function(res) {
            console.log(res);
            Highcharts.chart('error-detail', {
                chart: {
                    type: 'columnrange',
                    inverted: true,
                    marginBottom: 70,
                    height: (10 / 16 * 180) + '%',
                    style: {
                        fontFamily: 'Open Sans'
                    },
                    spacingTop: 60,
                    backgroundColor: '#13173c',
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
                    enabled: false,
                    itemStyle: {
                        color: '#fff'
                    }
                },

                series: [{
                    name: 'Error Detail',
                    data: res
                }]

            });
        }
    })
}


errorDetail();
issueBank();
rateTransaction();

var query = '?a=a';

function changeMerchant(sel) {
    query += '&merchanId=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
}

function changePaymentMethod(sel) {
    query += '&payMethod=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
}

function changeBankroll(sel) {
    query += '&gateWay=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
}

function changeDate(inp) {
    query += '&date=' + inp.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
}
