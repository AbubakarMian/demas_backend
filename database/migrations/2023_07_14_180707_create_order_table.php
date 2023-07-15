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
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->default(0);
            $table->bigInteger('payment_id')->nullable()->default(0);
            $table->bigInteger('user_sale_agent_id')->nullable()->default(0);
            $table->bigInteger('user_travel_agent_id')->nullable()->default(0);
            $table->bigInteger('user_driver_id')->nullable()->default(0);
            $table->bigInteger('cash_collected_by')->nullable()->default(null);
            $table->bigInteger('cash_collected_by_user_id')->nullable()->default(null);
            $table->string('price')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
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
        Schema::dropIfExists('order');
    }
};
