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
        Schema::create('new_agent_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->default("");
            $table->string('email')->nullable()->default("");
            $table->string('phone')->nullable()->default("");
            $table->string('whatsapp')->nullable()->default("");
            $table->string('comments')->nullable()->default("");
            $table->string('password')->nullable()->default("");
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
        Schema::dropIfExists('new_agent_request');
    }
};
