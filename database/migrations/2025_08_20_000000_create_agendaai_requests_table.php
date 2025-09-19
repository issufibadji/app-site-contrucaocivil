<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agendaai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('agendaai_clients');
            $table->foreignId('category_id')->constrained('agendaai_service_categories');
            $table->foreignId('service_id')->constrained('agendaai_services');
            $table->foreignId('professional_id')->nullable()->constrained('agendaai_professionals');
            $table->foreignId('schedule_availability_id')->nullable()->constrained('agendaai_schedule_availabilities');
            $table->integer('duration');
            $table->decimal('price',10,2)->nullable();
            $table->dateTime('scheduled_at');
            $table->string('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('priority', ['urgente','normal'])->default('normal');
            $table->text('description')->nullable();
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendaai_requests');
    }
};
