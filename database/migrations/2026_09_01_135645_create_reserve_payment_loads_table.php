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
        Schema::create('reserve_payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('reserve_id')
                ->constrained('reserves')
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'reserve_id',
                'payment_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserve_payment_loads');
    }
};
