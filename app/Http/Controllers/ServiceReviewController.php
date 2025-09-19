<?php

namespace App\Http\Controllers;

use App\Models\AgendaAiAppointment;
use App\Models\ServiceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ServiceReviewController extends Controller
{
    public function create(AgendaAiAppointment $appointment)
    {
        abort_if(
            optional(Auth::user()->client)->id !== $appointment->client_id ||
            $appointment->status !== 'concluido',
            403
        );

        $appointment->load('service', 'availability.schedule.professional.user');

        return Inertia::render('Appointments/Review', [
            'appointment' => $appointment,
        ]);
    }

    public function store(Request $request, AgendaAiAppointment $appointment)
    {
        abort_if(
            optional($request->user()->client)->id !== $appointment->client_id ||
            $appointment->status !== 'concluido',
            403
        );

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        ServiceReview::create([
            'appointment_id' => $appointment->id,
            'client_id' => $appointment->client_id,
            'professional_id' => optional($appointment->professional)->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('appointments.history')->with('success', 'Avaliação enviada!');
    }
}
