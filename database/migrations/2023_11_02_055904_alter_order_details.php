<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('cash_collected_by',300)->nullable()->default(null)->comment('admin/sale_agent/driver');
            $table->string('payment_collected_type',300)->nullable()->default(null)->comment('paid/cod/later');
            $table->bigInteger('cash_collected_by_user_id')->nullable()->default(0);
            $table->string('collection_amount',300)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
