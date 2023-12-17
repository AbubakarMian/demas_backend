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
            $table->dropColumn(['cash_collected_by','payment_type','is_paid','payment_collected_type']);
            $table->string('cash_collected_by_role')->nullable()->default(null)->comment('admin/travel_agent/driver');
            $table->string('payment_status')->nullable()->default(null)->comment('pending/paid/refund');
        });
        Schema::table('order_detail', function (Blueprint $table) {
            $table->string('cash_collected_by_role')->nullable()->default(null)->comment('admin/travel_agent/driver');
            $table->string('payment_status')->nullable()->default(null)->comment('pending/paid/refund');
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
