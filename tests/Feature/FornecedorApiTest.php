<?php

namespace Tests\Feature;

use App\Models\Fornecedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FornecedorApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_fornecedor()
    {
        $response = $this->postJson('/api/fornecedores', [
            'nome' => 'Fornecedor A',
            'documento' => '12345678901234',
            'ativo' => true,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'nome' => 'Fornecedor A',
            'documento' => '12345678901234',
            'ativo' => true,
        ]);
    }

    /** @test */
    public function it_can_list_fornecedores()
    {
        Fornecedor::factory()->create();

        $response = $this->getJson('/api/fornecedores');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_update_a_fornecedor()
    {
        $fornecedor = Fornecedor::factory()->create();

        $response = $this->putJson('/api/fornecedores/' . $fornecedor->id, [
            'nome' => 'Fornecedor Atualizado',
            'documento' => '98765432109876',
            'ativo' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'nome' => 'Fornecedor Atualizado',
            'documento' => '98765432109876',
            'ativo' => false,
        ]);
    }

    /** @test */
    public function it_can_delete_a_fornecedor()
    {
        $fornecedor = Fornecedor::factory()->create();

        $response = $this->deleteJson('/api/fornecedores/' . $fornecedor->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('fornecedores', [
            'id' => $fornecedor->id
        ]);
    }
}
