<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('rua');  // Rua
            $table->string('numero');  // Número do imóvel
            $table->string('complemento')->nullable();  // Complemento
            $table->string('bairro');  // Bairro
            $table->string('cidade');  // Cidade
            $table->string('uf');  // Estado
            $table->string('cep');  // CEP
            $table->timestamps();
        });
    
        // Tabela intermediária entre fornecedores e endereços
        Schema::create('fornecedor_endereco', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->foreignId('endereco_id')->constrained('enderecos')->onDelete('cascade');
            $table->timestamps();
        });
    }    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
