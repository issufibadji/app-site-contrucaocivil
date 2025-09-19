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
        Schema::table('agendaai_professionals', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_professionals', 'commission')) {
                $table->dropColumn('commission');
            }

            if (Schema::hasColumn('agendaai_professionals', 'establishment_id')) {
                $table->dropForeign(['establishment_id']);
                $table->dropColumn('establishment_id');
            }

            if (! Schema::hasColumn('agendaai_professionals', 'professions')) {
                $table->json('professions')->nullable();
            }

            if (! Schema::hasColumn('agendaai_professionals', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendaai_professionals', function (Blueprint $table) {
            if (Schema::hasColumn('agendaai_professionals', 'professions')) {
                $table->dropColumn('professions');
            }

            if (Schema::hasColumn('agendaai_professionals', 'description')) {
                $table->dropColumn('description');
            }

            if (! Schema::hasColumn('agendaai_professionals', 'commission')) {
                $table->decimal('commission', 5, 2)->default(0);
            }

            if (! Schema::hasColumn('agendaai_professionals', 'establishment_id')) {
                $table->foreignId('establishment_id')
                      ->constrained('agendaai_establishments')
                      ->onDelete('cascade');
            }
        });
    }
};
