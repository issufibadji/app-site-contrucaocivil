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

        // estrutura completa de menus
        $menuStructure = [
            // --- Área Operacional (Público)

            [
                'description' => 'Agendar/Solicitar Serviços',
                'icon'        => 'fa-bullhorn',
                'level'       => 1,
                'route'       => 'requests',
                'acl'         => 'requests-all',
                'group'       => 'Público',
                'order'       => 1,
            ],

            [
                'description' => 'Solicitações Recebidas',
                'icon'        => 'fa-inbox',
                'level'       => 1,
                'route'       => 'requests/received',
                'acl'         => 'requests-received-all',
                'group'       => 'Público',
                'order'       => 2,
            ],

            [
                'description' => 'Minha Agenda',
                'icon'        => 'fa-calendar-days',
                'level'       => 1,
                'route'       => 'schedules',
                'acl'         => 'schedules-all',
                'group'       => 'Público',
                'order'       => 3,
            ],

            [
                'description' => 'Disponibilidades',
                'icon'        => 'fa-clock',
                'level'       => 1,
                'route'       => 'schedule-availabilities',
                'acl'         => 'schedule-availabilities-all',
                'group'       => 'Público',
                'order'       => 4,
            ],
            [
                'description' => 'Histórico de Serviços',
                'icon'        => 'fa-history',
                'level'       => 1,
                'route'       => 'appointments/history',
                'acl'         => 'appointments-history-all',
                'group'       => 'Público',
                'order'       => 5,
            ],
            [
                'description' => 'Avaliações',
                'icon'        => 'fa-star',
                'level'       => 1,
                'route'       => 'reviews',
                'acl'         => 'appointments-all',
                'group'       => 'Público',
                'order'       => 6,
            ],
            [
                'description' => 'Mensagens / Suporte',
                'icon'        => 'fa-comment-dots',
                'level'       => 1,
                'route'       => 'messages',
                'acl'         => 'messages-all',
                'group'       => 'Público',
                'order'       => 7,
            ],

            // --- Gestão da Administração (Administração)
            [
                'description' => 'Gestão de Usuários',
                'icon'        => 'fa-user-cog',
                'level'       => 2,
                'route'       => 'users',
                'acl'         => 'user-all',
                'group'       => 'Administração',
                'order'       => 1,
            ],
            [
                'description' => 'Gestão de Prestadores',
                'icon'        => 'fa-user-tie',
                'level'       => 2,
                'route'       => 'professionals',
                'acl'         => 'professionals-all',
                'group'       => 'Administração',
                'order'       => 2,
            ],
            [
                'description' => 'Gestão de Clientes',
                'icon'        => 'fa-user',
                'level'       => 2,
                'route'       => 'clients',
                'acl'         => 'clients-all',
                'group'       => 'Administração',
                'order'       => 3,
            ],
            [
                'description' => 'Gestão de Telefones',
                'icon'        => 'fa-phone',
                'level'       => 2,
                'route'       => 'phones',
                'acl'         => 'phones-all',
                'group'       => 'Administração',
                'order'       => 4,
            ],
            [
                'description' => 'Gestão de Endereços',
                'icon'        => 'fa-address-card',
                'level'       => 2,
                'route'       => 'addresses',
                'acl'         => 'addresses-all',
                'group'       => 'Administração',
                'order'       => 5,
            ],
            [
                'description' => 'Serviços Cadastrados',
                'icon'        => 'fa-briefcase',
                'level'       => 2,
                'route'       => 'services',
                'acl'         => 'services-all',
                'group'       => 'Administração',
                'order'       => 6,
            ],
            [
                'description' => 'Gestão de Agendamentos',
                'icon'        => 'fa-calendar',
                'level'       => 2,
                'route'       => 'appointments',
                'acl'         => 'appointments-all',
                'group'       => 'Público',
                'order'       => 7,
            ],
            [
                'description' => 'Moderação de Conteúdo',
                'icon'        => 'fa-shield-alt',
                'level'       => 2,
                'route'       => 'moderation',
                'acl'         => 'moderation-all',
                'group'       => 'Administração',
                'order'       => 8,
            ],
            [
                'description' => 'Gestão de Planos',
                'icon'        => 'fa-layer-group',
                'level'       => 2,
                'route'       => 'plans',
                'acl'         => 'plan-all',
                'group'       => 'Administração',
                'order'       => 9,
            ],
            [
                'description' => 'Configurações de Sistema',
                'icon'        => 'fa-cogs',
                'level'       => 2,
                'route'       => 'config',
                'acl'         => 'configs-all',
                'group'       => 'Administração',
                'order'       => 10,
            ],
             [
                'description' => 'Enviar notificações',
                'icon'        => 'fa-paper-plane',
                'level'       => 3,
                'route'       => 'notifications/create',
                'acl'         => 'notification-all',
                'group'       => 'Sistema',
                'order'       => 11,
            ],

            // --- Sistema (master)
            [
                'description' => 'Gestão de Papéis',
                'icon'        => 'fa-shield-alt',
                'level'       => 3,
                'route'       => 'roles',
                'acl'         => 'roles-all',
                'group'       => 'Sistema',
                'order'       => 1,
            ],
            [
                'description' => 'Gestão de Permissões',
                'icon'        => 'fa-lock',
                'level'       => 3,
                'route'       => 'permissions',
                'acl'         => 'permissions-all',
                'group'       => 'Sistema',
                'order'       => 2,
            ],

            [
                'description' => 'Roles/Usuários',
                'icon'        => 'fa-user-tag',
                'level'       => 3,
                'route'       => 'roles-user',
                'acl'         => 'roles-user-all',
                'group'       => 'Sistema',
                'order'       => 3,
            ],
            [
                'description' => 'Gestão de Menus',
                'icon'        => 'fa-stream',
                'level'       => 3,
                'route'       => 'menus',
                'acl'         => 'menu-all',
                'group'       => 'Sistema',
                'order'       => 4,
            ],
            [
                'description' => 'Auditoria/Logs de Acesso',
                'icon'        => 'fa-list-alt',
                'level'       => 3,
                'route'       => 'audits',
                'acl'         => 'audits-all',
                'group'       => 'Sistema',
                'order'       => 5,
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
