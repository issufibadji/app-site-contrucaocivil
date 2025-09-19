<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user      = $request->user();
        $google2fa = new Google2FA();

        // ——— seu código de 2FA aqui ———
        try {
            if (!$user->google2fa_secret) {
                throw new \Exception('No secret yet');
            }
            $secret = Crypt::decrypt($user->google2fa_secret);
        } catch (\Exception $e) {
            $secret                   = $google2fa->generateSecretKey();
            $user->google2fa_secret   = Crypt::encrypt($secret);
            $user->save();
        }
        $qrCodeUrl = $google2fa->getQRCodeUrl(config('app.name'), $user->email, $secret);
        $renderer  = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());
        $writer    = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        $user = $request->user();

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status'          => session('status'),
            'qrCodeUrl'       => 'data:image/svg+xml;base64,'.base64_encode($qrCodeSvg),
            'secretKey'       => $secret,
            'professional'    => null,
            'categories'      => [],
            'addresses'       => [],
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        if ($user->avatar_path) {
            File::delete([
                public_path('storage/'.$user->avatar_path),
                storage_path('app/public/'.$user->avatar_path),
            ]);
        }

        $file = $validated['avatar'];
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $directory = public_path('storage/avatars');

        File::ensureDirectoryExists($directory);
        $file->move($directory, $filename);

        $user->forceFill([
            'avatar_path' => 'avatars/'.$filename,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
