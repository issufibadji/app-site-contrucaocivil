<?php

namespace App\Http\Controllers;

use App\Models\AgendaAiService;
use Inertia\Inertia;

class PublicSearchController extends Controller
{
    public function index()
    {
        $services = AgendaAiService::with(['professionals.user', 'category'])
            ->select([
                'uuid as id',
                'name',
                'descrition as description',
                'price',
                'category_id',
            ])
            ->get()
            ->map(function ($svc) {
                // Pega o primeiro prestador, se existir
                $firstProf = $svc->professionals->first();
                $profUser = $firstProf?->user;

                return [
                    'id'           => $svc->id,
                    'name'         => $svc->name,
                    'description'  => $svc->description,
                    'price'        => $svc->price,
                    'category'     => $svc->category?->name    || null,
                    'professional' => $profUser?->name         || null,
                    'rating'       => 5,
                    'reviews'      => 0,
                ];
            });

        return Inertia::render('PublicSearch', [
            'services' => $services,
        ]);
    }
}
