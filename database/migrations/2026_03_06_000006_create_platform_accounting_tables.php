<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_income', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('subscription_id')->nullable()->constrained('clinic_subscriptions');
            $table->decimal('amount', 10, 2);
            $table->date('received_date');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('received_date');
        });

        Schema::create('platform_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // server, development, marketing, maintenance, other
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('expense_date');
        });

        Schema::create('platform_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // income, expense
            $table->morphs('reference'); // reference_id + reference_type
            $table->decimal('amount', 10, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_transactions');
        Schema::dropIfExists('platform_expenses');
        Schema::dropIfExists('platform_income');
    }
};
