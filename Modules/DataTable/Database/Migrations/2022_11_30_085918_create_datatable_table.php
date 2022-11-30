<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTableTable extends Migration
{
    public function up()
    {
        Schema::create('data_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'in-active'])->default('active');
            $table->string('address');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_tables');
    }
}
