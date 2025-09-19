<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('agendaai_service_categories', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->string('name');
        $table->timestamps();
    });

    // FK opcional: se você quiser um relacionamento 1-N
    Schema::table('agendaai_services', function (Blueprint $table) {
        $table->foreignId('category_id')
              ->nullable()
              ->constrained('agendaai_service_categories')
              ->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendaai_service_categories');
    }
};
