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
        Schema::create('transport_commision_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transport_prices_id')->nullable()->default(0);
            $table->bigInteger('sale_agent_user_id')->nullable()->default(0);
            $table->bigInteger('travel_agent_user_id')->nullable()->default(0);
            $table->bigInteger('driver_user_id')->nullable()->default(0);
            $table->string('sale_agent_commision')->nullable()->default(null);
            $table->string('travel_agent_commision')->nullable()->default(null);
            $table->string('driver_user_commision')->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('transport_commision_agents');
    }
};
