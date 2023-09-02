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
        Schema::table('transport', function (Blueprint $table) {
            $table->text('features')->nullable()->default(null);
            $table->text('booking')->nullable()->default(null);
            $table->text('dontforget')->nullable()->default(null);        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport', function (Blueprint $table) {
            //
        });
    }
};
