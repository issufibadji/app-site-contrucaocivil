<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agendaai_schedule_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')
                  ->constrained('agendaai_schedules')
                  ->onDelete('cascade');
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendaai_schedule_availabilities');
    }
};
