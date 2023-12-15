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
        Schema::create('sales_agent_trip_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_sale_agent_id')->nullable()->default(0);
            $table->bigInteger('journey_id')->nullable()->default(0);
            $table->bigInteger('slot_id')->nullable()->default(0);
            $table->bigInteger('transport_type_id')->nullable()->default(0);
            $table->double('commission' ,20,2)->nullable()->default(0);
            $table->double('price' ,20,2)->nullable()->default(0);
            $table->tinyInteger('is_default')->nullable()->default(0);
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
        Schema::dropIfExists('table_sales_agent_trip_price');
    }
};
