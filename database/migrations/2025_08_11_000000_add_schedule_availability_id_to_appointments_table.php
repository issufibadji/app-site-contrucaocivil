<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agendaai_appointments', function (Blueprint $table) {
            $table->foreignId('schedule_availability_id')
                  ->nullable()
                  ->constrained('agendaai_schedule_availabilities')
                  ->after('client_id');
        });
    }

    public function down(): void
    {
        Schema::table('agendaai_appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_availability_id');
        });
    }
};
