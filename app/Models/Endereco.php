<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Endereco",
 *     type="object",
 *     required={"rua", "numero", "bairro", "cidade", "uf", "cep"},
 *     @OA\Property(property="id", type="integer", description="ID do endereço"),
 *     @OA\Property(property="rua", type="string", description="Rua do endereço"),
 *     @OA\Property(property="numero", type="string", description="Número do endereço"),
 *     @OA\Property(property="complemento", type="string", description="Complemento do endereço"),
 *     @OA\Property(property="bairro", type="string", description="Bairro do endereço"),
 *     @OA\Property(property="cidade", type="string", description="Cidade do endereço"),
 *     @OA\Property(property="uf", type="string", description="UF do endereço"),
 *     @OA\Property(property="cep", type="string", description="CEP do endereço"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Data de exclusão")
 * )
 */
class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = [
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
    ];

    // Relação many-to-many com fornecedores
    public function fornecedores()
    {
        return $this->belongsToMany(Fornecedor::class, 'fornecedor_endereco');
    }
}
