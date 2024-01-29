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
        Schema::create('car_conditions', function (Blueprint $table) {
            $table->id();
            $table->integer('language_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('serial_number')->nullable(1);
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
        Schema::dropIfExists('car_conditions');
    }
};
