<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $active = $request->input('active');
        $verified = $request->input('verified');

        $users = User::query()
            ->when($search, function ($query, $term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                          ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($active !== null && $active !== '', function ($query) use ($active) {
                $value = filter_var($active, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                if ($value === null) {
                    return;
                }

                $query->where('active', $value);
            })
            ->when($verified !== null && $verified !== '', function ($query) use ($verified) {
                $isVerified = filter_var($verified, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

                if ($isVerified === null) {
                    return;
                }

                $isVerified
                    ? $query->whereNotNull('email_verified_at')
                    : $query->whereNull('email_verified_at');
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only('search', 'active', 'verified'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'active' => 'required|boolean',
            'active_2fa' => 'boolean',
            'email_verified_at' => 'nullable|date',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'active' => $validated['active'],
            'active_2fa' => data_get($validated, 'active_2fa', false),
            'email_verified_at' => $validated['email_verified_at'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');
    }


    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => Role::all(),
            'user_roles' => $user->roles->pluck('name'),
        ]);
    }

   public function update(Request $request, User $user)
   {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|confirmed|min:6',
            'active' => 'required|boolean',
            'active_2fa' => 'boolean',
            'email_verified_at' => 'nullable|date',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'active' => $validated['active'],
            'active_2fa' => array_key_exists('active_2fa', $validated)
                ? (bool) $validated['active_2fa']
                : $user->active_2fa,
            'email_verified_at' => $validated['email_verified_at'],
            'password' => $request->filled('password') ? Hash::make($validated['password']) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso.');
    }

    public function toggleStatus(User $user)
    {
        $user->active = !$user->active;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Status do usuário atualizado.');
    }



    public function toggle2FA(User $user)
    {
        $user->active_2fa = !$user->active_2fa;
        $user->save();

        return redirect()->back()->with('success', 'Status de autenticação em 2FA atualizado com sucesso.');
    }


    }
