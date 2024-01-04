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
        Schema::create('contas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->unsignedBigInteger('devedor_id')->nullable();
            $table->foreign('devedor_id')->references('id')->on('devedores')->onDelete('cascade');
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->unsignedBigInteger('tipochave_id')->nullable();
            $table->foreign('tipochave_id')->references('id')->on('tipos_chaves')->onDelete('cascade');
            $table->string('chave_pix')->nullable();
            $table->boolean('ativa')->default(TRUE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_bancarias');
    }
};
