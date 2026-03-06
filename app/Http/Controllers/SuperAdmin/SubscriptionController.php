<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSubscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    public function index()
    {
        $subscriptions = ClinicSubscription::with(['clinic', 'plan'])
            ->latest()
            ->paginate(20);

        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    public function assign(Clinic $clinic)
    {
        $plans = SubscriptionPlan::active()->get();
        return view('superadmin.subscriptions.assign', compact('clinic', 'plans'));
    }

    public function store(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'plan_id'    => 'required|exists:subscription_plans,id',
            'price_paid' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:500',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        $this->subscriptionService->assignPlan(
            $clinic,
            $plan,
            $validated['price_paid'],
            $validated['notes'] ?? null
        );

        return redirect()->route('superadmin.clinics.show', $clinic)
                         ->with('success', "Subscription assigned to {$clinic->name} successfully.");
    }

    public function renew(Clinic $clinic)
    {
        $plans = SubscriptionPlan::active()->get();
        $current = $clinic->latestSubscription;
        return view('superadmin.subscriptions.renew', compact('clinic', 'plans', 'current'));
    }

    public function processRenewal(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'plan_id'    => 'required|exists:subscription_plans,id',
            'price_paid' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:500',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        $this->subscriptionService->renew(
            $clinic,
            $plan,
            $validated['price_paid'],
            $validated['notes'] ?? null
        );

        return redirect()->route('superadmin.clinics.show', $clinic)
                         ->with('success', "Subscription renewed for {$clinic->name}.");
    }

    public function suspend(Clinic $clinic)
    {
        $this->subscriptionService->suspend($clinic);

        return back()->with('success', "{$clinic->name} has been suspended.");
    }
}
