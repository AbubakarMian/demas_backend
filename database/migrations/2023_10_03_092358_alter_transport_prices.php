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
        Schema::table('transport_prices', function (Blueprint $table) {
            $table->dropColumn('journey_slot_id');
        });
        Schema::table('transport_prices', function (Blueprint $table) {
            $table->bigInteger('journey_id')->nullable()->default(0);
            $table->bigInteger('slot_id')->nullable()->default(0);
            $table->tinyInteger('is_default')->nullable()->default(0);
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
