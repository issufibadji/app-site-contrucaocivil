<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\AgendaAiAppointment;
use App\Models\AgendaAiService;
use App\Models\AgendaAiClient;
use App\Models\AgendaAiScheduleAvailability;
use App\Models\AgendaAiRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class AgendaAiAppointmentController extends Controller
{
    public function index()
    {
        $appointments = AgendaAiAppointment::with(['service','client.user','availability.schedule.professional.user'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
        ]);
    }

    public function create()
    {
        // agora em inglês: 'service' e 'client'
        $services = AgendaAiService::pluck('name', 'id');
        $clients  = AgendaAiClient::with('user')
                     ->get()
                     ->pluck('user.name', 'id');
        $availabilities = AgendaAiScheduleAvailability::with('schedule.professional.user')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = $item->schedule->schedule ?? '';
                if ($item->schedule && $item->schedule->professional && $item->schedule->professional->user) {
                    $name .= ' - ' . $item->schedule->professional->user->name;
                }
                $name .= ' ['.$item->day_of_week.' '.$item->start_time.'-'.$item->end_time.']';
                return [$item->id => $name];
            });

        return Inertia::render('Appointments/Create', [
            'services' => $services,
            'clients' => $clients,
            'availabilities' => $availabilities,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_id'               => 'required|exists:agendaai_services,id',
            'client_id'                => 'required|exists:agendaai_clients,id',
            'schedule_availability_id' => 'required|exists:agendaai_schedule_availabilities,id',
            'scheduled_at'             => 'required|date',
            'status'                   => 'required|in:pendente,confirmado,cancelado',
        ]);

        AgendaAiAppointment::create($data);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Agendamento criado com sucesso.');
    }

    public function edit(int $id)
    {
        $appointment = AgendaAiAppointment::findOrFail($id);

        $services = AgendaAiService::pluck('name', 'id');
        $clients  = AgendaAiClient::with('user')
                     ->get()
                     ->pluck('user.name', 'id');
        $availabilities = AgendaAiScheduleAvailability::with('schedule.professional.user')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = $item->schedule->schedule ?? '';
                if ($item->schedule && $item->schedule->professional && $item->schedule->professional->user) {
                    $name .= ' - ' . $item->schedule->professional->user->name;
                }
                $name .= ' ['.$item->day_of_week.' '.$item->start_time.'-'.$item->end_time.']';
                return [$item->id => $name];
            });

        return Inertia::render('Appointments/Edit', [
            'appointment' => $appointment,
            'services' => $services,
            'clients' => $clients,
            'availabilities' => $availabilities,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $appointment = AgendaAiAppointment::findOrFail($id);

        $data = $request->validate([
            'service_id'               => 'required|exists:agendaai_services,id',
            'client_id'                => 'required|exists:agendaai_clients,id',
            'schedule_availability_id' => 'required|exists:agendaai_schedule_availabilities,id',
            'scheduled_at'             => 'required|date',
            'status'                   => 'required|in:pendente,confirmado,cancelado',
        ]);

        $appointment->update($data);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Agendamento atualizado com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AgendaAiAppointment::findOrFail($id)->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Agendamento excluído com sucesso.');
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $requests = [];
        if ($user->hasRole('client') && $user->client) {
            $requests = AgendaAiRequest::with('service')
                ->where('client_id', $user->client->id)
                ->whereIn('status', ['confirmado', 'cancelado'])
                ->orderByDesc('scheduled_at')
                ->get()
                ->map(function ($request) {
                    return [
                        'serviceName'   => $request->service->name ?? '',
                        'scheduledDate' => Carbon::parse($request->scheduled_at)->format('d/m/Y'),
                        'status'        => $request->status,
                        'actions'       => $request->status === 'confirmado'
                            ? ['repetir', 'avaliar', 'cancelar']
                            : ['repetir'],
                    ];
                })
                ->values()
                ->toArray();
        }

        $appointments = [];
        if ($user->hasRole('professional') && $user->professional) {
            $appointments = AgendaAiAppointment::with(['service', 'client.user', 'availability.schedule'])
                ->whereHas('availability.schedule.professional', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereIn('status', ['confirmado', 'cancelado'])
                ->orderByDesc('scheduled_at')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'serviceName'   => $appointment->service->name ?? '',
                        'scheduledDate' => Carbon::parse($appointment->scheduled_at)->format('d/m/Y'),
                        'status'        => $appointment->status,
                        'actions'       => $appointment->status === 'confirmado'
                            ? ['repetir', 'avaliar', 'cancelar']
                            : ['repetir'],
                    ];
                })
                ->values()
                ->toArray();
        }

        return Inertia::render('Appointments/History', [
            'requests'     => $requests,
            'appointments' => $appointments,
        ]);
    }
}
