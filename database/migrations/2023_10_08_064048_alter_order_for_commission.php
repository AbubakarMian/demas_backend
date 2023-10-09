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
            $table->double('driver_commission_total', 20, 2)->nullable()->default(0);
            $table->bigInteger('sale_agent_user_id')->nullable()->default(0);
            $table->string('sale_agent_commission_type', 100)->nullable()->default(null);
            $table->double('sale_agent_commission_total', 20, 2)->nullable()->default(0);
            $table->bigInteger('travel_agent_user_id')->nullable()->default(0);
            $table->string('travel_agent_commission_type', 100)->nullable()->default('fix_amount');
            $table->double('travel_agent_commission_total', 20, 2)->nullable()->default(0);
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
