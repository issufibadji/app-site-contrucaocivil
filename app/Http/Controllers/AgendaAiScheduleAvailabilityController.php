<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\AgendaAiScheduleAvailability;
use App\Models\AgendaAiSchedule;
use Inertia\Inertia;

class AgendaAiScheduleAvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = AgendaAiScheduleAvailability::with('schedule.professional.user')
            ->orderBy('day_of_week')
            ->paginate(15);

        return Inertia::render('ScheduleAvailabilities/Index', [
            'availabilities' => $availabilities,
        ]);
    }

    public function create()
    {
        $schedules = AgendaAiSchedule::with('professional.user')->get()->mapWithKeys(function ($schedule) {
            $name = $schedule->schedule;
            if ($schedule->professional && $schedule->professional->user) {
                $name .= ' - ' . $schedule->professional->user->name;
            }
            return [$schedule->id => $name];
        });

        return Inertia::render('ScheduleAvailabilities/Create', [
            'schedules' => $schedules,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'schedule_id' => 'required|exists:agendaai_schedules,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        AgendaAiScheduleAvailability::create($data);

        return redirect()->route('schedule-availabilities.index')
            ->with('success', 'Disponibilidade cadastrada com sucesso.');
    }

    public function edit(int $id)
    {
        $availability = AgendaAiScheduleAvailability::findOrFail($id);

        $schedules = AgendaAiSchedule::with('professional.user')->get()->mapWithKeys(function ($schedule) {
            $name = $schedule->schedule;
            if ($schedule->professional && $schedule->professional->user) {
                $name .= ' - ' . $schedule->professional->user->name;
            }
            return [$schedule->id => $name];
        });

        return Inertia::render('ScheduleAvailabilities/Edit', [
            'availability' => $availability,
            'schedules' => $schedules,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $availability = AgendaAiScheduleAvailability::findOrFail($id);

        $data = $request->validate([
            'schedule_id' => 'required|exists:agendaai_schedules,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        $availability->update($data);

        return redirect()->route('schedule-availabilities.index')
            ->with('success', 'Disponibilidade atualizada com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AgendaAiScheduleAvailability::findOrFail($id)->delete();

        return redirect()->route('schedule-availabilities.index')
            ->with('success', 'Disponibilidade removida com sucesso.');
    }
}
