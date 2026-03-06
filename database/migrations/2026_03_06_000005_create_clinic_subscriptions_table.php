<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinic_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans');
            $table->date('subscription_start_date');
            $table->date('subscription_end_date');
            $table->date('renewal_date');
            $table->string('subscription_status')->default('active'); // active, expired, suspended, trial
            $table->decimal('price_paid', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users'); // superadmin who assigned
            $table->timestamps();

            $table->index(['clinic_id', 'subscription_status']);
            $table->index('subscription_end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_subscriptions');
    }
};
