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
            $table->dropColumn('cash_collected_by');
            $table->dropColumn('payment_collected_user_id');
            $table->dropColumn('type');
            $table->dropColumn('payment_collected_price');
            $table->dropColumn('user_driver_id');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->string('cash_collected_by')->nullable()->default('admin')->comment('admin/driver');
            $table->tinyInteger('is_paid')->nullable()->default(0);
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
