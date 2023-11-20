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
        Schema::create('transport_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transport_type_id')->nullable()->default(0);
            $table->bigInteger('journey_slot_id')->nullable()->default(0);
            $table->string('price')->nullable()->default(null);
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
        Schema::dropIfExists('transport_prices');
    }
};
