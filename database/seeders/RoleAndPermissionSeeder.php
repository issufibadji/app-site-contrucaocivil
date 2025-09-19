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
            'configs-all',
            'user-all',
            'audits-all',
            'notification-all',
            'menu-all',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Atribui permissões conforme níveis
        $master->syncPermissions($permissions);

        $admin->syncPermissions([
            'user-all',
            'notification-all',
        ]);

        $professional->syncPermissions([]);

        $client->syncPermissions([]);
    }
}
