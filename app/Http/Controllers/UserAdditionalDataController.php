<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdditionalDataRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserAdditionalDataController extends Controller
{
    public function store(AdditionalDataRequest $request): RedirectResponse
    {
        $additionalData = $request->user()->additionalData()->firstOrNew([]);

        $additionalData->fill($request->validated());
        $additionalData->save();

        return Redirect::route('profile.edit')->with('status', 'additional-data-saved');
    }

    public function update(AdditionalDataRequest $request): RedirectResponse
    {
        $additionalData = $request->user()->additionalData()->firstOrNew([]);

        $additionalData->fill($request->validated());
        $additionalData->save();

        return Redirect::route('profile.edit')->with('status', 'additional-data-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $additionalData = $request->user()->additionalData;

        if ($additionalData) {
            $additionalData->delete();
        }

        return Redirect::route('profile.edit')->with('status', 'additional-data-deleted');
    }
}
