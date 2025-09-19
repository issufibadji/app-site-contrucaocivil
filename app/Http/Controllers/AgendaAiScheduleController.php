<?php

namespace App\Http\Controllers;

use App\Models\AgendaAiProfessional;
use App\Models\AgendaAiSchedule;
use App\Models\AgendaAiScheduleAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendaAiScheduleController extends Controller
{
    public function index()
    {
        $schedules = AgendaAiSchedule::with('professional.user')
            ->withCount('availabilities')
            ->latest()
            ->paginate(15);

        return Inertia::render('Schedules/Index', [
            'schedules' => $schedules,
        ]);
    }

    public function create()
    {
        $professionals = AgendaAiProfessional::with('user')
            ->get()
            ->pluck('user.name', 'id');

        return Inertia::render('Schedules/Create', [
            'professionals' => $professionals,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'schedule' => 'required|string|max:255',
            'professional_id' => 'required|exists:agendaai_professionals,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = AgendaAiSchedule::create([
            'schedule' => $data['schedule'],
            'professional_id' => $data['professional_id'],
        ]);

        $conflict = AgendaAiScheduleAvailability::whereHas('schedule', function ($q) use ($data) {
            $q->where('professional_id', $data['professional_id']);
        })
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Conflito com outra disponibilidade existente.'])->withInput();
        }

        AgendaAiScheduleAvailability::create([
            'schedule_id' => $schedule->id,
            'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Agenda criada com sucesso.');
    }

    public function edit(int $id)
    {
        $schedule = AgendaAiSchedule::findOrFail($id);

        $professionals = AgendaAiProfessional::with('user')
            ->get()
            ->pluck('user.name', 'id');

        return Inertia::render('Schedules/Edit', [
            'schedule' => $schedule,
            'professionals' => $professionals,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $schedule = AgendaAiSchedule::findOrFail($id);

        $data = $request->validate([
            'schedule' => 'required|string|max:255',
            'professional_id' => 'required|exists:agendaai_professionals,id',
            'day_of_week' => 'nullable|integer|min:0|max:6',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $schedule->update([
            'schedule' => $data['schedule'],
            'professional_id' => $data['professional_id'],
        ]);

        if ($request->filled(['day_of_week', 'start_time', 'end_time'])) {
            $conflict = AgendaAiScheduleAvailability::whereHas('schedule', function ($q) use ($schedule) {
                $q->where('professional_id', $schedule->professional_id);
            })
                ->where('day_of_week', $data['day_of_week'])
                ->where(function ($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->exists();

            if ($conflict) {
                return back()->withErrors(['start_time' => 'Conflito com outra disponibilidade existente.'])->withInput();
            }

            AgendaAiScheduleAvailability::create([
                'schedule_id' => $schedule->id,
                'day_of_week' => $data['day_of_week'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
            ]);
        }

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Agenda atualizada com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AgendaAiSchedule::findOrFail($id)->delete();

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Agenda removida com sucesso.');
    }
}
