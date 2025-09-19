<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;              // ← importe o Str aqui
use App\Models\AgendaAiServiceCategory;
use App\Models\AgendaAiService;

class AgendaAiServiceCategorySeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AgendaAiService::truncate();
        AgendaAiServiceCategory::truncate();
        Schema::enableForeignKeyConstraints();

        $cats = [
            'Instalações e Manutenção Elétrica' => [
                'Eletricista Residencial',
                'Eletricista Industrial',
                'Montador de Painéis Elétricos',
            ],
            'Hidráulica e Saneamento' => [
                'Encanador / Canalizador',
                'Instalador de Sistemas de Gás',
                'Técnico em Refrigeração e Climatização',
            ],
            'Tecnologia da Informação' => [
                'Técnico em Informática',
                'Técnico em Suporte e Manutenção de Computadores',
                'Técnico em Redes de Computadores',
                'Técnico em Segurança da Informação',
            ],
            'Construção Civil e Metalmecânica' => [
                'Pedreiro',
                'Carpinteiro',
                'Serralheiro Metalmecânico',
                'Pintor Predial e Industrial',
                'Soldador',
            ],
            'Automação e Telecomunicações' => [
                'Técnico em Automação Industrial',
                'Técnico em Telecomunicações',
                'Instalador de Sistemas de Segurança Eletrônica',
            ],
            'Manutenção de Dispositivos Móveis' => [
                'Técnico em Manutenção de Celulares e Tablets',
            ],
        ];

        foreach ($cats as $catName => $services) {
            $cat = AgendaAiServiceCategory::create([
                'uuid' => Str::uuid(),
                'name' => $catName,
            ]);

            foreach ($services as $srvName) {
                AgendaAiService::create([
                    'uuid'         => Str::uuid(),
                    'name'         => $srvName,
                    'duration_min' => 60,
                    'price'        => 0.00,
                    'user_id'      => 1,
                    'category_id'  => $cat->id,
                    'image'        => null,
                    'descrition'   => null,
                ]);
            }
        }
    }
}
