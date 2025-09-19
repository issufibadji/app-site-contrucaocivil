<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('professional_id');
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('appointment_id')
                ->references('id')
                ->on('agendaai_appointments')
                ->onDelete('cascade');
            $table->foreign('client_id')
                ->references('id')
                ->on('agendaai_clients');
            $table->foreign('professional_id')
                ->references('id')
                ->on('agendaai_professionals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};
