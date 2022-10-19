const FORMAT_DATE_TIME = 'DD/MM/YYYY';
const buildColumnChart = (idElement, dataChart) => {
    Highcharts.chart(idElement, {
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
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
        credits: {
            enabled: false
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
        },
        xAxis: [{
            categories: dataChart.categories,
            crosshair: true,
            labels: {
                style: {
                    color: '#fff',
                }
            },
        }],
        colors: ['#159e5c', '#1174d7'],
        yAxis: [{
                // Primary yAxis
                labels: {
                    format: '{value}M',
                    style: {
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                },
                title: {
                    text: 'GMV (VND)',
                    style: {
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                }
            },
            {
                // Secondary yAxis
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
                        color: '#fff',
                        fontWeight: 'bold',
                    }
                },
                // plotLines: {
                //     color: '#e66c37'
                // },
                opposite: true
            }
        ],
        tooltip: {
            shared: true
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
        series: dataChart.series,
    });
}

const columnChart = {
    updateChart: (idElement, data) => {
        buildColumnChart(idElement, data);
    },
    filter: async(params) => {
        let response = [];
        // await $.ajax({
        //     type: "POST",
        //     url: '/dashboard/ajax/statistics-by-gmv-and-transaction',
        //     data: params,
        //     global: false,
        //     success: function (result) {
        //         response = result;
        //     },
        //     error: function () {
        //         console.log('Không tìm thấy dữ liệu phù hợp.');
        //     }
        // });
        console.log(response)
        return response;
    },
    onChange: async(idElement) => {
        let chartDataType = $('#select-chart-time').find(':selected').val();
        let merchantID = $('#select-merchant-summary').find(':selected').val() ? Number.parseInt($('#select-merchant-summary').find(':selected').val()) : 0;
        let paymentMethodID = $('#select-payment-method-summary').val() ? Number.parseInt($('#select-payment-method-summary').val()) : 0;
        let bankrollID = $('#select-bankroll-summary').val() ? Number.parseInt($('#select-bankroll-summary').val()) : 0;
        let params = {
            type: chartDataType,
            merchant_id: merchantID,
            method_id: paymentMethodID,
            gateway_id: bankrollID,
        }
        const newData = await columnChart.filter(params);
        let dataChart = {}
        dataChart.categories = []
        let rawTimeArr = Object.keys(newData.gmv)
        rawTimeArr.map((rawTime) => {
            if (rawTime.length < 10) {
                dataChart.categories.push(moment(rawTime, 'YYYY-MM').format('MM/YYYY'))
            } else {
                dataChart.categories.push(moment(rawTime, 'YYYY-MM-DD').format('DD/MM/YYYY'))
            }
        });
        dataChart.series = []
        let transactionSeriesData = []
        let gmvSeriesData = []

        for (let [time, transaction] of Object.entries(newData.transactions)) {
            let transactionVolumeInMillion = Number.parseInt(transaction);
            transactionSeriesData.push(transactionVolumeInMillion)
        }

        for (let [time, gmv] of Object.entries(newData.gmv)) {
            let gmvVolumeInMillion = Number.parseInt(Number.parseInt(gmv) / 1000000);
            gmvSeriesData.push(Number.parseInt(gmvVolumeInMillion))
        }
        dataChart.series.push({
            data: gmvSeriesData,
            name: "GMV (VND)",
            type: "column",
            tooltip: {
                valueSuffix: ' M'
            },
        })
        dataChart.series.push({
            data: transactionSeriesData,
            name: "Transactions",
            type: "spline",
            tooltip: {
                valueSuffix: ''
            },
            yAxis: 1
        })
        console.log({ dataChart })

        columnChart.updateChart(idElement, dataChart);
    }
}

const summary = {
    onChange: async() => {
        if (!$('#select-time-summary').data('daterangepicker')) {
            return false
        }
        console.log('summary onchange')
        let merchantID = $('#select-merchant-summary').find(':selected').val() ? Number.parseInt($('#select-merchant-summary').find(':selected').val()) : 0;
        let paymentMethodID = $('#select-payment-method-summary').val() ? Number.parseInt($('#select-payment-method-summary').val()) : 0;
        let bankrollID = $('#select-bankroll-summary').val() ? Number.parseInt($('#select-bankroll-summary').val()) : 0;
        let startDate = $('#select-time-summary').data('daterangepicker').startDate.format('YYYY-MM-DD');
        let endDate = $('#select-time-summary').data('daterangepicker').endDate.format('YYYY-MM-DD');

        let ranges = $('#select-time-summary').data('daterangepicker').ranges;
        const rangesArr = Object.entries(ranges);
        let timeLabel = null;
        for (const [rangeLabel, rangeValue] of rangesArr) {
            let from = moment(rangeValue[0]._d).format('YYYY-MM-DD');
            let to = moment(rangeValue[1]._d).format('YYYY-MM-DD');
            if (from === startDate && to === endDate) {
                switch (rangeLabel) {
                    case 'Today':
                        timeLabel = 'Last day';
                        break;
                        // case 'Yesterday':
                        //     timeLabel = 'Last 2 days';
                        //     break;
                        // case 'Last 7 Days':
                        //     timeLabel = 'Last 7 days';
                        //     break;
                        // case 'Last 30 Days':
                        //     timeLabel = 'Last 30 Days';
                        //     break;
                    case 'This Month':
                        timeLabel = 'Last month';
                        break;
                        // case 'Last Month':
                        //     timeLabel = 'Last 2 months';
                        //     break;
                    default:
                        timeLabel = null;
                }
            }
        }

        if (timeLabel) {
            $('.time-label').removeClass('d-none').addClass('d-inline-block').html(timeLabel);
        } else {
            $('.time-label').addClass('d-none').html('');
        }

        let params = {
            merchant_id: merchantID,
            method_id: paymentMethodID,
            gateway_id: bankrollID,
            from_date: startDate,
            to_date: endDate
        }
        console.log({ params })
        const newData = await summary.filter(params);
        summary.update(newData)
    },
    filter: async(params) => {
        let response = null;
        // await $.ajax({
        //     type: "POST",
        //     url: '/dashboard/ajax/statistics-gmv',
        //     data: params,
        //     global: false,
        //     success: function (result) {
        //         response = result;
        //     },
        //     error: function () {
        //         console.log('Không tìm thấy dữ liệu phù hợp.');
        //     }
        // });
        return response;
    },
    update: (summaryData) => {
        let totalTransaction = summaryData.total_transaction;
        totalTransaction.current = totalTransaction.current || 0;
        totalTransaction.prev = totalTransaction.prev || 0;

        let totalGMV = summaryData.total_gmv;
        totalGMV.current = totalGMV.current || 0;
        totalGMV.prev = totalGMV.prev || 0;

        let avgGMV = summaryData.avg_gmv;
        avgGMV.current = avgGMV.current || 0;
        avgGMV.prev = avgGMV.prev || 0;

        let invoiceGMV = summaryData.total_invoice;
        let websiteGMV = summaryData.total_website;

        totalTransaction.percentage = (Number.parseFloat(totalTransaction.current) - Number.parseFloat(totalTransaction.prev)) / Number.parseFloat(totalTransaction.prev) * 100;
        $('#total-transaction-volume').html(formatNumberWithSuffix(Number.parseInt(totalTransaction.current)));
        let totalTransactionPercentElement = $('#total-transaction-percent');
        if (!isFinite(totalTransaction.percentage)) {
            totalTransactionPercentElement.html('100%');
        } else {
            totalTransactionPercentElement.html(Math.abs(totalTransaction.percentage).toFixed(2) + '%');
        }

        if (totalTransaction.percentage > 0) {
            $('#total-transaction-img').attr('src', upImgUrl);
            totalTransactionPercentElement.removeClass('decrease');
            totalTransactionPercentElement.addClass('increase');
        } else {
            $('#total-transaction-img').attr('src', downImgUrl);
            totalTransactionPercentElement.addClass('decrease');
            totalTransactionPercentElement.removeClass('increase');
        }

        totalGMV.percentage = (Number.parseFloat(totalGMV.current) - Number.parseFloat(totalGMV.prev)) / Number.parseFloat(totalGMV.prev) * 100;
        $('#total-gmv-volume').html(formatNumberWithSuffix(Number.parseInt(totalGMV.current)));
        let totalGMVPercentElement = $('#total-gmv-percent');
        if (!isFinite(totalGMV.percentage)) {
            totalGMVPercentElement.html('100%');
        } else {
            totalGMVPercentElement.html(Math.abs(totalGMV.percentage).toFixed(2) + '%');
        }
        if (totalGMV.percentage > 0) {
            $('#total-gmv-img').attr('src', upImgUrl);
            totalGMVPercentElement.removeClass('decrease');
            totalGMVPercentElement.addClass('increase');
        } else {
            $('#total-gmv-img').attr('src', downImgUrl);
            totalGMVPercentElement.addClass('decrease');
            totalGMVPercentElement.removeClass('increase');
        }

        avgGMV.percentage = (Number.parseFloat(avgGMV.current) - Number.parseFloat(avgGMV.prev)) / Number.parseFloat(avgGMV.prev) * 100;
        $('#avg-gmv-volume').html(formatNumberWithSuffix(Number.parseInt(avgGMV.current)));
        let avgGMVPercentElement = $('#avg-gmv-percent');
        if (!isFinite(avgGMV.percentage)) {
            avgGMVPercentElement.html('100%');
        } else {
            avgGMVPercentElement.html(Math.abs(avgGMV.percentage).toFixed(2) + '%');
        }
        if (avgGMV.percentage > 0) {
            $('#avg-gmv-img').attr('src', upImgUrl);
            avgGMVPercentElement.removeClass('decrease');
            avgGMVPercentElement.addClass('increase');
        } else {
            $('#avg-gmv-img').attr('src', downImgUrl);
            avgGMVPercentElement.addClass('decrease');
            avgGMVPercentElement.removeClass('increase');
        }

        $('#gmv-invoice-volume').html(formatNumberWithSuffix(Number.parseInt(invoiceGMV)));
        $('#gmv-website-volume').html(formatNumberWithSuffix(Number.parseInt(websiteGMV)));
    },
}