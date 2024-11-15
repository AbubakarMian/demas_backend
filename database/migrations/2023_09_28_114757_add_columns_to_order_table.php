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
        Schema::table('order', function (Blueprint $table) {
            $table->string('total_price')->nullable()->default(null);
            $table->string('status')->nullable()->default(null)->comment('pending /confirmed/inprogress/cancelled/rejected/completed');
            $table->string('payment_collected_type')->nullable()->default(null)->comment('admin/sale_agent/travel_agent/driver');
            $table->bigInteger('payment_collected_user_id')->nullable()->default(null);
            $table->string('payment_collected_price')->nullable()->default(null);
            $table->string('order_type')->nullable()->default(null)->comment('single /package');
            $table->string('payment_type')->nullable()->default(null)->comment('online/cash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            //
        });
    }
};
