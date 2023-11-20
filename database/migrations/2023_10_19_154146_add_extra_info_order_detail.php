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
        Schema::table('order_detail', function (Blueprint $table) {
            $table->bigInteger('transport_id')->nullable()->default(0);
            $table->bigInteger('transport_type_id')->nullable()->default(0);
            $table->string('pick_extrainfo', 300)->nullable()->default("");
            $table->string('dropoff_extrainfo', 300)->nullable()->default("");
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
