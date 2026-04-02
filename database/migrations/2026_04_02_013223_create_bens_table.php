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
        Schema::create('bens', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('numero_patrimonio', 50)->unique()->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->text('descricao')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias_bem');
            $table->foreignId('unidade_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('sala_id')->nullable()->constrained('salas')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->date('data_aquisicao')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->enum('status', ['ativo', 'inativo', 'manutencao', 'descartado'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bens');
    }
};
