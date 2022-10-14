<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('dates');
            $table->integer('merchant_id')->nullable()->comment('id dai ly');
            $table->integer('method_id')->nullable()->comment('id phuong thuc thanh toan');
            $table->string('method')->nullable()->comment('phuong thuc thanh toan');
            $table->string('payment_type', 30)->nullable()->comment('phuong thuc thanh toan');
            $table->integer('gateway_id')->nullable()->comment('id nguon tien');
            $table->string('gateway')->nullable()->comment('nguon tien');
            $table->string('fund', 20)->nullable()->comment('nguon tien');
            $table->string('bank_code', 10)->nullable()->comment('ngan hang - thuong hieu the');
            $table->string('mid_code', 20)->nullable()->comment('mid code');
            $table->string('channel', 20)->nullable()->comment('kenh thanh toan ecom hay invoice');
            $table->tinyInteger('trans_type')->nullable()->comment('loai giao dich thanh toan - hoan tien');
            $table->tinyInteger('trans_status')->nullable()->comment('trang thai giao dich');
            $table->bigInteger('total_trans')->nullable()->comment('tong giao dich');
            $table->bigInteger('total_amount')->nullable()->comment('tong gia tri giao dich');
            $table->bigInteger('total_foreign_amount')->nullable();
            $table->double('rank_merchant_fee', 4, 2)->nullable()->comment('% tai thoi điem tinh phí thu vào cuối tháng');
            $table->bigInteger('total_merchant_fee_byrank')->nullable()->comment('tong phi thu tinh cho merchant theo rank thuc te');
            $table->bigInteger('total_merchant_proc_fee')->nullable()->comment('tong phi nhan tam tinh');
            $table->bigInteger('total_merchant_pay_fee')->nullable()->comment('tong phi tra tam tinh');
            $table->double('rank_bank_fee', 4, 2)->nullable()->comment('% tai thoi điem tinh phí trả vào cuối tháng');
            $table->bigInteger('total_bank_fee_byrank')->nullable()->comment('tong phi tra cho bank theo rank thuc te');
            $table->bigInteger('total_bank_proc_fee')->nullable()->comment('tổng phí trả xử lý');
            $table->bigInteger('total_bank_pay_fee')->nullable()->comment('tong phi tra thanh toan');
            $table->unique(['dates', 'merchant_id', 'method_id', 'gateway_id', 'bank_code', 'mid_code', 'channel', 'trans_type', 'trans_status'], 'idx_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports_transaction');
    }
}
