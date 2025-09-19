<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendaai_services', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_services', 'provider_id')) {
                $table->dropForeign(['provider_id']);
                $table->renameColumn('provider_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('agendaai_services', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_services', 'user_id') && !Schema::hasColumn('agendaai_services', 'provider_id')) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'provider_id');
                $table->foreign('provider_id')->references('id')->on('users')->cascadeOnDelete();
            }
        });
    }
};
