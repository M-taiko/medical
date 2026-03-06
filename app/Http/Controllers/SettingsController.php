<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $clinic = Clinic::findOrFail(auth()->user()->clinic_id);
        return view('settings.index', compact('clinic'));
    }

    public function update(Request $request)
    {
        $clinic = Clinic::findOrFail(auth()->user()->clinic_id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string',
            'currency' => 'required|string|max:5',
            'timezone' => 'required|string',
        ]);

        $clinic->update([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'email'   => $validated['email'],
            'address' => $validated['address'],
            'settings' => [
                'currency' => $validated['currency'],
                'timezone' => $validated['timezone'],
            ],
        ]);

        return back()->with('success', 'Clinic settings updated successfully.');
    }
}
