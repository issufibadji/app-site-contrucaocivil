<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('zip_code', 20)->nullable();
            $table->string('street', 150)->nullable();
            $table->string('number', 20)->nullable();
            $table->string('complement', 120)->nullable();
            $table->string('district', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 5)->nullable();
            $table->string('country', 120)->default('Brasil');
            $table->boolean('is_international')->default(false);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
