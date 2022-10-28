function gvmvGrowth(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/gmvgrowth",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            Highcharts.chart("column-chart", {
                chart: {
                    zoomType: "xy",
                    marginBottom: 70,
                    height: (10 / 16) * 80 + "%",
                    style: {
                        fontFamily: "Open Sans",
                    },
                    spacingTop: 60,
                    backgroundColor: "#13173c",
                },
                title: {
                    text: "GMV and transaction growth chart",
                    verticalAlign: "top",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                    y: -15,
                    x: -20,
                },
                xAxis: [
                    {
                        categories: res.categories,
                        crosshair: true,
                        style: {
                            color: "#fff",
                        },
                    },
                ],
                colors: ["#159e5c", "#1174d7"],
                tooltip: {
                    shared: true,
                },
                yAxis: [
                    {
                        // Primary yAxis
                        labels: {
                            format: "{value}M",
                        },
                        title: {
                            text: "GMV (VND)",
                            style: {
                                color: "#fff",
                                fontWeight: "bold",
                            },
                        },
                    },
                    {
                        // Secondary yAxis
                        title: {
                            text: "Transactions",
                            style: {
                                color: "#fff",
                                fontWeight: "bold",
                            },
                        },
                        labels: {
                            format: "{value}",
                            style: {
                                color: Highcharts.getOptions().colors[0],
                            },
                        },
                        opposite: true,
                    },
                ],
                legend: {
                    layout: "horizontal",
                    align: "top",
                    verticalAlign: "top",
                    itemMarginTop: 0,
                    itemStyle: {
                        color: "#fff",
                    },
                },
                series: [
                    {
                        name: "GMV",
                        type: "column",
                        data: res.columns,
                        tooltip: {
                            valueSuffix: " M",
                        },
                    },
                    {
                        name: "Transactions",
                        yAxis: 1,
                        type: "spline",
                        data: res.line,
                    },
                ],
            });
        },
    });
}

function gmv_okr(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/gmvinfo",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            $(".percent").show();
            $("#total-transaction-volume").text(res.gmv_okr.toFixed(0));
            if (res.percent_gmv != null) {
                $("#total-transaction-percent").text(
                    res.percent_gmv.toFixed(0)+ "%"
                );
                $("#total-gmv-percent").text(res.percent_total_gmv.toFixed(0)+ "%");
                $("#avg-gmv-percent").text(res.percent_avg_gmv.toFixed(0) + "%");
            } else {
                $(".percent").hide();
            }
            $("#total-gmv-volume").text(res.total_gmv.toFixed(0));
            $("#avg-gmv-volume").text(res.avg_gmv.toFixed(0));
            $("#gmv-invoice-volume").text(res.gmv_invoice.toFixed(0));
            $("#gmv-website-volume").text(res.gmv_ecom.toFixed(0));
            $(".first-table").children().remove();
            $(".second-table").children().remove();
            let html = "";
            let totalgmv = 0;
            let totaltrans = 0;
            let totalError = 0;
            let thead = `<table class="table">
                            <thead>
                                <tr>
                                    <th>Card number</th>
                                    <th>GMV</th>
                                    <th>Count</td>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="table-overflow" id="card-error-1">`,
                tfoot = `</div>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right mr-5" id="tb_gmv"></th>
                                    <th class="text-right" id="tb_trans"></th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>`;
            let thead2 = `<table class="table">
                        <thead>
                            <tr>
                                <th>Card Number</th>
                                <th>Number of errors</th>
                            </tr>
                        </thead>
                            <tbody>`,
                html2 = "",
                tfoot2 = `<tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right" id="tb_error"></th>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>`;
            if (res.cardError.length == 0) {
                html = `<tr>
                        <td colspan="3">No card found</td>
                    </tr>`;
                html2 = `<tr>
                        <td colspan="3">No card found</td>
                    </tr>`;
            }
            res.cardError.forEach((card) => {
                totalgmv += card["gmv"];
                totaltrans += card["trans"];
                totalError += card["errors"];
                html += `<tr class="tchild">
                                <td>${card["card_no"]}</td>
                                <td class="text-right">${card["gmv"]}</td>
                                <td class="text-right">${card["trans"]}</td>
                            </tr>`;

                html2 += `<tr>
                                <td>${card["card_no"]}</td>
                                <td class="text-right">${card["errors"]}</td>
                            </tr>`;
            });
            html = thead + html + tfoot;
            html2 = thead2 + html2 + tfoot2;
            $(".first-table").append(html);
            $(".second-table").append(html2);
            $("#tb_gmv").text(totalgmv);
            $("#tb_trans").text(totaltrans);
            $("#tb_error").text(totalError);
        },
    });
}

function gvmvGrowth(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/gmvgrowth",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            Highcharts.chart("column-chart", {
                chart: {
                    zoomType: "xy",
                    marginBottom: 70,
                    height: (10 / 16) * 80 + "%",
                    style: {
                        fontFamily: "Open Sans",
                    },
                    spacingTop: 60,
                    backgroundColor: "#13173c",
                },
                title: {
                    text: "GMV and transaction growth chart",
                    verticalAlign: "top",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                    y: -15,
                    x: -20,
                },
                xAxis: [
                    {
                        categories: res.categories,
                        crosshair: true,
                        style: {
                            color: "#fff",
                        },
                    },
                ],
                colors: ["#159e5c", "#1174d7"],
                tooltip: {
                    shared: true,
                },
                yAxis: [
                    {
                        // Primary yAxis
                        labels: {
                            format: "{value}M",
                        },
                        title: {
                            text: "GMV (VND)",
                            style: {
                                color: "#fff",
                                fontWeight: "bold",
                            },
                        },
                    },
                    {
                        // Secondary yAxis
                        title: {
                            text: "Transactions",
                            style: {
                                color: "#fff",
                                fontWeight: "bold",
                            },
                        },
                        labels: {
                            format: "{value}",
                            style: {
                                color: Highcharts.getOptions().colors[0],
                            },
                        },
                        opposite: true,
                    },
                ],
                legend: {
                    layout: "horizontal",
                    align: "top",
                    verticalAlign: "top",
                    itemMarginTop: 0,
                    itemStyle: {
                        color: "#fff",
                    },
                },
                series: [
                    {
                        name: "GMV",
                        type: "column",
                        data: res.columns,
                        tooltip: {
                            valueSuffix: " M",
                        },
                    },
                    {
                        name: "Transactions",
                        yAxis: 1,
                        type: "spline",
                        data: res.line,
                    },
                ],
            });
        },
    });
}

function gmvProportion(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/gmv-proportion",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            console.log(res);
            Highcharts.chart("chart-pie", {
                chart: {
                    plotBackgroundColor: "",
                    plotBorderWidth: null,
                    plotShadow: false,
                    height: (10 / 16) * 100 + "%",
                    type: "pie",
                    // borderRadius: '10px',
                    style: {
                        fontFamily: "Open Sans",
                    },
                    spacingTop: 20,
                    backgroundColor: "#13173c",
                },
                title: {
                    text: "The GMV proportion",
                    verticalAlign: "top",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                    y: 20,
                },
                tooltip: {
                    pointFormat:
                        "{series.name}: <b>{point.percentage:.1f}%</b>",
                },
                accessibility: {
                    point: {
                        valueSuffix: "%",
                    },
                },
                plotOptions: {
                    pie: {
                        innerSize: "60%",
                        allowPointSelect: true,
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "<b>{point.name}</b>: {point.percentage:.1f} %",
                        },
                        showInLegend: false,
                    },
                },

                series: [
                    {
                        name: "GMV Proportion",
                        colorByPoint: true,
                        data: res,
                    },
                ],
            });
        },
    });
}

function statusOfBrand(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/trans-status-of-brands",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            Highcharts.chart("horizional-barchart", {
                chart: {
                    type: "bar",
                    height: (10 / 16) * 80 + "%",
                    style: {
                        fontFamily: "Open Sans",
                    },
                    backgroundColor: "#13173c",
                },
                title: {
                    text: "Transaction status by issuing bank and card bank",
                    verticalAlign: "top",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                },
                xAxis: {
                    categories: res.categories,
                    min: 0,
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: null,
                    },
                },
                legend: {
                    reversed: true,
                    itemStyle: {
                        color: "#fff",
                    },
                },
                plotOptions: {
                    series: {
                        stacking: "normal",
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                        },
                    },
                },
                series: [
                    {
                        name: "success",
                        data: res.success,
                    },
                    {
                        name: "cancel",
                        data: res.cancel,
                    },
                    {
                        name: "process",
                        data: res.process,
                    },
                    {
                        name: "other",
                        data: res.other,
                    },
                ],
            });
        },
    });
}

function rateTransaction(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/rateTransaction",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            var datesTotal = res.total.date,
                datesSuccess = res.success.date,
                datesError = res.error.date;
            console.log(res);
            Highcharts.chart("rate-transaction", {
                chart: {
                    type: "areaspline",
                    marginBottom: 70,
                    height: (10 / 16) * 60 + "%",
                    style: {
                        fontFamily: "Open Sans",
                    },
                    spacingTop: 60,
                    backgroundColor: "#13173c",
                },
                title: {
                    text: "The coversion rate of transactions",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                },
                legend: {
                    layout: "vertical",
                    align: "left",
                    verticalAlign: "top",
                    x: 120,
                    y: 70,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor ||
                        "#FFFFFF",
                },
                xAxis: {
                    type: "datetime",
                    labels: {
                        formatter: function () {
                            return Highcharts.dateFormat(
                                "%Y-%m-%d",
                                this.value
                            );
                        },
                    },
                    tickPositioner: function () {
                        return datesTotal.map(function (date) {
                            return Date.parse(date);
                        });
                    },
                },
                yAxis: {
                    title: {
                        text: "Quantity",
                    },
                },
                credits: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        pointStart: 2000,
                    },
                    areaspline: {
                        fillOpacity: 0.5,
                    },
                },
                series: [
                    {
                        data: (function () {
                            return datesTotal.map(function (date, i) {
                                return [Date.parse(date), res.total.data[i]];
                            });
                        })(),
                        name: "Total Transaction",
                    },
                    {
                        data: (function () {
                            return datesError.map(function (date, i) {
                                return [Date.parse(date), res.error.data[i]];
                            });
                        })(),
                        name: "Error Transaction",
                    },
                    {
                        data: (function () {
                            return datesSuccess.map(function (date, i) {
                                return [Date.parse(date), res.success.data[i]];
                            });
                        })(),
                        name: "Success Transaction",
                    },
                ],
            });
        },
    });
}

function issueBank(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/issueBank",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (res) {
            Highcharts.chart("issue-bank", {
                chart: {
                    type: "treemap",
                    backgroundColor: "#13173c",
                },
                colorAxis: {
                    minColor: "#FFFFFF",
                    maxColor: Highcharts.getOptions().colors[0],
                },
                plotOptions: {
                    treemap: {
                        showInLegend: false,
                    },
                },
                series: [
                    {
                        // layoutAlgorithm: 'squarified',
                        data: res,
                    },
                ],
                title: {
                    text: "GMV by issuing bank",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                },
            });
        },
    });
}

function errorDetail(merchant, dateStart, dateEnd, payment, gateway) {
    $.ajax({
        type: "POST",
        url: "/errorDetail",
        data: {
            merchantId: merchant,
            dateStart: dateStart,
            dateEnd: dateEnd,
            payMethod: payment,
            gateWay: gateway,
        },
        success: function (response) {
            errorNames = [];
            errorCounts = [];
            response.forEach((res) => {
                errorCounts.push(res.total);
                switch (res.trans_status) {
                    case 1:
                        errorNames.push("Customer Cancel");
                        break;
                    case 2:
                        errorNames.push("Wrong Parameters");
                        break;
                    case 3:
                        errorNames.push("Invalid_OTP");
                        break;
                    case 4:
                        errorNames.push("Invalid_ExpDate");
                        break;
                    default:
                        break;
                }
            });

            Highcharts.chart("error-detail", {
                chart: {
                    type: "bar",
                    inverted: true,
                    height: 191 + "%",
                    style: {
                        fontFamily: "Open Sans",
                    },
                    backgroundColor: "#13173c",
                },

                title: {
                    text: "Error Details",
                    style: {
                        fontWeight: "bold",
                        fontSize: "16px",
                        color: "#fff",
                    },
                },

                xAxis: {
                    categories: errorNames,
                    style: {
                        color: "#fff",
                        fontWeight: "bold",
                    },
                },
                tooltip: {
                    valueSuffix: "errors",
                },

                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: "{y} errors",
                        },
                    },
                },
                legend: {
                    enabled: false,
                },
                series: [
                    {
                        name: "Errors",
                        pointStart: 0,
                        data: errorCounts,
                    },
                ],
            });
        },
    });
}

function formatDate(date) {
    var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [year, month, day].join("-");
}

$(".filter-data").change(function () {
    let merchant = $("#select-merchant-summary").find(":selected").val();
    let date = $("#select-time-summary").val().split(" - ");
    let start = formatDate(new Date(date[0]), "YYYY-MM-DD");
    let end = formatDate(new Date(date[1]), "YYYY-MM-DD");
    let payment = $("#select-payment-method-summary").find(":selected").val();
    let gateway = $("#select-bankroll-summary").find(":selected").val();
    gmv_okr(merchant, start, end, payment, gateway);
    gvmvGrowth(merchant, start, end, payment, gateway);
    gmvProportion(merchant, start, end, payment, gateway);
    statusOfBrand(merchant, start, end, payment, gateway);
    issueBank(merchant, start, end, payment, gateway);
    rateTransaction(merchant, start, end, payment, gateway);
    errorDetail(merchant, start, end, payment, gateway);
});
