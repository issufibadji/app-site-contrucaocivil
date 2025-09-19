<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuSideBar;

class MenuSidebarSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();
        MenuSideBar::truncate();

        // cores padrão por nível
        $styleMap = [
            1 => 'text-camel-300',
            2 => 'text-vanilla-300',
            3 => 'text-mint-300',
        ];

        // menus disponíveis apenas para os módulos ativos do sistema
        $menuStructure = [
            [
                'description' => 'Meu Perfil',
                'icon'        => 'fa-user',
                'level'       => 1,
                'route'       => 'profile',
                'acl'         => 'yourself',
                'group'       => 'Área Operacional',
                'order'       => 1,
            ],
            [
                'description' => 'Notificações',
                'icon'        => 'fa-bell',
                'level'       => 2,
                'route'       => 'notifications',
                'acl'         => 'notification-all',
                'group'       => 'Gestão da Administração',
                'order'       => 1,
            ],
            [
                'description' => 'Gestão de Usuários',
                'icon'        => 'fa-users',
                'level'       => 2,
                'route'       => 'users',
                'acl'         => 'user-all',
                'group'       => 'Gestão da Administração',
                'order'       => 2,
            ],
            [
                'description' => 'Papéis',
                'icon'        => 'fa-user-shield',
                'level'       => 3,
                'route'       => 'roles',
                'acl'         => 'roles-all',
                'group'       => 'Administração do Sistema',
                'order'       => 1,
            ],
            [
                'description' => 'Permissões',
                'icon'        => 'fa-lock',
                'level'       => 3,
                'route'       => 'permissions',
                'acl'         => 'permissions-all',
                'group'       => 'Administração do Sistema',
                'order'       => 2,
            ],
            [
                'description' => 'Vínculo Papéis/Usuários',
                'icon'        => 'fa-user-tag',
                'level'       => 3,
                'route'       => 'roles-user',
                'acl'         => 'roles-user-all',
                'group'       => 'Administração do Sistema',
                'order'       => 3,
            ],
            [
                'description' => 'Menus do Sistema',
                'icon'        => 'fa-stream',
                'level'       => 3,
                'route'       => 'menus',
                'acl'         => 'menu-all',
                'group'       => 'Administração do Sistema',
                'order'       => 4,
            ],
            [
                'description' => 'Auditorias',
                'icon'        => 'fa-list-alt',
                'level'       => 3,
                'route'       => 'audits',
                'acl'         => 'audits-all',
                'group'       => 'Administração do Sistema',
                'order'       => 5,
            ],
            [
                'description' => 'Configurações',
                'icon'        => 'fa-cogs',
                'level'       => 3,
                'route'       => 'config',
                'acl'         => 'configs-all',
                'group'       => 'Administração do Sistema',
                'order'       => 6,
            ],
        ];

        // persiste no banco
        foreach ($menuStructure as $item) {
            MenuSideBar::create([
                'description' => $item['description'],
                'icon'        => $item['icon'],
                'level'       => $item['level'],
                'route'       => $item['route'],
                'acl'         => $item['acl'],
                'parent_id'   => $item['parent_id'] ?? null,
                'group'       => $item['group'],
                'order'       => $item['order'],
                'active'      => true,
                'style'       => "color: {$styleMap[$item['level']]}",
            ]);
        }
    }
}
