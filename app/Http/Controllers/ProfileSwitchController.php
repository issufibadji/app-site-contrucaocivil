<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileSwitchController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_id' => ['required', Rule::exists('user_profiles', 'id')],
        ]);

        $user = $request->user();

        /** @var UserProfile $profile */
        $profile = $user->profiles()->with('role')->findOrFail($validated['profile_id']);

        DB::transaction(function () use ($user, $profile) {
            $user->switchProfile($profile);
        });

        return back()->with('status', 'profile-switched');
    }
}
