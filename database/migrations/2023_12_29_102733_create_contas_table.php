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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->unsignedBigInteger('devedor_id')->nullable();
            $table->foreign('devedor_id')->references('id')->on('devedores')->onDelete('cascade');
            $table->string('descricao')->nullable();
            $table->string('cobranca')->nullable();
            $table->date('vencimento')->nullable();
            $table->decimal('valor', 18, 2)->default(0);
            $table->date('data_pagamento')->nullable();
            $table->decimal('valor_pago', 18, 2)->default(0);
            $table->unsignedBigInteger('formapagamento_id')->nullable();
            $table->foreign('formapagamento_id')->references('id')->on('formas_pagamentos')->onDelete('cascade');
            $table->unsignedBigInteger('contabancaria_pagamento_id')->nullable();
            $table->foreign('contabancaria_pagamento_id')->references('id')->on('contas_bancarias')->onDelete('cascade');
            $table->string('comprovante')->nullable();
            $table->boolean('recorrente')->default(FALSE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
