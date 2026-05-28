<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adiciona user_id como nullable primeiro para não quebrar dados existentes
        Schema::table('pets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Associa pets já existentes ao primeiro usuário (dados de desenvolvimento)
        DB::table('pets')->whereNull('user_id')->update(['user_id' => 1]);

        // Agora que todos os registros têm dono, torna a coluna obrigatória
        Schema::table('pets', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
