<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount('subscriptions')->get();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'slug'             => 'required|string|max:50|unique:subscription_plans,slug',
            'duration_months'  => 'required|integer|min:1|max:12',
            'price'            => 'required|numeric|min:0',
            'features'         => 'nullable|array',
            'is_active'        => 'boolean',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('superadmin.plans.index')
                         ->with('success', 'Subscription plan created.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'duration_months' => 'required|integer|min:1|max:12',
            'price'           => 'required|numeric|min:0',
            'is_active'       => 'boolean',
        ]);

        $plan->update($validated);

        return redirect()->route('superadmin.plans.index')
                         ->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->subscriptions()->active()->exists()) {
            return back()->with('error', 'Cannot delete a plan with active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('superadmin.plans.index')
                         ->with('success', 'Plan deleted.');
    }
}
