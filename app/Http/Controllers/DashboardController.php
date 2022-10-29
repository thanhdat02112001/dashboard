<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Method;
use App\Models\ReportTransaction;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $startTime = Carbon::now()->copy()->startOfDay()->toDateTimeString();
        $endTime = Carbon::now()->toDateTimeString();
        $prevDay = Carbon::now()->copy()->startOfDay()->subDay()->toDateTimeString();
        $prevTime = Carbon::now()->subDay()->toDateTimeString();

        $merchants = DB::table('merchants')->select('id', 'name')->get()->toArray();
        $merchantData = [];
        foreach ($merchants as $merchant) {
            $merchantData[$merchant->id] = $merchant->name;
        }

        $methods = DB::table('payment_methods')->select('id', 'method')->get()->toArray();
        $methodData = [];
        foreach ($methods as $method) {
            $methodData[$method->id] =  $method->method;
        }

        $gateways = DB::table('gateways')->select('id', 'gateway')->get()->toArray();
        $gatewaysData = [];
        foreach ($gateways as $gateway) {
            $gatewaysData[$gateway->id] = $gateway->gateway;
        }
        $card_errors = ReportTransaction::where('trans_status', 3)->pluck('card_id')->toArray();
        $cardErrors = array_count_values($card_errors);
        $cardDatas = [];
        foreach ($cardErrors as $key => $value){
            $card = DB::table('cards')->where('id', $key)->first();
            $trans_by_card = ReportTransaction::where('card_id', $key)->get();
            $tmp_data = [
                'card_no' => $card->card_no,
                'gmv' => $trans_by_card->sum('total_amount'),
                'trans' => count($trans_by_card->toArray()),
                'errors' => $value,
            ];
            array_push($cardDatas, $tmp_data);
        }

        $total_transactions = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])->get()->toArray();
        $transactions = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where('trans_status', 5)->get()->toArray();
        $prev_transactions =  ReportTransaction::whereBetween('created_at', [$prevDay, $prevTime])
        ->where('trans_status', 5)->get()->toArray();
        $total_gmv = ReportTransaction::whereBetween('created_at',[$startTime, $endTime] )
        ->where('trans_status', 5)->sum('total_amount');
        $prev_total_gmv  = ReportTransaction::whereBetween('created_at', [$prevDay, $prevTime])
        ->where('trans_status', 5)->sum('total_amount');
        $gmv_invoice = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where('trans_status', 5)->where('channel', 'invoice')->sum('total_amount');
        $gmv_ecom = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where('trans_status', 5)->where('channel', 'ecom')->sum('total_amount');
        $data = [
            'gmv_okr' => count($total_transactions),
            'percent_gmv' => (count($transactions) - count($prev_transactions)) * 100 / count($prev_transactions),
            'total_gmv' => $total_gmv / 1000000,
            'percent_total_gmv' => ($total_gmv - $prev_total_gmv) * 100/  $prev_total_gmv,
            'avg_gmv' => ($total_gmv / count($transactions)) / 1000000,
            'percent_avg_gmv' => ($total_gmv / count($transactions) - $prev_total_gmv / count($prev_transactions) )* 100 / ($prev_total_gmv / count($prev_transactions)),
            'gmv_invoice' => $gmv_invoice / 1000000,
            'gmv_ecom' => $gmv_ecom / 1000000,
        ];

        return view('merchant', compact('merchantData', 'methodData', 'gatewaysData', 'data', 'cardDatas'));
    }

    public function gmvinfo(Request $request) {
        $startTime = Carbon::now()->copy()->startOfDay()->toDateTimeString();
        $endTime = Carbon::now()->toDateTimeString();
        $prevDay = Carbon::now()->copy()->startOfDay()->subDay()->toDateTimeString();
        $prevTime = Carbon::now()->subDay()->toDateTimeString();

        $conditions = [];
        if(isset($request->merchantId) && $request->merchantId != 'null') {
            $conditions[] = ['merchant_id', $request->merchantId];
        }

        if(isset($request->payMethod) && $request->payMethod != 'null') {
            $conditions[] = ['method_id', $request->payMethod];
        }

        if(isset($request->gateWay) && $request->gateWay != 'null') {
            $conditions[] = ['gateway_id', $request->gateWay];
        }
        $card_errors = ReportTransaction::where('trans_status', 3)->where($conditions)->pluck('card_id')->toArray();
        $cardErrors = array_count_values($card_errors);
        $cardDatas = [];
        foreach ($cardErrors as $key => $value){
            $card = DB::table('cards')->where('id', $key)->first();
            $trans_by_card = ReportTransaction::where('card_id', $key)->get();
            $tmp_data = [
                'card_no' => $card->card_no,
                'gmv' => $trans_by_card->sum('total_amount'),
                'trans' => count($trans_by_card->toArray()),
                'errors' => $value,
            ];
            array_push($cardDatas, $tmp_data);
        }

        $total_transactions = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where($conditions)->get()->toArray();
        $transactions = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where($conditions)->where('trans_status', 5)->get()->toArray();
        $prev_transactions =  ReportTransaction::whereBetween('created_at', [$prevDay, $prevTime])
        ->where($conditions)->where('trans_status', 5)->get()->toArray();
        $total_gmv = ReportTransaction::whereBetween('created_at',[$startTime, $endTime] )
        ->where($conditions)->where('trans_status', 5)->sum('total_amount');
        $prev_total_gmv  = ReportTransaction::whereBetween('created_at', [$prevDay, $prevTime])
        ->where($conditions)->where('trans_status', 5)->sum('total_amount');
        $gmv_invoice = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where($conditions)->where('trans_status', 5)->where('channel', 'invoice')->sum('total_amount');
        $gmv_ecom = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where($conditions)->where('trans_status', 5)->where('channel', 'ecom')->sum('total_amount');
        if(isset($request->dateStart) && $request->dateStart != 'null' &&($request->dateStart != now()->format('Y-m-d')) && isset(request()->dateEnd) && request()->dateEnd != 'null') {
            if ($request->dateStart == $request->dateEnd) {
                $card_errors = ReportTransaction::where('trans_status', 3)->where('dates',$request->dateStart)->where($conditions)->pluck('card_id')->toArray();
                $cardErrors = array_count_values($card_errors);
                $cardDatas = [];
                foreach ($cardErrors as $key => $value){
                    $card = DB::table('cards')->where('id', $key)->first();
                    $trans_by_card = ReportTransaction::where('card_id', $key)->get();
                    $tmp_data = [
                        'card_no' => $card->card_no,
                        'gmv' => $trans_by_card->sum('total_amount'),
                        'trans' => count($trans_by_card->toArray()),
                        'errors' => $value,
                    ];
                    array_push($cardDatas, $tmp_data);
                }
                $preDay = (new Carbon($request->dateStart))->subDay()->toDateString();
                $total_transactions = ReportTransaction::where('dates',$request->dateStart)
                ->where($conditions)->get()->toArray();
                $transactions = ReportTransaction::where('dates',$request->dateStart)
                ->where($conditions)->where('trans_status', 5)->get()->toArray();
                $prev_transactions =  ReportTransaction::where('dates',$preDay)
                ->where($conditions)->where('trans_status', 5)->get()->toArray();
                $total_gmv = ReportTransaction::where('dates',$request->dateStart)
                ->where($conditions)->where('trans_status', 5)->sum('total_amount');
                $prev_total_gmv  = ReportTransaction::where('dates',$preDay)
                ->where($conditions)->where('trans_status', 5)->sum('total_amount');
                $gmv_invoice = ReportTransaction::where('dates',$request->dateStart)
                ->where($conditions)->where('trans_status', 5)->where('channel', 'invoice')->sum('total_amount');
                $gmv_ecom = ReportTransaction::where('dates',$request->dateStart)
                ->where($conditions)->where('trans_status', 5)->where('channel', 'ecom')->sum('total_amount');
                $data = [
                    'gmv_okr' => count($total_transactions),
                    'percent_gmv' => (count($transactions) - count($prev_transactions)) * 100 / count($prev_transactions),
                    'total_gmv' => $total_gmv / 1000000,
                    'percent_total_gmv' => ($total_gmv - $prev_total_gmv) * 100/  $prev_total_gmv,
                    'avg_gmv' => ($total_gmv / count($transactions)) / 1000000,
                    'percent_avg_gmv' => ($total_gmv / count($transactions) - $prev_total_gmv / count($prev_transactions) )* 100 / ($prev_total_gmv / count($prev_transactions)),
                    'gmv_invoice' => $gmv_invoice / 1000000,
                    'gmv_ecom' => $gmv_ecom / 1000000,
                    'cardError' => $cardDatas,
                ];

                return $data;
            } else {
                $total_transactions = ReportTransaction::whereBetween('created_at', [request()->dateStart, request()->dateEnd])->where($conditions)->get()->toArray();
                $transactions = ReportTransaction::whereBetween('created_at', [request()->dateStart, request()->dateEnd])->where($conditions)->where('trans_status', 5)->get()->toArray();
                $total_gmv = ReportTransaction::whereBetween('created_at', [request()->dateStart, request()->dateEnd])->where($conditions)->where('trans_status', 5)->sum('total_amount');
                $gmv_invoice = ReportTransaction::whereBetween('created_at', [request()->dateStart, request()->dateEnd])->where($conditions)->where('trans_status', 5)->where('channel', 'invoice')->sum('total_amount');
                $gmv_ecom = ReportTransaction::whereBetween('created_at',[request()->dateStart, request()->dateEnd])
                ->where($conditions)->where('trans_status', 5)->where('channel', 'ecom')->sum('total_amount');
                $card_errors = ReportTransaction::where('trans_status', 3)->whereBetween('created_at', [request()->dateStart, request()->dateEnd])->where($conditions)->pluck('card_id')->toArray();
                $cardErrors = array_count_values($card_errors);
                $cardDatas = [];
                foreach ($cardErrors as $key => $value){
                    $card = DB::table('cards')->where('id', $key)->first();
                    $trans_by_card = ReportTransaction::where('card_id', $key)->get();
                    $tmp_data = [
                        'card_no' => $card->card_no,
                        'gmv' => $trans_by_card->sum('total_amount'),
                        'trans' => count($trans_by_card->toArray()),
                        'errors' => $value,
                    ];
                    array_push($cardDatas, $tmp_data);
                }
            }
            $data = [
                'gmv_okr' => count($total_transactions),
                'total_gmv' => $total_gmv / 1000000,
                'avg_gmv' => ($total_gmv / count($transactions)) / 1000000,
                'gmv_invoice' => $gmv_invoice / 1000000,
                'gmv_ecom' => $gmv_ecom / 1000000,
                'cardError' => $cardDatas,
            ];
            return $data;
        }

        $data = [
            'gmv_okr' => count($total_transactions),
            'percent_gmv' => (count($transactions) - count($prev_transactions)) * 100 / count($prev_transactions),
            'total_gmv' => $total_gmv / 1000000,
            'percent_total_gmv' => ($total_gmv - $prev_total_gmv) * 100/  $prev_total_gmv,
            'avg_gmv' => ($total_gmv / count($transactions)) / 1000000,
            'percent_avg_gmv' => ($total_gmv / count($transactions) - $prev_total_gmv / count($prev_transactions) )* 100 / ($prev_total_gmv / count($prev_transactions)),
            'gmv_invoice' => $gmv_invoice / 1000000,
            'gmv_ecom' => $gmv_ecom / 1000000,
            'cardError' => $cardDatas,
        ];

        return $data;

    }

    public function gmvGrowth(Request $request)
    {
        $dates = [];
        $transactions = $this->successTrans;
        foreach ($transactions as $key => $trans) {
            if (isset($request->merchantId) && $request->merchantId != null) {
                if ($trans->merchant_id != $request->merchantId){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->payMethod) && $request->payMethod != null) {
                if ($trans->method_id != $request->payMethod){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->gateWay) && $request->gateWay != null) {
                if ($trans->gateway_id != $request->gateWay){
                    unset($transactions[$key]);
                }
            }
            if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
                if ($request->dateStart != now()->format('Y-m-d')) {
                    if ($request->dateStart == $request->dateEnd) {
                        if ($trans->dates != $request->dateStart) {
                            unset($transactions[$key]);
                        }
                    } else {
                        $period = new DatePeriod(
                            new DateTime($request->dateStart),
                            new DateInterval('P1D'),
                            new DateTime($request->dateEnd)
                        );
                        foreach ($period as $dt) {
                            array_push($dates, $dt->format('Y-m-d'));
                        }
                        if (!in_array($trans->dates, $dates)) {
                            unset($transactions[$key]);
                        }
                    }
                } else {
                    $startMonth = Carbon::now()->copy()->startOfMonth();
                    $today = Carbon::now();
                    $interval = DateInterval::createFromDateString(('1 day'));
                    $period = new DatePeriod($startMonth, $interval, $today);
                    foreach ($period as $dt) {
                        array_push($dates, $dt->format('Y-m-d'));
                    }
                    if (!in_array($trans->dates, $dates)) {
                        unset($transactions[$key]);
                    }
                }
            }
        }
        $datas = $transactions->groupBy('dates');
        $categories = [];
        $columns = [];
        $line = [];
        foreach ($datas as $key => $value) {
            array_push($categories, $key);
            array_push($columns, round((int)$value->sum('total_amount') / 1000000, 2));
            array_push($line, count($value));
        }
        return response()->json(['columns' => $columns, 'line' => $line, 'categories' => $dates]);
    }

    public function gmvProportion(Request $request)
    {
        $dates = [];
        $transactions = $this->successTrans;
        foreach ($transactions as $key => $trans) {
            if (isset($request->merchantId) && $request->merchantId != null) {
                if ($trans->merchant_id != $request->merchantId){
                    unset($transactions[$key]);
                }
            }
            // if (isset($request->payMethod) && $request->payMethod != null) {
            //     if ($trans->method_id != $request->payMethod){
            //         unset($transactions[$key]);
            //     }
            // }
            // if (isset($request->gateWay) && $request->gateWay != null) {
            //     if ($trans->gateway_id != $request->gateWay){
            //         unset($transactions[$key]);
            //     }
            // }
            if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
                if ($request->dateStart != now()->format('Y-m-d')) {
                    if ($request->dateStart == $request->dateEnd) {
                        if ($trans->dates != $request->dateStart) {
                            unset($transactions[$key]);
                        }
                    } else {
                        $period = new DatePeriod(
                            new DateTime($request->dateStart),
                            new DateInterval('P1D'),
                            new DateTime($request->dateEnd)
                        );
                        foreach ($period as $dt) {
                            array_push($dates, $dt->format('Y-m-d'));
                        }
                        if (!in_array($trans->dates, $dates)) {
                            unset($transactions[$key]);
                        }
                    }
                } else {
                    $startMonth = Carbon::now()->copy()->startOfMonth();
                    $today = Carbon::now();
                    $interval = DateInterval::createFromDateString(('1 day'));
                    $period = new DatePeriod($startMonth, $interval, $today);
                    foreach ($period as $dt) {
                        array_push($dates, $dt->format('Y-m-d'));
                    }
                    if (!in_array($trans->dates, $dates)) {
                        unset($transactions[$key]);
                    }
                }
            }
        }
        $datas = $transactions->groupBy('method_id');
        $total_gmv = $transactions->sum('total_amount');
        $pieDatas = [];
        foreach ($datas as $key => $value) {

            $piedata = [
                        'name' => Method::find($key)->method,
                        'y' => round((int)$value->sum('total_amount') * 100 /$total_gmv,2),
                        ];
            array_push($pieDatas, $piedata);
        }
        return $pieDatas;
    }

    public function transStatusOfBrand(Request $request)
    {
        $dates = [];
        $transactions = ReportTransaction::all();
        foreach ($transactions as $key => $trans) {
            if (isset($request->merchantId) && $request->merchantId != null) {
                if ($trans->merchant_id != $request->merchantId){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->payMethod) && $request->payMethod != null) {
                if ($trans->method_id != $request->payMethod){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->gateWay) && $request->gateWay != null) {
                if ($trans->gateway_id != $request->gateWay){
                    unset($transactions[$key]);
                }
            }
            if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
                if ($request->dateStart != now()->format('Y-m-d')) {
                    if ($request->dateStart == $request->dateEnd) {
                        if ($trans->dates != $request->dateStart) {
                            unset($transactions[$key]);
                        }
                    } else {
                        $period = new DatePeriod(
                            new DateTime($request->dateStart),
                            new DateInterval('P1D'),
                            new DateTime($request->dateEnd)
                        );
                        foreach ($period as $dt) {
                            array_push($dates, $dt->format('Y-m-d'));
                        }
                        if (!in_array($trans->dates, $dates)) {
                            unset($transactions[$key]);
                        }
                    }
                } else {
                    $startMonth = Carbon::now()->copy()->startOfMonth();
                    $today = Carbon::now();
                    $interval = DateInterval::createFromDateString(('1 day'));
                    $period = new DatePeriod($startMonth, $interval, $today);
                    foreach ($period as $dt) {
                        array_push($dates, $dt->format('Y-m-d'));
                    }
                    if (!in_array($trans->dates, $dates)) {
                        unset($transactions[$key]);
                    }
                }
            }
        }
        $datas = $transactions->groupBy('gateway_id');
        $success_rate = [];
        $cancel_rate = [];
        $process_rate = [];
        $other_rate = [];
        $brand_categories = [];
        foreach ($datas as $key => $value) {
            $total_trans_amount = $value->sum('total_amount');
            $success_trans_amount = $value->where('trans_status', 5)->sum('total_amount');
            $cancel_trans_amount = $value->where('trans_status', 3)->sum('total_amount');
            $processing_trans_amount = $value->where('trans_status', 2)->sum('total_amount');
            if ($total_trans_amount != 0) {
                $gateway_name = DB::table('gateways')->select('gateway')->where('id', $key)->first();
                $percent_success = round($success_trans_amount * 100 / $total_trans_amount, 2);
                $percent_cancel =  round($cancel_trans_amount * 100 / $total_trans_amount, 2);
                $percent_processing = round($processing_trans_amount * 100 / $total_trans_amount, 2);
                $percent_other = round(100 - ($percent_cancel + $percent_processing + $percent_success), 2);
                array_push($success_rate, $percent_success);
                array_push($cancel_rate, $percent_cancel);
                array_push($process_rate, $percent_processing);
                array_push($other_rate, $percent_other);
                array_push($brand_categories, $gateway_name->gateway);
            }
        }
        return [
                'categories' => $brand_categories,
                'success' => $success_rate,
                'cancel' => $cancel_rate,
                'process' => $process_rate,
                'other' => $other_rate,
        ];
    }

    public function issueBank(Request $request) {
        $dates = [];
        $transactions = $this->successTrans;
        foreach ($transactions as $key => $trans) {
            if (isset($request->merchantId) && $request->merchantId != null) {
                if ($trans->merchant_id != $request->merchantId){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->payMethod) && $request->payMethod != null) {
                if ($trans->method_id != $request->payMethod){
                    unset($transactions[$key]);
                }
            }
            if (isset($request->gateWay) && $request->gateWay != null) {
                if ($trans->gateway_id != $request->gateWay){
                    unset($transactions[$key]);
                }
            }
            if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
                if ($request->dateStart != now()->format('Y-m-d')) {
                    if ($request->dateStart == $request->dateEnd) {
                        if ($trans->dates != $request->dateStart) {
                            unset($transactions[$key]);
                        }
                    } else {
                        $period = new DatePeriod(
                            new DateTime($request->dateStart),
                            new DateInterval('P1D'),
                            new DateTime($request->dateEnd)
                        );
                        foreach ($period as $dt) {
                            array_push($dates, $dt->format('Y-m-d'));
                        }
                        if (!in_array($trans->dates, $dates)) {
                            unset($transactions[$key]);
                        }
                    }
                } else {
                    $startMonth = Carbon::now()->copy()->startOfMonth();
                    $today = Carbon::now();
                    $interval = DateInterval::createFromDateString(('1 day'));
                    $period = new DatePeriod($startMonth, $interval, $today);
                    foreach ($period as $dt) {
                        array_push($dates, $dt->format('Y-m-d'));
                    }
                    if (!in_array($trans->dates, $dates)) {
                        unset($transactions[$key]);
                    }
                }
            }
        }
        $datas = $transactions->groupBy('gateway_id');
        $data = [];
        foreach ($datas as $key => $value) {
            $issueBank = [
                'name' => DB::table('gateways')->select('gateway')->where('id', $key)->first()->gateway,
                'value' => $value->sum('total_amount'),
            ];
            array_push($data, $issueBank);
        }
        usort($data, function($item1, $item2)
        {
            return $item1['value'] < $item2['value'];
        });

        $i = 10;
        $data = array_slice($data, 0, 10);
        foreach($data as $index => $item) {
            $data[$index]['colorValue'] = $i--;
        }
        return $data;
    }

    public function errorDetail(Request $request) {
        $conditions = [
            ['trans_status', '<>', 5]
        ];
        if(isset($request->merchantId) && $request->merchantId != 'null') {
            $conditions[] = ['merchant_id', $request->merchantId];
        }

        if(isset($request->payMethod) && $request->payMethod != 'null') {
            $conditions[] = ['method_id', $request->payMethod];
        }

        if(isset($request->gateWay) && $request->gateWay != 'null') {
            $conditions[] = ['gateway_id', $request->gateWay];
        }

        $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where($conditions)
                        ->groupBy('trans_status')->get();

        if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
            if ($request->dateStart == $request->dateEnd) {
                $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where($conditions)
                        ->where('dates', $request->dateStart)
                        ->groupBy('trans_status')->get();
            } else {
                $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                ->where($conditions)
                ->whereBetween('dates', [$request->dateStart, $request->dateEnd])
                ->groupBy('trans_status')->get();
            }
        }
        $data = [];
        foreach($status as $item) {
            $data[] = [
                'total' => $item->total,
                'trans_status' => $item->trans_status,
            ];
        }
        rsort($data);
        return $data;
    }
    public function rateTransaction(Request $request) {
        $formDate = Carbon::now()->copy()->startOfMonth()->toDateString();
        $toDate = Carbon::now()->toDateString();
        $data = [];

        $conditions = [];

        if(isset(request()->merchantId) && request()->merchantId != 'null') {
            $conditions[] = ['merchant_id', request()->merchantId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['method_id', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        if(isset(request()->date) && request()->date != 'null') {
            $conditions[] = ['dates',request()->date];
        }
        if(isset($request->dateStart) && $request->dateStart != 'null' && isset($request->dateEnd) && $request->dateEnd != 'null') {
            if ($request->dateStart != now()->format('Y-m-d')) {
                $totalTransactions =  DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                        ->whereBetween('created_at', [$request->dateStart, $request->dateEnd])
                                        ->where($conditions)
                                        ->groupBy('date')->get();
                $errorTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                        ->whereBetween('created_at', [$request->dateStart, $request->dateEnd])
                                        ->where('trans_status', '<>', 5)
                                        ->where($conditions)
                                        ->groupBy('date')->get();
                $successTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                        ->whereBetween('created_at', [$request->dateStart, $request->dateEnd])
                                        ->where('trans_status', 5)
                                        ->where($conditions)
                                        ->groupBy('date')->get();
            } else {
                $totalTransactions =  DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$formDate, $toDate])
                ->where($conditions)
                ->groupBy('date')->get();
                $errorTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$formDate, $toDate])
                ->where('trans_status', '<>', 5)
                ->where($conditions)
                ->groupBy('date')->get();
                $successTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$formDate, $toDate])
                ->where('trans_status', 5)
                ->where($conditions)
                ->groupBy('date')->get();
            }
        }

        foreach($totalTransactions as $item) {
            $data['total']['data'][] = $item->total;
            $data['total']['date'][] = $item->date;
        }
        $data['total']['name'] = 'Total transaction';

        foreach($errorTransactions as $item) {
            $data['error']['data'][] = $item->total;
            $data['error']['date'][] = $item->date;
        }
        $data['error']['name'] = 'Error transaction';
        foreach($successTransactions as $item) {
            $data['success']['data'][] = $item->total;
            $data['success']['date'][] = $item->date;
        }
        $data['success']['name'] = 'Success transaction';
        return $data;
    }
}
