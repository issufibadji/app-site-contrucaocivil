<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PhoneRequest;
use App\Models\AgendaAiPhone;
use App\Models\AgendaAiProfessional;
use App\Models\User;
use Inertia\Inertia;
class AgendaAiPhoneController extends Controller
{
    public function index()
    {
        $phones = AgendaAiPhone::with('user')
                                ->latest()
                                ->paginate(15);

        //dd($phones->toArray());
        return Inertia::render('Phones/Index', [
            'phones' => $phones,
        ]);
    }

    public function create()
    {
        $professionals  = AgendaAiProfessional::with('user')->get();
        $users = User::with('client', 'professional', 'phones', 'addresses')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_type' => $user->professional ? 'profissional' : ($user->client ? 'cliente' : 'outro'),
                    'phone' => optional($user->phones->first())->phone,
                    'address' => optional($user->addresses->first())->street,
                ];
            });


        return Inertia::render('Phones/Create', [
            'professionals' => $professionals,
            'users' => $users,
        ]);
    }

    public function edit($id)
    {
        $professionals  = AgendaAiProfessional::with('user')->get();
        $users = User::with('client', 'professional', 'phones', 'addresses')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_type' => $user->professional ? 'profissional' : ($user->client ? 'cliente' : 'outro'),
                    'phone' => optional($user->phones->first())->phone,
                    'address' => optional($user->addresses->first())->street,
                ];
            });

        $phone = AgendaAiPhone::findOrFail($id);

        return Inertia::render('Phones/Edit', [
            'phone' => $phone,
            'professionals' => $professionals,
            'users' => $users,
        ]);
    }


    public function store(PhoneRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        AgendaAiPhone::create($validated);

        return redirect()
            ->route('phones.index')
            ->with('success', 'Telefone cadastrado com sucesso.');
    }

    public function update(PhoneRequest $request, $id): RedirectResponse
    {
        $validated = $request->validated();

        $phoneModel = AgendaAiPhone::findOrFail($id);

        $phoneModel->update($validated);

        return redirect()
            ->route('phones.index')
            ->with('success', 'Telefone atualizado com sucesso.');
    }


    public function destroy($id): RedirectResponse
    {
        AgendaAiPhone::findOrFail($id)->delete();
        return redirect()
            ->route('phones.index')
            ->with('success', 'Telefone removido com sucesso.');
    }
}
