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
        Schema::create('panel_portofolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id');
            $table->foreignId('employee_id')->nullable();
            $table->foreignId('service_id')->nullable();
            $table->string('caption');
            $table->string('image');
            $table->foreignId('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_portofolios');
    }
};
