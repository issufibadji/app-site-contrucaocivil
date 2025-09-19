<?php

namespace App\Http\Controllers;

use App\Models\AgendaAiProfessional;
use Inertia\Inertia;

class PublicProfessionalController extends Controller
{
    public function show(AgendaAiProfessional $professional)
    {
        $professional->load('user.phones', 'user.addresses', 'services', 'reviews');

        return Inertia::render('Public/ProfessionalShow', [
            'professional' => $professional,
        ]);
    }
}
