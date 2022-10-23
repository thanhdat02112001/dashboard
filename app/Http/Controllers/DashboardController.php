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

        $methods = Method::all()->toArray();
        $methodData = [];
        foreach ($methods as $method) {
            array_push($methodData, $method['method']);
        }

        $gateways = DB::table('gateways')->select('gateway')->get()->toArray();
        $gatewaysData = [];
        foreach ($gateways as $gateway) {
            array_push($gatewaysData, $gateway->gateway);
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
        // dd($cardDatas);

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
        ->where('trans_status', 5)->where('channel', 2)->sum('total_amount');
        $gmv_ecom = ReportTransaction::whereBetween('created_at', [$startTime, $endTime])
        ->where('trans_status', 5)->where('channel', 1)->sum('total_amount');
        $data = [
            'gmv_okr' => count($total_transactions),
            'percent_gmv' => count($transactions) * 100 / count($prev_transactions),
            'total_gmv' => $total_gmv / 1000000,
            'percent_total_gmv' => $total_gmv * 100/  $prev_total_gmv,
            'avg_gmv' => ($total_gmv / count($transactions)) / 1000000,
            'percent_avg_gmv' => ($total_gmv / count($transactions) )* 100 / ($prev_total_gmv / count($prev_transactions)),
            'gmv_invoice' => $gmv_invoice / 1000000,
            'gmv_ecom' => $gmv_ecom / 1000000,
        ];

        return view('merchant', compact('merchantData', 'methodData', 'gatewaysData', 'data', 'cardDatas'));
    }

    public function gmvGrowth()
    {
        $conditions = [];
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        $startMonth = Carbon::now()->copy()->startOfMonth();
        $today = Carbon::now();
        $interval = DateInterval::createFromDateString(('1 day'));
        $period = new DatePeriod($startMonth, $interval, $today);
        $dates = [];
        $columns = [];
        $line = [];
        foreach ($period as $dt) {
            array_push($dates, $dt->format('Y-m-d'));
        }

        foreach ($dates as $date) {
            $transPerDay = ReportTransaction::where('dates', $date)
                                ->where($conditions)->where('trans_status', 5)->get();
            $amountPerDay = ReportTransaction::where('dates', $date)
                                ->where($conditions)->where('trans_status', 5)->sum('total_amount');

            array_push($columns, (int)$amountPerDay /1000000);
            array_push($line, count($transPerDay));
        }
        return response()->json(['columns' => $columns, 'line' => $line, 'categories' => $dates]);
    }

    public function gmvProportion()
    {
        $conditions = [];
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        $pieDatas = [];
        $total_gmv = ReportTransaction::where('dates', Carbon::now()->toDateString())
                                        ->where($conditions)->where('trans_status', 5)->sum('total_amount');
        $methods = Method::all();
        foreach ($methods as $method) {
            $method_gmv = ReportTransaction::where('dates', Carbon::now()->toDateString())
            ->where('trans_status', 5)
            ->where($conditions)->where('method_id', $method->id)->sum('total_amount');
            $piedata = [
                'name' => $method->method,
                'y' => round($method_gmv * 100 /$total_gmv,2),
            ];
            array_push($pieDatas, $piedata);
        }
        return $pieDatas;
    }

    public function transStatusOfBrand()
    {
        $conditions = [];
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        $today =Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $brands = DB::table('reports_transaction')->select("bank_code")
                    ->where($conditions)->distinct()->get();
        $success_rate = [];
        $cancel_rate = [];
        $process_rate = [];
        $other_rate = [];
        $brand_categories = [];
        foreach ($brands as $brand) {
            if ($brand->bank_code != "") {
                array_push($brand_categories, $brand->bank_code);

                $total_trans_amount = ReportTransaction::where('dates', $today)
                ->where($conditions)->where('bank_code', $brand->bank_code)->sum('total_amount');
                $success_trans_amount = ReportTransaction::where('dates', $today)
                ->where($conditions)->where('trans_status', 5)->where('bank_code', $brand->bank_code)->sum('total_amount');
                $cancel_trans_amount = ReportTransaction::where('dates', $today)
                ->where($conditions)->where('trans_status', 3)->where('bank_code', $brand->bank_code)->sum('total_amount');
                $processing_trans_amount = ReportTransaction::where('dates', $today)
                ->where($conditions)->where('trans_status', 2)->where('bank_code', $brand->bank_code)->sum('total_amount');
                if ($total_trans_amount != 0) {
                    $percent_success = round($success_trans_amount * 100 / $total_trans_amount, 2);
                    $percent_cancel =  round($cancel_trans_amount * 100 / $total_trans_amount, 2);
                    $percent_processing = round($processing_trans_amount * 100 / $total_trans_amount, 2);
                    $percent_other = round(100 - ($percent_cancel + $percent_processing + $percent_success), 2);
                    array_push($success_rate, $percent_success);
                    array_push($cancel_rate, $percent_cancel);
                    array_push($process_rate, $percent_processing);
                    array_push($other_rate, $percent_other);
                }
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

    public function issueBank() {
        $conditions = [];
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        // $brands = DB::table('reports_transaction')->select("bank_code", "created_at")
        //             ->where($conditions)
        //             ->distinct()->get();
        $brands = DB::table('banks')->select('bank_code')->get()->toArray();
        // dd($brands);
        if(isset(request()->dateStart) && request()->dateStart != 'null' && isset(request()->dateEnd) && request()->dateEnd != 'null') {
            $brands = DB::table('reports_transaction')->select("bank_code", "created_at")
                    ->where($conditions)
                    ->whereBetween('dates', [request()->dateStart, request()->dateEnd])
                    ->distinct()->get();
        }
        $data = [];
        foreach ($brands as $index => $brand) {
            if ($brand->bank_code != "") {
                $data[$index]['name'] = $brand->bank_code;
                if(isset(request()->merchanId) && request()->merchanId != 'null') {
                    $total_trans_amount = ReportTransaction::where('bank_code', $brand->bank_code)
                                            ->where($conditions)->sum('total_amount');
                }
                else {
                    $total_trans_amount = ReportTransaction::where('bank_code', $brand->bank_code)->where('dates', Carbon::now()->toDateString())->sum('total_amount');
                }
                $data[$index]['value'] = (int)$total_trans_amount;
            }
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

    public function errorDetail() {
        $conditions = [
            ['trans_status', '<>', 5]
        ];
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where($conditions)
                        ->groupBy('trans_status')->get();

        if(isset(request()->dateStart) && request()->dateStart != 'null' && isset(request()->dateEnd) && request()->dateEnd != 'null') {
            $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where($conditions)
                        ->whereBetween('dates', [request()->dateStart, request()->dateEnd])
                        ->groupBy('trans_status')->get();
        }
        $data = [];
        foreach($status as $item) {
            $data[] = (int)$item->total;
        }
        return $data;
    }
    public function rateTransaction() {
        $formDate = date('Y-m-d', strtotime('-7 days'));
        $toDate = date('Y-m-d', strtotime('1 days'));
        $data = [];

        $conditions = [];

        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $conditions[] = ['merchant_id', request()->merchanId];
        }

        if(isset(request()->payMethod) && request()->payMethod != 'null') {
            $conditions[] = ['payment_type', request()->payMethod];
        }

        if(isset(request()->gateWay) && request()->gateWay != 'null') {
            $conditions[] = ['gateway_id', request()->gateWay];
        }

        if(isset(request()->date) && request()->date != 'null') {
            $conditions[] = ['dates',request()->date];
        }

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
