@extends('layouts.app')

@section('title', 'Merchant')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- <link href="{{ asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/plugins/select2/select2.min.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" href="/css/dashboard_merchant.css">
@endsection

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            {{--<div class="col-lg-12">--}}
            <div class="col-lg-9">
                {{--row1--}}
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-lg-6">
                                {{ Form::select('select-merchant-summary', $merchantData, null, ['id' => 'select-merchant-summary', 'class' => 'form-control select-merchant', 'placeholder' => 'All Merchant', 'onChange' => 'changeMerchant()']) }}
                            </div>
                            <div class="col-lg-6">
                                <input class="form-control text-center date-input"
                                       onchange="changeDate()"
                                       readonly="readonly"
                                       id="select-time-summary"
                                       type="text"
                                       name="daterange"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-6">
                                {{ Form::select('select-payment-method-summary', $methodData ?? [], null, ['id' => 'select-payment-method-summary', 'class' => 'form-control payment-method-select', 'placeholder' => 'All payment method', 'onchange' => "changePaymentMethod()"]) }}
                            </div>
                            <div class="col-lg-6">
                                {{ Form::select('select-bankroll-summary', $gatewaysData ?? [], null, ['id' => 'select-bankroll-summary', 'class' => 'form-control bankroll-select', 'placeholder' => 'All gateway', 'onchange' => "changeBankroll()"]) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{--row2--}}
                <div class="row pt-3">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="square-box-info total-trans">
                                    <div>
                                        <img src="{{ asset('images/total-transactions.png') }}" alt="total-transactions" class="total-transaction-img">
                                        <span class="text-light box-info-title">Total transaction</span>
                                    </div>
                                    <div class="py-2">
                                        <span class="text-light box-info-volume" id="total-transaction-volume">{{ number_format($data['gmv_okr']) }}</span>
                                    </div>
                                    <div>
                                        <img src="{{ asset('images/up.png') }}" alt="up" class="up-img" id="total-transaction-img">
                                        <span class="box-info-percent" id="total-transaction-percent">{{ number_format($data['percent_gmv']) }}%</span>
                                        <span class="d-none text-light ml-1 time-label"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="square-box-info total-trans">
                                    <div>
                                        <img src="{{ asset('images/total-gmv.png') }}" alt="total-transactions" class="total-transaction-img">
                                        <span class="text-light box-info-title">Total GMV</span>
                                    </div>
                                    <div class="py-2">
                                        <span class="text-light box-info-volume" id="total-gmv-volume">{{ number_format($data['total_gmv']) }} M</span>
                                    </div>
                                    <div>
                                        <img src="{{ asset('images/up.png') }}" alt="up" class="up-img" id="total-gmv-img">
                                        <span class="box-info-percent" id="total-gmv-percent">{{ number_format($data['percent_total_gmv']) }}%</span>
                                        <span class="d-none text-light ml-1 time-label"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="square-box-info total-trans">
                                    <div>
                                        <img src="{{ asset('images/average-gmv.png') }}" alt="total-transactions" class="total-transaction-img">
                                        <span class="text-light box-info-title">Avg.GMV</span>
                                    </div>
                                    <div class="py-2">
                                        <span class="text-light box-info-volume" id="avg-gmv-volume">{{ number_format($data['avg_gmv']) }} M</span>
                                    </div>
                                    <div>
                                        <img src="{{ asset('images/up.png') }}" alt="up" class="up-img" id="avg-gmv-img">
                                        <span class="box-info-percent" id="avg-gmv-percent">{{ number_format($data['percent_avg_gmv']) }}%</span>
                                        <span class="d-none text-light ml-1 time-label"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="square-box-info total-trans">
                                    <div>
                                        <img src="{{ asset('images/gmv-invoice.png') }}" alt="total-transactions" class="total-transaction-img">
                                        <span class="text-light box-info-title">GMV Invoice</span>
                                    </div>
                                    <div class="py-2">
                                        <span class="text-light box-info-volume" id="gmv-invoice-volume">{{ number_format($data['gmv_invoice']) }} M</span>
                                    </div>
                                    <div>
                                        <img src="{{ asset('images/gmv-invoice-up.png') }}" alt="up" class="up-img" id="gmv-invoice-img">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="square-box-info total-trans">
                                    <div>
                                        <img src="{{ asset('images/gmv-website.png') }}" alt="total-transactions" class="total-transaction-img">
                                        <span class="text-light box-info-title">GMV Website</span>
                                    </div>
                                    <div class="py-2">
                                        <span class="text-light box-info-volume" id="gmv-website-volume">{{ number_format($data['gmv_ecom'])}} M</span>
                                    </div>
                                    <div>
                                        <img src="{{ asset('images/gmv-website-up.png') }}" alt="up" class="up-img" id="gmv-website-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--row3--}}
                <div class="row">
                    {{--<div class="col-lg-12">--}}
                    <div class="col-lg-7">
                        <div class="chart-column">
                            <div class="chart-body">
                                <div class="col-3 select-time-transaction-grow-chart">
                                    <div class="form-group">
                                        <select class="form-control" onchange="columnChart.onChange('column-chart')" id="select-chart-time">
                                            <option value="this-month" selected="selected">This month</option>
                                            <option value="last-month">Last month</option>
                                            <option value="this-year">This year</option>
                                            <option value="last-year">Last year</option>
                                        </select>
                                    </div>
                                </div>
                                <figure class="highcharts-figure">
                                    <div id="column-chart"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="chart-column">
                            <div id="chart-pie"></div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <figure class="highcharts-figure">
                            <div id="horizional-barchart"></div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">

            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{ asset('js/plugins/moment/moment.min.js') }}" type="text/javascript"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{-- <script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('js/plugins/select2/select2.min.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{--HIGHT CHART--}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    {{-- <script src="/js/dashboard_merchant.js"></script> --}}
    <script src="/js/chartdraw.js"></script>
    <script>
        let upImgUrl = '{{ asset('images/up.png') }}';
        let downImgUrl = '{{ asset('images/down.png') }}';

        const formatNumberWithSuffix = (number) => {
            const ranges = [
                { divider: 1e18 , suffix: ' E' },
                { divider: 1e15 , suffix: ' P' },
                { divider: 1e12 , suffix: ' T' },
                { divider: 1e9 , suffix: ' G' },
                { divider: 1e6 , suffix: ' M' },
                { divider: 1e3 , suffix: ' K' }
            ];

            for (let i = 0; i < ranges.length; i++) {
                if (number >= ranges[i].divider) {
                    return (number / ranges[i].divider).toFixed(2) + ranges[i].suffix;
                }
            }
            return number.toString();
        }

        // const initElements = async () => {
        //     $('input[name="daterange"]').daterangepicker(
        //         {
        //             opens: 'left',
        //             locale: {
        //                 format: 'DD/MM/YYYY',
        //                 separator: " - ",
        //             },
        //             maxDate: moment(),
        //             ranges: {
        //                 'Today': [moment(), moment()],
        //                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //                 'This Month': [moment().startOf('month'), moment().endOf('month')],
        //                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        //             }
        //         }
        //     );

        //     $('#select-merchant-summary').select2({
        //         width: '100%',
        //         allowClear: true,
        //         placeholder: 'All Merchant'
        //     })
        // }

        // const changeMerchant = () => {
        //     summary.onChange()
        //     columnChart.onChange('column-chart')
        // }

        // const changeDate = () => {
        //     summary.onChange()
        // }

        // const changePaymentMethod = () => {
        //     summary.onChange()
        //     columnChart.onChange('column-chart')
        // }

        // const changeBankroll = () => {
        //     summary.onChange()
        //     columnChart.onChange('column-chart')
        // }

        // $(document).ready(function() {
        //     initElements().then(() => {
        //         $('#select-payment-method-summary').trigger('change')
        //     })
        // });
        // Data retrieved from https://netmarketshare.com/

        // Data retrieved from https://netmarketshare.com/

    </script>
@endsection
