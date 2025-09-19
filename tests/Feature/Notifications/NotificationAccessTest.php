<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class NotificationAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createPermission(): Permission
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return Permission::firstOrCreate(['name' => 'notification-all']);
    }

    public function test_user_with_permission_can_access_notification_pages(): void
    {
        $this->createPermission();

        $user = User::factory()->create();
        $user->givePermissionTo('notification-all');

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('notifications.create'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_access_notification_pages(): void
    {
        $this->createPermission();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('notifications.create'))
            ->assertForbidden();
    }
}
