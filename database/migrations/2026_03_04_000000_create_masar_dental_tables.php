<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Clinics
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add clinic_id to users (which usually already exists, so we modify it or assume fresh)
        // For fresh setup:
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('receptionist'); // superadmin, doctor, receptionist
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Roles & Permissions (Simplified RBAC)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->foreignId('clinic_id')->nullable()->constrained();
            $table->timestamps();
        });

        // 3. Patients
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('national_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('address')->nullable();
            $table->json('chronic_diseases')->nullable();
            $table->json('allergies')->nullable();
            $table->string('blood_type')->nullable();
            $table->json('emergency_contact')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Visits
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users');
            $table->dateTime('appointment_time');
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->text('chief_complaint')->nullable();
            $table->text('diagnosis')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Dental Chart Records
        Schema::create('dental_chart_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('visit_id')->nullable()->constrained();
            $table->foreignId('doctor_id')->constrained('users');
            $table->string('tooth_number')->index(); // e.g., '11', '12', '51' (FDI)
            $table->string('condition'); // healthy, decay, filled, etc.
            $table->text('findings')->nullable();
            $table->string('treatment')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Invoices & Payments (Financial)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('visit_id')->nullable()->constrained();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining_balance', 12, 2)->default(0); // calculated
            $table->string('status')->default('unpaid'); // unpaid, partial, paid
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // cash, card, transfer
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('user_id')->constrained(); // who recorded it
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->string('receipt_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. Inventory
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('unit'); // box, piece, bottle
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('type'); // in, out, deduction
            $table->integer('quantity');
            $table->string('reference')->nullable(); // linked to visit or purchase order
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop in reverse
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('dental_chart_records');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('clinics');
    }
};
