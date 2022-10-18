<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Method;
use App\Models\ReportTransaction;
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

        $total_transactions = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')])
                                                ->get()->toArray();
        $transactions = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')])
                                        ->where('trans_status', 5)->get()->toArray();
        $prev_transactions =  ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay()->subDay(),  Carbon::now('Asia/Ho_Chi_Minh')->subDay()])
                                        ->where('trans_status', 5)->get()->toArray();
        $total_gmv = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')])
                                        ->where('trans_status', 5)->sum('total_amount');
        $prev_total_gmv  = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay()->subDay(),  Carbon::now('Asia/Ho_Chi_Minh')->subDay()])
                                        ->where('trans_status', 5)->sum('total_amount');
        $gmv_invoice = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')])
                                        ->where('trans_status', 5)->where('channel', 2)->sum('total_amount');
        $gmv_ecom = ReportTransaction::whereBetween('created_at', [Carbon::now()->copy()->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')])
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

        return view('merchant', compact('merchantData', 'methodData', 'gatewaysData', 'data'));
    }

    public function gmvGrowth()
    {
        $startMonth = Carbon::now()->copy()->startOfMonth();
        $today = Carbon::now();
        $interval = DateInterval::createFromDateString(('1 day'));
        $period = new DatePeriod($startMonth, $interval, $today);
        $dates = [];
        $columns = [];
        $line = [];
        foreach ($period as $dt) {
            array_push($dates, $dt->toDateString());
        }

        foreach ($dates as $date) {
            $transPerDay = ReportTransaction::where('dates', $date)->where('trans_status', 5)->get();
            $amountPerDay = ReportTransaction::where('dates', $date)->where('trans_status', 5)->sum('total_amount');

            array_push($columns, (int)$amountPerDay /1000000);
            array_push($line, count($transPerDay));
        }
        return response()->json(['columns' => $columns, 'line' => $line, 'categories' => $dates]);
    }

    public function gmvProportion()
    {
        $pieDatas = [];
        $total_gmv = ReportTransaction::where('dates', Carbon::now('Asia/Ho_Chi_Minh')->toDateString())
                                        ->where('trans_status', 5)->sum('total_amount');
        $methods = Method::all();
        foreach ($methods as $method) {
            $method_gmv = ReportTransaction::where('dates', Carbon::now('Asia/Ho_Chi_Minh')->toDateString())
            ->where('trans_status', 5)->where('method_id', $method->id)->sum('total_amount');
            $piedata = [
                'name' => $method->method,
                'y' => $method_gmv * 100 /$total_gmv,
                'drilldown' => $method->method,
            ];
            array_push($pieDatas, $piedata);
        }
        return $pieDatas;
    }

    public function transStatusOfBrand()
    {
        $today =Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $brands = DB::table('reports_transaction')->select("bank_code")->distinct()->get();
        $datas = [];
        $brand_categories = [];
        foreach ($brands as $brand) {
            if ($brand->bank_code != "") {
                array_push($brand_categories, $brand->bank_code);
                $total_trans_amount = ReportTransaction::where('dates', $today)
                ->where('bank_code', $brand->bank_code)->sum('total_amount');
                $success_trans_amount = ReportTransaction::where('dates', $today)
                ->where('trans_status', 5)->where('bank_code', $brand->bank_code)->sum('total_amount');
                $cancel_trans_amount = ReportTransaction::where('dates', $today)
                ->where('trans_status', 5)->where('bank_code', $brand->bank_code)->sum('total_amount');
                $fail_trans_amount = ReportTransaction::where('dates', $today)
                ->where('trans_status', 5)->where('bank_code', $brand->bank_code)->sum('total_amount');
                if ($total_trans_amount != 0) {
                    $percent_success = $success_trans_amount * 100 / $total_trans_amount;
                    $percent_cancel = $cancel_trans_amount * 100 / $total_trans_amount;
                    $percent_fail = $fail_trans_amount * 100 / $total_trans_amount;
                    $data = [
                        'name' => $brand->bank_code,
                        'data' => [$percent_success, $percent_cancel, $percent_fail, 100 - ($percent_cancel + $percent_success +$percent_fail)]
                    ];
                    array_push($datas, $data);
                }
            }
        }
        return ['categories' => $brand_categories, 'data' => $datas];
    }

    public function issueBank() {
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $brands = DB::table('reports_transaction')->select("bank_code")
                    ->where('merchant_id', request()->merchanId)
                    ->distinct()->get();
        }
        else {
            $brands = DB::table('reports_transaction')->select("bank_code")
                    ->distinct()->get();
        }
        $data = [];
        foreach ($brands as $index => $brand) {
            if ($brand->bank_code != "") {
                $data[$index]['name'] = $brand->bank_code; 
                if(isset(request()->merchanId) && request()->merchanId != 'null') {
                    $total_trans_amount = ReportTransaction::where('bank_code', $brand->bank_code)
                                            ->where('merchant_id', request()->merchanId)->sum('total_amount');
                }
                else {
                    $total_trans_amount = ReportTransaction::where('bank_code', $brand->bank_code)->sum('total_amount');
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
        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where('trans_status', '<>', 5)
                        ->where('merchant_id', request()->merchanId)
                        ->groupBy('trans_status')->get();
        }
        else {
            $status = DB::table('reports_transaction')->select("trans_status",  DB::raw('count(*) as total'))
                        ->where('trans_status', '<>', 5)
                        ->groupBy('trans_status')->get();
        }
        
        $data = [];
        foreach($status as $item) {
            $data[] = [0, (int)$item->total];
        }
        return $data;
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new DateInterval('P1D');
    
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
    
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }
    
        return $array;
    }
    
    public function rateTransaction() {
        $formDate = date('Y-m-d', strtotime('-7 days'));
        $toDate = date('Y-m-d', strtotime('1 days'));
        $data = [];

        if(isset(request()->merchanId) && request()->merchanId != 'null') {
            $totalTransactions =  DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->where('merchant_id', request()->merchanId)
                                ->groupBy('date')->get();
            $errorTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->where('trans_status', '<>', 5)
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->where('merchant_id', request()->merchanId)
                                ->groupBy('date')->get();
            $successTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->where('trans_status', 5)
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->where('merchant_id', request()->merchanId)
                                ->groupBy('date')->get();
        }
        else {
            $totalTransactions =  DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->groupBy('date')->get();
            $errorTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->where('trans_status', '<>', 5)
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->groupBy('date')->get();
            $successTransactions = DB::table('reports_transaction')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                ->where('trans_status', 5)
                                ->whereBetween('created_at', [$formDate, $toDate])
                                ->groupBy('date')->get();
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
