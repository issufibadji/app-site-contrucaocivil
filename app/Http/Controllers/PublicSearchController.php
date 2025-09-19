<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PublicSearchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PublicSearch', [
            'services' => [],
        ]);
    }
}
