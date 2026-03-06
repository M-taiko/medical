<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Visit;
use App\Models\DentalChartRecord;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Subscription Plans
        $monthly = SubscriptionPlan::create([
            'name'             => 'Monthly',
            'slug'             => 'monthly',
            'duration_months'  => 1,
            'price'            => 99.00,
            'is_active'        => true,
        ]);
        $semiAnnual = SubscriptionPlan::create([
            'name'             => 'Semi-Annual',
            'slug'             => 'semi_annual',
            'duration_months'  => 6,
            'price'            => 499.00,
            'is_active'        => true,
        ]);
        SubscriptionPlan::create([
            'name'             => 'Yearly',
            'slug'             => 'yearly',
            'duration_months'  => 12,
            'price'            => 899.00,
            'is_active'        => true,
        ]);

        // 2. Super Admin (no clinic_id)
        $superAdmin = User::create([
            'name'      => 'Super Admin',
            'email'     => 'admin@masardental.com',
            'password'  => Hash::make('password'),
            'role'      => 'superadmin',
            'is_active' => true,
        ]);

        // 3. Create a Primary Clinic
        $clinic = Clinic::create([
            'name'                => 'Masar Dental - Main Branch',
            'address'             => '123 Health Street, Medical District',
            'phone'               => '+1234567890',
            'email'               => 'info@masardental.com',
            'settings'            => ['currency' => 'USD', 'timezone' => 'UTC'],
            'is_active'           => true,
            'subscription_status' => 'active',
        ]);

        // 4. Assign Monthly subscription to clinic
        $sub = \App\Models\ClinicSubscription::create([
            'clinic_id'               => $clinic->id,
            'plan_id'                 => $monthly->id,
            'subscription_start_date' => today(),
            'subscription_end_date'   => today()->addMonth(),
            'renewal_date'            => today()->addMonth(),
            'subscription_status'     => 'active',
            'price_paid'              => 99.00,
            'created_by'              => $superAdmin->id,
        ]);

        // Record platform income
        $income = \App\Models\PlatformIncome::create([
            'clinic_id'       => $clinic->id,
            'subscription_id' => $sub->id,
            'amount'          => 99.00,
            'received_date'   => today(),
            'description'     => 'Initial subscription - Monthly plan',
        ]);
        \App\Models\PlatformTransaction::create([
            'type'             => 'income',
            'reference_id'     => $income->id,
            'reference_type'   => \App\Models\PlatformIncome::class,
            'amount'           => 99.00,
            'transaction_date' => today(),
            'description'      => $income->description,
        ]);

        // 5. Create Clinic Users
        $clinicAdmin = User::create([
            'name'      => 'Clinic Admin',
            'email'     => 'clinicadmin@masardental.com',
            'password'  => Hash::make('password'),
            'role'      => 'clinic_admin',
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);

        $doctor = User::create([
            'name'      => 'Dr. Ahmed Hassan',
            'email'     => 'doctor@masardental.com',
            'password'  => Hash::make('password'),
            'role'      => 'doctor',
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Sara Reception',
            'email'     => 'reception@masardental.com',
            'password'  => Hash::make('password'),
            'role'      => 'receptionist',
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);

        // 6. Auth as doctor to satisfy BelongsToClinic trait during seeding
        auth()->login($doctor);

        // 4. Create dummy patient
        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'first_name' => 'Omar',
            'last_name' => 'Patient',
            'national_id' => '123456789',
            'date_of_birth' => '1990-05-15',
            'gender' => 'Male',
            'phone' => '0551234567',
            'whatsapp_number' => '0551234567',
            'blood_type' => 'O+',
            'chronic_diseases' => ['Diabetes'],
            'allergies' => ['Penicillin'],
            'emergency_contact' => ['name' => 'Ali', 'phone' => '0559876543'],
        ]);

        // 5. Create a dummy visit
        $visit = Visit::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_time' => now()->addHours(2),
            'status' => 'scheduled',
            'chief_complaint' => 'Pain in upper right molar',
        ]);

        // 6. Create some dummy treatments for the dashboard metrics
        DentalChartRecord::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_id' => $visit->id,
            'tooth_number' => '16',
            'condition' => 'treated',
            'treatment' => 'Root Canal',
            'price' => 250.00
        ]);

        // 7. Dummy Invoice for revenue metrics
        Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'visit_id' => $visit->id,
            'total_amount' => 250.00,
            'paid_amount' => 250.00,
            'remaining_balance' => 0,
            'status' => 'paid',
        ]);
        
        auth()->logout();

        $this->command->info('');
        $this->command->info('=== Seeded Successfully ===');
        $this->command->info('Super Admin:   admin@masardental.com / password');
        $this->command->info('Clinic Admin:  clinicadmin@masardental.com / password');
        $this->command->info('Doctor:        doctor@masardental.com / password');
        $this->command->info('Receptionist:  reception@masardental.com / password');
        $this->command->info('===========================');
    }
}
