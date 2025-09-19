<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\AgendaAiService;
use App\Models\AgendaAiProfessional;
use App\Models\AgendaAiServiceCategory; // ← importado
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AgendaAiServiceController extends Controller
{
    public function index()
    {
        $services   = AgendaAiService::with('category','professionals')
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(15);

        // <-- nova linha: carregamos todas as categorias para o filtro
        $categories = AgendaAiServiceCategory::all();

        return Inertia::render('Services/Index', [
            'services'   => $services,
            'categories' => $categories,   // ← passamos categorias
        ]);
    }

    public function create()
    {
        // categorias com os serviços para o droplist e lista dinâmica
        $categories    = AgendaAiServiceCategory::with('services')->get();
        $professionals = AgendaAiProfessional::with('user')
                                             ->get()
                                             ->pluck('user.name', 'id');

        // instancia vazia para o partial
        $service = new AgendaAiService();

        return Inertia::render('Services/Create', [
            'categories'    => $categories,    // ← passamos categorias
            'professionals' => $professionals,
            'service'       => $service,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:agendaai_service_categories,id', // validação de categoria
            'name'           => 'required|string|max:255',
            'duration_min'   => 'required|integer|min:1',
            'price'          => 'required|numeric|min:0',
            'descrition'     => 'required|string|max:1000',
            'image'          => 'nullable|image|max:2048',
            'professionals'  => 'nullable|array',
            'professionals.*'=> 'exists:agendaai_professionals,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images','public');
        }

        $validated['user_id'] = Auth::id();
       // $validated['user_id'] = $request->user()->id;

        $service = AgendaAiService::create($validated);
        $service->refresh();
        $service->professionals()->sync($validated['professionals'] ?? []);

        return redirect()
            ->route('services.index')
            ->with('success','Serviço criado com sucesso.');
    }

    public function edit(string $uuid)
    {
        // traz o serviço já com categoria e profissionais carregados
        $service = AgendaAiService::with('category','professionals')
                                  ->findOrFail($uuid);

        // todas as categorias (com seus serviços) para o droplist
        $categories    = AgendaAiServiceCategory::with('services')->get();
        $professionals = AgendaAiProfessional::with('user')
                                             ->get()
                                             ->pluck('user.name', 'id');

        return Inertia::render('Services/Edit', [
            'service'       => $service,
            'categories'    => $categories,    // ← passamos categorias aqui também
            'professionals' => $professionals,
        ]);
    }

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $service = AgendaAiService::findOrFail($uuid);

        $validated = $request->validate([
            'category_id'    => 'required|exists:agendaai_service_categories,id',
            'name'           => 'required|string|max:255',
            'duration_min'   => 'required|integer|min:1',
            'price'          => 'required|numeric|min:0',
            'descrition'     => 'required|string|max:1000',
            'image'          => 'nullable|image|max:2048',
            'professionals'  => 'nullable|array',
            'professionals.*'=> 'exists:agendaai_professionals,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images','public');
        }

        $service->update($validated);
        $service->professionals()->sync($validated['professionals'] ?? []);

        return redirect()
            ->route('services.index')
            ->with('success', 'Serviço atualizado com sucesso.');
    }

    public function destroy(string $uuid): RedirectResponse
    {
        $service = AgendaAiService::findOrFail($uuid);
        $service->professionals()->detach();
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Serviço excluído com sucesso.');
    }
}
