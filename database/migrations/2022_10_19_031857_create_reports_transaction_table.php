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
            $table->bigInteger('merchant_id')->unsigned()->nullable()->comment('id dai ly');
            $table->bigInteger('method_id')->unsigned()->nullable()->comment('id phuong thuc thanh toan');
            $table->bigInteger('gateway_id')->nullable()->unsigned()->comment('id nguon tien');
            $table->string('bank_code')->nullable()->comment('ngan hang - thuong hieu the');
            $table->string('channel', 20)->nullable()->comment('kenh thanh toan ecom hay invoice');
            $table->bigInteger('trans_status')->nullable()->unsigned()->comment('trang thai giao dich');
            $table->bigInteger('total_amount')->nullable()->comment('tong gia tri giao dich');
            $table->bigInteger('card_id')->unsigned()->nullable();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('channel')->references('id')->on('channels');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('method_id')->references('id')->on('payment_methods');
            $table->foreign('trans_status')->references('id')->on('trans_status');
            $table->foreign('gateway_id')->references('id')->on('gateways');
            $table->foreign('bank_code')->references('bank_code')->on('banks');
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
