<?php

namespace App\Http\Controllers;

use App\Models\ReportTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $successTrans;
    public function __construct()
    {
        $this->successTrans =  ReportTransaction::where('trans_status', 5)->orderBy('dates', 'ASC')->get();
    }
}
