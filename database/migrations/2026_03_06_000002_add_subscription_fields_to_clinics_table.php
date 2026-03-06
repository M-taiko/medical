<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('settings');
            $table->string('subscription_status')->default('trial')->after('is_active');
            // trial, active, expired, suspended
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'subscription_status']);
        });
    }
};
