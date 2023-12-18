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
        // Schema::dropColumns('order','payment_status');
        Schema::dropColumns('order_detail','payment_status');

        // Schema::table('order', function (Blueprint $table) {            
        //     $table->string('user_payment_status')->nullable()->default(null)->comment('pending/paid/refund'); // pending/paid/refund
        //     $table->string('admin_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null'); // pending/paid/refund/null
        //     $table->string('sale_agent_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
        //     $table->string('travel_agent_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
        //     $table->string('driver_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
        // });
        Schema::table('order_detail', function (Blueprint $table) {
            $table->double('payable_to_admin' ,20,2)->nullable()->default(0);
            $table->string('user_payment_status')->nullable()->default(null)->comment('pending/paid/refund'); // pending/paid/refund
            $table->string('admin_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null'); // pending/paid/refund/null
            $table->string('sale_agent_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
            $table->string('travel_agent_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
            $table->string('driver_payment_status')->nullable()->default(null)->comment('pending/paid/refund/null');
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
