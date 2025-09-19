<?php

namespace App\Http\Controllers;

use App\Models\{AgendaAiRequest, AgendaAiServiceCategory, AgendaAiService, AgendaAiProfessional, AgendaAiScheduleAvailability};
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgendaAiRequestController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        $requests = AgendaAiRequest::with(['service','category','professional.user'])
            ->where('client_id', optional($client)->id)
            ->latest()
            ->paginate(15);

        return Inertia::render('Requests/Index', [
            'requests' => $requests,
        ]);
    }

    public function create()
    {
        $categories = AgendaAiServiceCategory::all();
        $selectedCategory = request()->input('category_id', optional($categories->first())->id);
        $services = AgendaAiService::where('category_id', $selectedCategory)->get();
        $professionals = AgendaAiProfessional::all();
        $availabilities = AgendaAiScheduleAvailability::when(request()->professional_id, function ($query) {
                $query->whereHas('schedule', function ($q) {
                    $q->where('professional_id', request()->professional_id);
                });
            })
            ->with('schedule.professional.user')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = $item->schedule->schedule ?? '';
                if ($item->schedule && $item->schedule->professional && $item->schedule->professional->user) {
                    $name .= ' - ' . $item->schedule->professional->user->name;
                }
                $name .= ' ['.$item->day_of_week.' '.$item->start_time.'-'.$item->end_time.']';
                return [$item->id => $name];
            });
        $priorities = ['urgente' => 'Urgente', 'normal' => 'Normal'];

        return Inertia::render('Requests/Create', [
            'services' => $services,
            'availabilities' => $availabilities,
            'categories' => $categories,
            'professionals' => $professionals,
            'priorities' => $priorities,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:agendaai_service_categories,id',
            'service_id' => 'required|exists:agendaai_services,id',
            'professional_id' => 'nullable|exists:agendaai_professionals,id',
            'schedule_availability_id' => 'nullable|exists:agendaai_schedule_availabilities,id',
            'duration' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'scheduled_at' => 'required|date|after:today',
            'address' => 'nullable|string',
            'photo' => 'nullable|image',
            'priority' => 'required|in:urgente,normal',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('requests', 'public');
        }
        $data['client_id'] = Auth::user()->client->id;
        $data['status'] = 'pendente';

        AgendaAiRequest::create($data);

        return redirect()->route('requests.index')->with('success', 'Solicitação enviada com sucesso.');
    }

    public function edit(int $id)
    {
        $requestModel = AgendaAiRequest::findOrFail($id);
        $categories = AgendaAiServiceCategory::all();
        $services = AgendaAiService::where('category_id', $requestModel->category_id)->get();
        $professionals = AgendaAiProfessional::all();
        $availabilities = AgendaAiScheduleAvailability::when($requestModel->professional_id, function ($query) use ($requestModel) {
                $query->whereHas('schedule', function ($q) use ($requestModel) {
                    $q->where('professional_id', $requestModel->professional_id);
                });
            })
            ->with('schedule.professional.user')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = $item->schedule->schedule ?? '';
                if ($item->schedule && $item->schedule->professional && $item->schedule->professional->user) {
                    $name .= ' - ' . $item->schedule->professional->user->name;
                }
                $name .= ' ['.$item->day_of_week.' '.$item->start_time.'-'.$item->end_time.']';
                return [$item->id => $name];
            });
        $priorities = ['urgente' => 'Urgente', 'normal' => 'Normal'];

        return Inertia::render('Requests/Edit', [
            'request' => $requestModel,
            'services' => $services,
            'availabilities' => $availabilities,
            'categories' => $categories,
            'professionals' => $professionals,
            'priorities' => $priorities,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $requestModel = AgendaAiRequest::findOrFail($id);
        $data = $request->validate([
            'category_id' => 'required|exists:agendaai_service_categories,id',
            'service_id' => 'required|exists:agendaai_services,id',
            'professional_id' => 'nullable|exists:agendaai_professionals,id',
            'schedule_availability_id' => 'nullable|exists:agendaai_schedule_availabilities,id',
            'duration' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'scheduled_at' => 'required|date|after:today',
            'address' => 'nullable|string',
            'photo' => 'nullable|image',
            'priority' => 'required|in:urgente,normal',
            'description' => 'nullable|string',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);
        if ($request->hasFile('photo')) {
            if ($requestModel->photo_path) {
                Storage::disk('public')->delete($requestModel->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('requests', 'public');
        }
        $requestModel->update($data);
        return redirect()->route('requests.index')->with('success','Solicitação atualizada com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AgendaAiRequest::findOrFail($id)->delete();
        return redirect()->route('requests.index')->with('success','Solicitação removida.');
    }

    public function received()
    {
        $professionalId = optional(Auth::user()->professional)->id;

        $requests = AgendaAiRequest::with(['client.user', 'service'])
            ->where('status', 'pendente')
            ->where('professional_id', $professionalId)
            ->get();

        return Inertia::render('Requests/Received', [
            'requests' => $requests,
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|in:confirmado,cancelado',
        ]);

        $requestModel = AgendaAiRequest::where('professional_id', optional(Auth::user()->professional)->id)
            ->findOrFail($id);

        $requestModel->update($data);

        return response()->json(['success' => true]);
    }
}
