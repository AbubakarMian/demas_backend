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
        Schema::create('sale_agent_travel_agent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_sale_agent_id')->nullable()->default(0);
            $table->bigInteger('user_travel_agent_id')->nullable()->default(0);
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
        Schema::dropIfExists('sale_agent_travel_agent');
    }
};
