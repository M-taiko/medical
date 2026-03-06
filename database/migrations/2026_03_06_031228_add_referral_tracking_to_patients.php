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
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('referral_source', ['patient', 'doctor', 'social_media', 'google', 'walk_in', 'other'])
                  ->default('walk_in')
                  ->after('notes');
            $table->foreignId('referred_by_patient_id')
                  ->nullable()
                  ->constrained('patients')
                  ->onDelete('set null')
                  ->after('referral_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['referral_source', 'referred_by_patient_id']);
        });
    }
};
