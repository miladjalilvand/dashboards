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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // مبلغ به تومان
            $table->unsignedBigInteger('amount');

            // زرین پال
            $table->string('authority')->nullable()->index();

            // کد نتیجه درخواست/تأیید
            $table->integer('status_code')->nullable();

            // وضعیت پرداخت
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
            ])->default('pending')->index();

            // شماره پیگیری زرین پال
            $table->string('ref_id')->nullable()->index();

            // توضیحات
            $table->text('description')->nullable();

            // اطلاعات پاسخ زرین پال برای لاگ
            $table->json('response')->nullable();

            // زمان پرداخت موفق
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
