<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgendaAiProfessional;
use App\Models\AgendaAiPhone;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class AgendaAiProfessionalController extends Controller
{
    private const PROFESSIONS = ['Eletricista', 'Pedreiro', 'Carpinteiro', 'Canalizador'];
    public function index()
    {
        $professionals = AgendaAiProfessional::with('user.addresses', 'user.phones')
                            ->latest()
                            ->paginate(15);

        return Inertia::render('Professionals/Index', [
            'professionals' => $professionals,
            'professions'   => self::PROFESSIONS,
        ]);
    }

    public function create()
    {
        $users = User::orderBy('name')->pluck('name', 'id');

        // Placeholder para start do formulário de telefones (caso queira já exibir um)
        $phones = old('phones', [[
            'ddi' => '',
            'ddd' => '',
            'phone' => '',
        ]]);

        return Inertia::render('Professionals/Create', [
            'users' => $users,
            'phones' => $phones,
            'professions' => self::PROFESSIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'professions'  => 'required|array',
            'professions.*'=> 'string',
            'description'  => 'nullable|string',
            'phones.*.ddi' => 'nullable|string|max:5',
            'phones.*.ddd' => 'nullable|string|max:5',
            'phones.*.phone' => 'nullable|string|max:20',
        ]);

        $professional = AgendaAiProfessional::create($validated);

        if ($request->has('phones')) {
            foreach ($request->phones as $phone) {
                if (empty($phone['phone'])) continue;

                AgendaAiPhone::create([
                    'ddi'  => $phone['ddi'] ?? null,
                    'ddd'  => $phone['ddd'] ?? null,
                    'phone' => $phone['phone'],
                    'professional_id'  => $professional->id,
                ]);
            }
        }

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional cadastrado com sucesso.');
    }

    public function edit(string $uuid)
    {
        $professional = AgendaAiProfessional::with('phones')
                            ->where('uuid', $uuid)
                            ->firstOrFail();

        $users = User::orderBy('name')->pluck('name', 'id');

        // Se voltou de validação com old(), usa aquilo; senão monta do Model
        $phonesOld = old('phones', null);
        $phones    = $phonesOld ?: $professional->phones->map(function($p) {
            return [
                'ddi' => $p->ddi,
                'ddd'=> $p->ddd,
                'phone' => $p->phone,
            ];
        })->toArray();

        return Inertia::render('Professionals/Edit', [
            'professional' => $professional,
            'users' => $users,
            'phones' => $phones,
            'professions' => self::PROFESSIONS,
        ]);
    }

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $professional = AgendaAiProfessional::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'professions'  => 'required|array',
            'professions.*'=> 'string',
            'description'  => 'nullable|string',
            'phones.*.ddi' => 'nullable|string|max:5',
            'phones.*.ddd' => 'nullable|string|max:5',
            'phones.*.phone'=> 'nullable|string|max:20',
        ]);

        $professional->update($validated);

        // Limpa os antigos e cadastra os novos, ligando ao professional_id
        AgendaAiPhone::where('professional_id', $professional->id)->delete();

        foreach ($request->phones ?? [] as $phone) {
            if (empty($phone['phone'])) continue;

            AgendaAiPhone::create([
                'ddi'=> $phone['ddi'] ?? null,
                'ddd' => $phone['ddd'] ?? null,
                'phone' => $phone['phone'],
                'professional_id' => $professional->id,
            ]);
        }

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(string $uuid): RedirectResponse
    {
        $professional = AgendaAiProfessional::where('uuid', $uuid)->firstOrFail();

        AgendaAiPhone::where('professional_id', $professional->id)->delete();
        $professional->delete();

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional removido com sucesso.');
    }
}
