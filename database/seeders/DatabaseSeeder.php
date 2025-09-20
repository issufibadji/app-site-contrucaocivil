<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria usuários fictícios
        User::factory(10)->create();

        // Executa os seeders dependentes
        $this->call([
            RoleAndPermissionSeeder::class,
            MenuSidebarSeeder::class, // ✅ Seeder dos Menus
            AppConfigSeeder::class,
        ]);

        // Usuário master
        $master = User::factory()->create([
            'name' => 'Master TI',
            'email' => 'master@agender.com',
        ]);

        // Usuário admin (gestor do negócio)
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@agender.com',
        ]);

        // Cliente externo
        $client = User::factory()->create([
            'name' => 'Cliente Externo',
            'email' => 'cliente@agender.com',
        ]);

        // Atribui papéis
        $this->assignProfileWithRole($master, 'master');
        $this->assignProfileWithRole($admin, 'admin');
        $this->assignProfileWithRole($client, 'client');

        // Permissões diretas opcionais
        $admin->givePermissionTo('audits-all');
        $client->givePermissionTo('yourself');
    }

    private function assignProfileWithRole(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            return;
        }

        $user->assignRole($role);

        $profile = $user->profiles()->firstOrCreate(
            ['role_id' => $role->id],
            ['name' => Str::headline($role->name), 'is_default' => true]
        );

        $user->switchProfile($profile);
    }
}
