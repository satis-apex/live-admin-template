<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('default_menu')->nullable();
            $table->string('link')->nullable();
            $table->string('permission_key');
            $table->string('module')->nullable();
            $table->string('icon')->nullable();
            $table->string('type');
            $table->string('parent_id')->nullable();
            $table->string('access')->default('Su-Admin');
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
        Schema::dropIfExists('menu_links');
    }
};
