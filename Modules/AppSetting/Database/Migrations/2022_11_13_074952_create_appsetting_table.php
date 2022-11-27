<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppSettingTable extends Migration
{
    public function up()
    {
        Schema::create('application_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('year');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('primary_light_color')->nullable();
            $table->string('primary_dark_color')->nullable();
            $table->string('complementary_color')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_infos');
    }
}
