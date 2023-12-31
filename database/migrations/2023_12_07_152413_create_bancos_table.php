<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->nullable();
            $table->string('nome')->nullable();
            $table->timestamps();
        });

        DB::table('bancos')->insert([
            ['codigo' => 001, 'nome' => 'Banco do Brasil S.A.'],
            ['codigo' => 033, 'nome' => 'Banco Santander (Brasil) S.A.'],
            ['codigo' => 104, 'nome' => 'Caixa Econômica Federal'],
            ['codigo' => 237, 'nome' => 'Banco Bradesco S.A.'],
            ['codigo' => 260, 'nome' => 'NU Pagamentos S.A.'],
            ['codigo' => 301, 'nome' => 'BPP Instituição de Pagamento S.A.'],
            ['codigo' => 336, 'nome' => 'Banco C6 S.A.'],
            ['codigo' => 341, 'nome' => 'Banco Itaú S.A.'],
            ['codigo' => 380, 'nome' => 'Picpay Servicos S.A.'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
