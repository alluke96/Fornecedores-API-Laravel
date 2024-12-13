<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Fornecedor;

class FornecedorApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_listar_fornecedores()
    {
        Fornecedor::factory()->count(5)->create();

        $response = $this->getJson('/api/fornecedores');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function pode_criar_fornecedor_valido()
    {
        $dados = [
            'nome' => 'Fornecedor Teste',
            'documento' => '12345678000195',
            // 'telefones' => [
            //     ['telefone' => '11999999999'],
            // ],
        ];

        $response = $this->postJson('/api/fornecedores', $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nome' => 'Fornecedor Teste']);
    }

    /** @test */
    public function nao_pode_criar_fornecedor_com_dados_invalidos()
    {
        $dados = [
            'nome' => '',
            'documento' => 'inválido',
            'email' => 'email_invalido',
            'telefone' => ''
        ];

        $response = $this->postJson('/api/fornecedores', $dados);

        $response->assertStatus(422);
    }
}