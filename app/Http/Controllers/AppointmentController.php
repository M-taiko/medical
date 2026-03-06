<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $visits = Visit::with(['patient', 'doctor'])->get();
            $events = $visits->map(function ($v) {
                return [
                    'id'              => $v->id,
                    'title'           => $v->patient->first_name . ' (' . ($v->chief_complaint ?? 'Routine') . ')',
                    'start'           => $v->appointment_time->toIso8601String(),
                    'end'             => $v->appointment_time->copy()->addMinutes(45)->toIso8601String(),
                    'backgroundColor' => $v->status == 'scheduled' ? '#4F46E5' : ($v->status == 'completed' ? '#10B981' : '#6B7280'),
                    'borderColor'     => 'transparent',
                    'url'             => route('dental-chart.show', $v->patient_id),
                ];
            });
            return response()->json($events);
        }

        $status = $request->input('status');
        $visits = Visit::with(['patient', 'doctor'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('appointment_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('appointments.index', compact('visits', 'status'));
    }

    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = User::where('role', 'doctor')->orderBy('name')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:users,id',
            'appointment_time' => 'required|date',
            'chief_complaint'  => 'nullable|string|max:500',
        ]);

        $validated['status']    = 'scheduled';
        $validated['clinic_id'] = auth()->user()->clinic_id;

        Visit::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully!');
    }

    public function edit(Visit $visit)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = User::where('role', 'doctor')->orderBy('name')->get();
        return view('appointments.edit', compact('visit', 'patients', 'doctors'));
    }

    public function update(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:users,id',
            'appointment_time' => 'required|date',
            'chief_complaint'  => 'nullable|string|max:500',
            'follow_up_date'   => 'nullable|date',
        ]);

        $visit->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated.');
    }

    public function updateStatus(Request $request, Visit $visit)
    {
        $request->validate([
            'status'    => 'required|in:scheduled,completed,cancelled',
            'diagnosis' => 'nullable|string|max:1000',
        ]);

        $visit->update([
            'status'    => $request->status,
            'diagnosis' => $request->diagnosis,
        ]);

        return redirect()->back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
