<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título (obrigatório)
            $table->text('description')->nullable(); // Descrição (opcional)
            $table->boolean('is_completed')->default(false); // Status: 0=pendente / 1=concluída
            $table->timestamps(); // Data de criação (created_at e updated_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
