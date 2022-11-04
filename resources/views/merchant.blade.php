@extends('layouts.app')

@section('title', 'Merchant')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                                {{ Form::select('select-merchant-summary', $merchantData, null, ['id' => 'select-merchant-summary', 'class' => 'form-control select-merchant filter-data', 'placeholder' => 'All Merchant']) }}
                            </div>
                            <div class="col-lg-6">
                                
                                {{ Form::select('select-date', ['Hôm qua','7 ngày gần nhất', '30 ngày gần nhất', 'Tháng này', 'Tháng trước'] , null, ['id' => 'select-date', 'class' => 'form-control text-center date-input filter-data', 'placeholder' => 'Hôm nay']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-6">
                                {{ Form::select('select-payment-method-summary', $methodData , null, ['id' => 'select-payment-method-summary', 'class' => 'form-control payment-method-select filter-data', 'placeholder' => 'All payment method', 'onchange' => "changePaymentMethod(this)"]) }}
                            </div>
                            <div class="col-lg-6">
                                {{ Form::select('select-bankroll-summary', $gatewaysData , null, ['id' => 'select-bankroll-summary', 'class' => 'form-control bankroll-select filter-data', 'placeholder' => 'All gateway', 'onchange' => "changeBankroll(this)"]) }}
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
                                    <div  class="percent">
                                        @if ($data['percent_gmv'])
                                        <img src="{{$data['percent_gmv'] > 0 ? asset('images/up.png') : asset('images/down.png') }}" alt="up" class="up-img" id="total-transaction-img">
                                        @endif
                                        <span class="box-info-percent" id="total-transaction-percent">{{ number_format($data['percent_gmv']) }}%</span>
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
                                        <span class="text-light box-info-volume" id="total-gmv-volume">{{ number_format($data['total_gmv']) }}</span>
                                        <span class="text-light time-label box-info-volume">M</span>
                                    </div>
                                    <div  class="percent">
                                        <img src="{{ $data['percent_total_gmv'] > 0 ? asset('images/up.png') : asset('images/down.png')}}" alt="up" class="up-img" id="total-gmv-img">
                                        <span class="box-info-percent" id="total-gmv-percent">{{ number_format($data['percent_total_gmv']) }}%</span>
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
                                        <span class="text-light box-info-volume" id="avg-gmv-volume">{{ number_format($data['avg_gmv']) }}</span>
                                        <span class="text-light time-label box-info-volume">M</span>
                                    </div>
                                    <div class="percent">
                                        <img src="{{ $data['percent_avg_gmv'] > 0 ? asset('images/up.png') : asset('images/down.png') }}" alt="up" class="up-img" id="avg-gmv-img">
                                        <span class="box-info-percent" id="avg-gmv-percent">{{ number_format($data['percent_avg_gmv']) }}%</span>
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
                                <figure class="highcharts-figure">
                                    <div id="column-chart"></div>
                                </figure>
                                <figure class="highcharts-figure">
                                    <div id="horizional-barchart"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="chart-right">
                            <div id="chart-pie"></div>
                            <div id="issue-bank"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="rate-trans">
                            <div id="rate-transaction"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="col-lg-12">
                    <div class="card-table first-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Card number</th>
                                    <th>GMV</th>
                                    <th>Count</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalGmv = 0;
                                    $totalTrans = 0;
                                @endphp
                                <div class="table-overflow">
                                    @foreach ($cardDatas as $card)
                                    <tr class="tchild">
                                        <td>{{$card['card_no']}}</td>
                                        <td class="text-right">{{number_format($card['gmv'])}}</td>
                                        <td class="text-right">{{$card['trans']}}</td>
                                    </tr>
                                    @php
                                        $totalGmv += $card['gmv'];
                                        $totalTrans += $card['trans'];
                                    @endphp
                                    @endforeach
                                </div>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-right mr-5" id="tb_gmv">{{number_format($totalGmv)}}</th>
                                        <th class="text-right" id="tb_trans">{{$totalTrans}}</th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-table second-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Card Number</th>
                                    <th>Number of errors</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalError = 0;
                                @endphp
                                @foreach ($cardDatas as $card)
                                    <tr>
                                        <td>{{$card['card_no']}}</td>
                                        <td class="text-right">{{$card['errors']}}</td>
                                        @php
                                            $totalError += $card['errors'];
                                        @endphp
                                    </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-right">{{$totalError}}</th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12">
                    <figure class="highcharts-figure">
                        <div id="error-detail"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{--HIGHT CHART--}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>

    {{-- <script src="/js/dashboard_merchant.js"></script> --}}
    <script src="/js/chartdraw.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/treemap.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
    $('input[id="select-time-summary"]').daterangepicker();
    </script>
@endsection
