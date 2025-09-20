<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleUserController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('roles-user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }
        $users = User::with('roles')->get();
        $roles = Role::all();

        return Inertia::render('Admin/RoleUser/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function assign(Request $request)
    {
        if (!Auth::user()->can('roles-user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $role = Role::findById($validated['role_id']);

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        $profile = $user->profiles()->firstOrCreate(
            ['role_id' => $role->id],
            [
                'name' => Str::headline($role->name),
                'is_default' => $user->profiles()->doesntExist(),
            ]
        );

        if (! $user->currentProfile) {
            $user->switchProfile($profile);
        }

        return back()->with('success', 'Papel atribuído ao usuário.');
    }

    public function remove(Request $request)
    {
        if (!Auth::user()->can('roles-user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $role = Role::findById($validated['role_id']);

        $currentProfile = $user->currentProfile;
        $profileToDelete = $user->profiles()->where('role_id', $role->id)->first();

        if ($user->hasRole($role)) {
            $user->removeRole($role);
        }

        if ($profileToDelete) {
            $profileId = $profileToDelete->id;
            $profileToDelete->delete();

            if ($currentProfile && $currentProfile->id === $profileId) {
                $nextProfile = $user->profiles()->first();

                if ($nextProfile) {
                    $user->switchProfile($nextProfile);
                } else {
                    $user->forceFill(['current_profile_id' => null])->save();
                    $user->syncRoles([]);
                }
            }
        }

        return back()->with('success', 'Papel removido do usuário.');
    }
}
