<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Fornecedor",
 *     type="object",
 *     required={"nome", "documento", "ativo"},
 *     @OA\Property(property="id", type="integer", description="ID do fornecedor"),
 *     @OA\Property(property="nome", type="string", description="Nome do fornecedor"),
 *     @OA\Property(property="documento", type="string", description="Documento do fornecedor"),
 *     @OA\Property(property="ativo", type="boolean", description="Status de atividade do fornecedor"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Data de exclusão (soft delete)")
 * )
 */
class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'documento',
        'ativo',
    ];

    /**
     * Relação many-to-many com telefones
     *
     * @OA\Property(property="telefones", type="array", @OA\Items(ref="#/components/schemas/Telefone"))
     */
    public function telefones()
    {
        return $this->belongsToMany(Telefone::class, 'fornecedor_telefone');
    }

    /**
     * Relação many-to-many com endereços
     *
     * @OA\Property(property="enderecos", type="array", @OA\Items(ref="#/components/schemas/Endereco"))
     */
    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'fornecedor_endereco');
    }
}
