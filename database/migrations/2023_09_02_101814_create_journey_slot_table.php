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
        Schema::create('journey_slot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('journey_id')->nullable()->default(0);
            $table->bigInteger('from_date');
            $table->bigInteger('to_date');
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
        Schema::dropIfExists('journey_slot');
    }
};
