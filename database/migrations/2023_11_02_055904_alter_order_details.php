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
        Schema::table('order_detail', function (Blueprint $table) {
            $table->string('cash_collected_by',300)->nullable()->default(null)->comment('admin/sale_agent/travel_agent/driver');
            $table->bigInteger('cash_collected_by_user_id')->nullable()->default(0);
            $table->string('payment_type',300)->nullable()->default(null)->comment('paid/cod/later');// online/advance
            $table->float('collection_amount_from_user',10,2)->nullable()->default(0);
            $table->tinyInteger('is_paid')->nullable()->default(0);
            $table->string('sub_order_id',150)->nullable()->default('');
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
