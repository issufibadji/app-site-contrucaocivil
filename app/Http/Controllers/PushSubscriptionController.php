<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => $request->user()->id,
                'p256dh_key' => $data['keys']['p256dh'],
                'auth_key' => $data['keys']['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }
}
