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
        Schema::create('telefones', function (Blueprint $table) {
            $table->id();
            $table->string('telefone');  // Número de telefone
            $table->timestamps();
        });
    
        // Tabela intermediária entre fornecedores e telefones
        Schema::create('fornecedor_telefone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->foreignId('telefone_id')->constrained('telefones')->onDelete('cascade');
            $table->timestamps();
        });
    }    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telefones');
    }
};
