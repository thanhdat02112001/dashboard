function gvmvGrowth(query = '') {
    $.ajax({
        type: 'GET',
        url: '/gmvgrowth' + query,
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
}

function gmvProportion(query = '') {
    $.ajax({
        type: 'GET',
        url: '/gmv-proportion' + query,
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
                        innerSize: '60%',
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
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
}

function statusOfBrand(query = '') {
    $.ajax({
        type: 'get',
        url: '/trans-status-of-brands' + query,
        success: function(res) {
            Highcharts.chart('horizional-barchart', {
                chart: {
                    type: 'bar',
                    height: (10 / 16 * 80) + '%',
                    style: {
                        fontFamily: 'Open Sans'
                    },
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
                    categories: res.categories,
                    min: 0,
                    max: 4,
                    scrollbar: {
                        enabled: true
                    },
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: null
                    }
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
                    },
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                        'name': 'success',
                        'data': res.success
                    },
                    {
                        'name': 'cancel',
                        'data': res.cancel
                    },
                    {
                        'name': 'process',
                        'data': res.process
                    },
                    {
                        'name': 'other',
                        'data': res.other
                    },
                ]
            });
        }
    })
}

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
                    type: 'treemap',
                    backgroundColor: '#13173c',
                },
                colorAxis: {
                    minColor: '#FFFFFF',
                    maxColor: Highcharts.getOptions().colors[0]
                },
                plotOptions: {
                    treemap: {
                        showInLegend: false,
                    }
                },
                series: [{
                    // layoutAlgorithm: 'squarified',
                    data: res
                }],
                title: {
                    text: 'GMV by issuing bank',
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
                    type: 'bar',
                    inverted: true,
                    height: (10 / 16 * 215) + '%',
                    style: {
                        fontFamily: 'Open Sans'
                    },
                    backgroundColor: '#13173c',
                },

                title: {
                    text: 'Error Details',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '16px',
                        color: '#fff',
                    },
                },

                xAxis: {
                    categories: [
                        'Customer Cancel',
                        'Wrong Parameters',
                        'Invalid_ExpDate',
                        'Invalid_OTP',
                    ],
                    style: {
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                },
                tooltip: {
                    valueSuffix: 'errors'
                },

                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{y} errors'
                        },

                    },
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: 'Errors',
                    pointStart: 0,
                    data: res
                }]

            });
        }
    })
}


errorDetail();
issueBank();
rateTransaction();
statusOfBrand();
gmvProportion();
gvmvGrowth();

var query = '?a=a';

function changeMerchant(sel) {
    query += '&merchanId=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
    statusOfBrand(query);
    gmvProportion(query);
    gvmvGrowth(query);
}

function changePaymentMethod(sel) {
    query += '&payMethod=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
    statusOfBrand(query);
    gmvProportion(query);
    gvmvGrowth(query);
}

function changeBankroll(sel) {
    query += '&gateWay=' + sel.value;
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
    statusOfBrand(query);
    gmvProportion(query);
    gvmvGrowth(query);
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function changeDate(inp) {
    let date = inp.value.split(' - ');
    let start = new Date(date[0]);
    let end = new Date(date[1]);
    query += '&dateStart=' + formatDate(start, 'YYYY-MM-DD') + '&dateEnd=' + formatDate(end, 'YYYY-MM-DD');
    errorDetail(query);
    issueBank(query);
    rateTransaction(query);
    statusOfBrand(query);
    gmvProportion(query);
    gvmvGrowth(query);
}
