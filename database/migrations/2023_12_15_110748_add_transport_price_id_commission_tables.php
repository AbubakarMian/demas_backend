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
        Schema::table('transport_journey_prices', function (Blueprint $table) {
            $table->drop();
        });
        Schema::table('sales_agent_trip_price', function (Blueprint $table) {
            $table->bigInteger('transport_price_id')->default(0);
        });
        Schema::table('travel_agent_commission', function (Blueprint $table) {
            $table->bigInteger('transport_price_id')->default(0);
        });
        Schema::table('driver_commission', function (Blueprint $table) {
            $table->bigInteger('transport_price_id')->default(0);
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
