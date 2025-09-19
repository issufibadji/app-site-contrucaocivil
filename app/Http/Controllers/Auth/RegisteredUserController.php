<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgendaAiPhone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Exibe a tela de registro.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Processa o cadastro de um novo usuário administrador e cria o estabelecimento vinculado.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('admin');

        // Registra telefone vinculado ao usuário
        AgendaAiPhone::create([
            'phone'   => $request->phone,
            'user_id' => $user->id,
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Redireciona para o dashboard após cadastro
        return redirect()->route('dashboard')
            ->with('success', 'Cadastro concluído com sucesso.');

    }
}
