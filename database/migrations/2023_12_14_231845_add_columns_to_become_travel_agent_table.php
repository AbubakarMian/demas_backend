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
        Schema::table('become_travel_agent', function (Blueprint $table) {
            $table->string('status', 100)->nullable()->default('pending');
              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('become_travel_agent', function (Blueprint $table) {
            //
        });
    }
};
