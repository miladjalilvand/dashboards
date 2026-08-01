<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_menus', function (Blueprint $table) {
            $table->id();
            $table->string('caption');
            $table->string('icon');
            $table->string('slug');
            $table->integer('visible')->default(0);
            $table->foreignId('menu_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_menus');
    }
};
