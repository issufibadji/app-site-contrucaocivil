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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'establishment_id')) {
                $table->dropForeign(['establishment_id']);
                $table->dropColumn('establishment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'establishment_id')) {
                $table->unsignedBigInteger('establishment_id')->nullable()->after('id');
                $table->foreign('establishment_id')->references('id')->on('agendaai_establishments')->onDelete('set null');
            }
        });
    }
};
