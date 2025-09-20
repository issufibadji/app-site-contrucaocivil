<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_profile_id')
                ->nullable()
                ->after('avatar_path')
                ->constrained('user_profiles')
                ->nullOnDelete();
        });

        $now = Carbon::now();
        $roleNames = DB::table('roles')->pluck('name', 'id');

        $userRoles = DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->orderBy('model_id')
            ->get()
            ->groupBy('model_id');

        foreach ($userRoles as $userId => $roles) {
            $isFirst = true;
            foreach ($roles as $role) {
                $roleId = (int) $role->role_id;
                $profileId = DB::table('user_profiles')->insertGetId([
                    'user_id'    => $userId,
                    'role_id'    => $roleId,
                    'name'       => ucfirst(str_replace(['_', '-'], ' ', $roleNames[$roleId] ?? 'Perfil '.$roleId)),
                    'is_default' => $isFirst,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($isFirst) {
                    DB::table('users')
                        ->where('id', $userId)
                        ->update(['current_profile_id' => $profileId]);
                    $isFirst = false;

                    DB::table('model_has_roles')
                        ->where('model_type', User::class)
                        ->where('model_id', $userId)
                        ->where('role_id', '!=', $roleId)
                        ->delete();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_profile_id');
        });

        Schema::dropIfExists('user_profiles');
    }
};
