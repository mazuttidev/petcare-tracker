<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 60);

            $table->enum('especie', ['Cão', 'Gato', 'Ave', 'Roedor', 'Réptil', 'Peixe', 'Outro']);

            $table->string('raca')->nullable();

            $table->date('data_nascimento')->nullable();

            $table->enum('sexo', ['Macho', 'Fêmea']);

            $table->boolean('castrado')->default(false);

            // Até 999,99 kg — cobre qualquer espécie doméstica com folga
            $table->decimal('peso_atual', 6, 2)->nullable();

            $table->string('cor')->nullable();

            // Microchip ISO 11784/11785 — 15 dígitos, mas aceita formatos variados
            $table->string('microchip', 30)->nullable()->unique();

            $table->text('observacoes')->nullable();

            $table->enum('status', ['Ativo', 'Falecido', 'Doado', 'Perdido'])->default('Ativo');

            $table->timestamps();

            // Exclusão lógica: registros deletados ficam na tabela com deleted_at preenchido
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
