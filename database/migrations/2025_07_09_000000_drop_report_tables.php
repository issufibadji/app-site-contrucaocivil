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
        Schema::dropIfExists('report_layouts');
        Schema::dropIfExists('report_fields');
        Schema::dropIfExists('report_relationships');
        Schema::dropIfExists('report_models');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('report_models', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('table_name');
            $table->string('display_name');
            $table->string('primary_key');
            $table->json('filters')->nullable();
            $table->timestamps();
        });

        Schema::create('report_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_model_id')->constrained('report_models')->onDelete('cascade');
            $table->string('related_table');
            $table->string('foreign_key');
            $table->string('local_key');
            $table->timestamps();
        });

        Schema::create('report_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_model_id')->constrained('report_models')->onDelete('cascade');
            $table->string('column');
            $table->string('label');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('report_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_model_id')->constrained('report_models')->onDelete('cascade');
            $table->json('layout')->nullable();
            $table->timestamps();
        });
    }
};
