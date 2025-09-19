<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendaai_address_establishments', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_address_establishments', 'establishment_id')) {
                $table->dropForeign(['establishment_id']);
                $table->renameColumn('establishment_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
        });

        Schema::table('agendaai_phones', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_phones', 'establishment_id')) {
                $table->dropForeign(['establishment_id']);
                $table->renameColumn('establishment_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('agendaai_address_establishments', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_address_establishments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'establishment_id');
                $table->foreign('establishment_id')->references('id')->on('agendaai_establishments')->cascadeOnDelete();
            }
        });

        Schema::table('agendaai_phones', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_phones', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'establishment_id');
                $table->foreign('establishment_id')->references('id')->on('agendaai_establishments')->cascadeOnDelete();
            }
        });
    }
};
