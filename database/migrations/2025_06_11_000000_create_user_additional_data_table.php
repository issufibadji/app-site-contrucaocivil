<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_additional_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cpf', 20)->nullable();
            $table->string('rg', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('secondary_phone', 30)->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_additional_data');
    }
};
