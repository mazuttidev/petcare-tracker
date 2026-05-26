<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesagens', function (Blueprint $table) {
            $table->id();

            // cascadeOnDelete() garante integridade referencial em hard-deletes (forceDelete).
            // Soft-delete do pet não dispara a cascade do banco; se quiser propagar a exclusão
            // lógica para pesagens, use um observer no model Pet.
            $table->foreignId('pet_id')
                  ->constrained('pets')
                  ->cascadeOnDelete();

            $table->date('data');

            // Faixa válida: 0.01 kg (passarinho) a 200.00 kg (cão gigante)
            $table->decimal('peso_kg', 5, 2);

            $table->enum('fonte', ['Manual', 'Balança', 'Clínica'])->default('Manual');

            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesagens');
    }
};
