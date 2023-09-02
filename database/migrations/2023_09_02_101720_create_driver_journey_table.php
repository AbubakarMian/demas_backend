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
        Schema::create('driver_journey', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_driver_id')->nullable()->default(0);
            $table->bigInteger('journey_id')->nullable()->default(0);
            $table->string('is_default')->nullable()->default(null);
            $table->string('journey_slot_id')->nullable()->default(null);
            $table->string('rate')->nullable()->default(null);
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
        Schema::dropIfExists('driver_journey');
    }
};
