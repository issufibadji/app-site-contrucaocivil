<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 🔒 Desativa checagem de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpa as tabelas com delete (mais seguro com FKs)
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        Permission::query()->delete();
        Role::query()->delete();

        // 🔓 Reativa checagem de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Criação dos papéis
        $master = Role::firstOrCreate(['name' => 'master']);           // Suporte técnico/TI
        $admin = Role::firstOrCreate(['name' => 'admin']);             // Administrador do negócio
        $professional = Role::firstOrCreate(['name' => 'professional']);// Colaborador/prestador
        $client = Role::firstOrCreate(['name' => 'client']);           // Usuário externo

        // Lista de permissões
        $permissions = [
            'permissions-all',
            'roles-all',
            'roles-user-all',
            'plan-all',
            'configs-all',
            'signature-all',
            'user-all',
            'audits-all',
            'notification-all',
            'menu-all',
            'yourself',
            'clients-all',
            'professionals-all',
            'appointments-all',
            'requests-all',
            'requests-received-all',
            'schedules-all',
            'schedule-availabilities-all',
            'services-all',
            'messages-all',
            'addresses-all',
            'phones-all',
            'mensagens-settings-all',
            'chat-link-settings-all',
            'appointments-history-all',

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Atribui permissões conforme níveis
        $allPermissions = Permission::all();
        $master->syncPermissions($allPermissions);

        $master->syncPermissions([
            'permissions-all',
            'roles-all',
            'roles-user-all',
            'plan-all',
            'configs-all',
            'signature-all',
            'user-all',
            'audits-all',
            'notification-all',
            'menu-all',
            'clients-all',
            'professionals-all',
            'appointments-all',
            'schedules-all',
            'schedule-availabilities-all',
            'services-all',
            'messages-all',
            'addresses-all',
            'phones-all',
            'mensagens-settings-all',
            'chat-link-settings-all',
            'appointments-history-all',
            'requests-received-all'
        ]);

        $admin->syncPermissions([
            'user-all',
            'clients-all',
            'professionals-all',
            'appointments-all',
            'requests-all',
            'requests-received-all',
            'schedules-all',
            'appointments-history-all',
            'schedule-availabilities-all',
            'services-all',
            'messages-all',
            'addresses-all',
            'phones-all',
            'notification-all',
        ]);

        // Permissões limitadas
        $professional->syncPermissions([
            'schedule-availabilities-all',
            'appointments-history-all',
            'schedules-all',
            'requests-all',
            'requests-received-all',
            'messages-all',
            'yourself'
        ]);

        $client->syncPermissions([
            'appointments-history-all',
            'requests-all',
            'messages-all',
            'yourself'

        ]);
    }
}
