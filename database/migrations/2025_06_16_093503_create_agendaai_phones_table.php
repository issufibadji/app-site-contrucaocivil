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
      Schema::create('agendaai_phones', function (Blueprint $table) {
          $table->id();
          $table->string('ddi')->nullable();
          $table->string('ddd')->nullable();
          $table->string('phone')->nullable();
          $table->foreignId('professional_id')->constrained('agendaai_professionals')->onDelete('cascade');
          $table->foreignId('establishment_id')->constrained('agendaai_establishments')->onDelete('cascade');

          $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendaai_phones');
    }
};
