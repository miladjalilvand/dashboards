<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_portofolios', function (Blueprint $table) {
            $table->string('image', 500)->change();
        });
    }

    public function down(): void
    {
        Schema::table('panel_portofolios', function (Blueprint $table) {
            $table->string('image')->change();
        });
    }
};
