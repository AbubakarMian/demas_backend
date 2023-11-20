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
            $table->string('driver_commission_type',100)->nullable()->default(null);
            $table->double('driver_commission' ,20,2)->nullable()->default(0);
            $table->bigInteger('sale_agent_user_id')->nullable()->default(0);
            $table->string('sale_agent_commission_type',100)->nullable()->default(null);
            $table->double('sale_agent_commission' ,20,2)->nullable()->default(0);
            $table->bigInteger('travel_agent_user_id')->nullable()->default(0);
            $table->string('travel_agent_commission_type',100)->nullable()->default('fix_amount');
            $table->double('travel_agent_commission' ,20,2)->nullable()->default(0);
            $table->string('status',100)->nullable()->default('pending')
            ->comment('pending,accept,reject,complete');//pending,accept,reject,complete

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
