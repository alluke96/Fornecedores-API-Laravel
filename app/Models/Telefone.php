<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Telefone",
 *     type="object",
 *     required={"telefone"},
 *     @OA\Property(property="id", type="integer", description="ID do telefone"),
 *     @OA\Property(property="telefone", type="string", description="Número de telefone"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Data de exclusão")
 * )
 */
class Telefone extends Model
{
    use HasFactory;

    protected $table = 'telefones';

    protected $fillable = [
        'telefone',
    ];

    // Relação many-to-many com fornecedores
    public function fornecedores()
    {
        return $this->belongsToMany(Fornecedor::class, 'fornecedor_telefone');
    }
}
