<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserAddressController extends Controller
{
    public function store(UserAddressRequest $request): RedirectResponse
    {
        $request->user()->address()->updateOrCreate([], $request->validated());

        return Redirect::route('profile.edit')->with('status', 'address-saved');
    }

    public function update(UserAddressRequest $request): RedirectResponse
    {
        $request->user()->address()->updateOrCreate([], $request->validated());

        return Redirect::route('profile.edit')->with('status', 'address-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $address = $request->user()->address;

        if ($address) {
            $address->delete();
        }

        return Redirect::route('profile.edit')->with('status', 'address-deleted');
    }
}
