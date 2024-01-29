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
        Schema::create('car_contents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('language_id')->nullable();
            $table->bigInteger('car_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('car_condition_id')->nullable();
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('car_model_id')->nullable();
            $table->bigInteger('body_type_id')->nullable();
            $table->bigInteger('fuel_type_id')->nullable();
            $table->bigInteger('transmission_type_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
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
        Schema::dropIfExists('car_contents');
    }
};
