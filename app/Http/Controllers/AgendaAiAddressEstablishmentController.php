<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AddressRequest;
use App\Models\AgendaAiAddressEstablishment;
use App\Models\User;
use Inertia\Inertia;

class AgendaAiAddressEstablishmentController extends Controller
{
    public function index()
    {
        $addresses = AgendaAiAddressEstablishment::with('user')
                        ->latest()
                        ->paginate(15);
        return Inertia::render('Addresses/Index', [
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        $users = User::with('client', 'professional')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_type' => $user->professional ? 'profissional' : ($user->client ? 'cliente' : 'outro'),
                ];
            });
        return Inertia::render('Addresses/Create', [
            'users' => $users,
        ]);
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $data = $request->validated();

        AgendaAiAddressEstablishment::create($data);

        return redirect()->route('addresses.index')
                         ->with('success','Address created.');
    }

    public function edit(int $id)
    {
        $address = AgendaAiAddressEstablishment::findOrFail($id);
        $users = User::with('client', 'professional')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_type' => $user->professional ? 'profissional' : ($user->client ? 'cliente' : 'outro'),
                ];
            });
        return Inertia::render('Addresses/Edit', [
            'address' => $address,
            'users' => $users,
        ]);
    }

    public function update(AddressRequest $request, int $id): RedirectResponse
    {
        $address = AgendaAiAddressEstablishment::findOrFail($id);
        $data = $request->validated();

        $address->update($data);

        return redirect()->route('addresses.index')
                         ->with('success','Address updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AgendaAiAddressEstablishment::findOrFail($id)->delete();
        return redirect()->route('addresses.index')
                         ->with('success','Address deleted.');
    }
}
