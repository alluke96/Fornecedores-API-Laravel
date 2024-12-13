<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Telefone;
use App\Models\Endereco;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Fornecedores",
 *     description="Gerenciamento dos fornecedores"
 * )
 */
class FornecedorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/fornecedores",
     *     summary="Listar todos os fornecedores",
     *     tags={"Fornecedores"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de fornecedores com telefones e endereços",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Fornecedor"))
     *     )
     * )
     */
    public function index()
    {
        // Retorna todos os fornecedores com telefones e endereços
        $fornecedores = Fornecedor::with(['telefones', 'enderecos'])->paginate(10);
        return response()->json($fornecedores);
    }

    /**
     * @OA\Post(
     *     path="/fornecedores",
     *     summary="Criar um novo fornecedor",
     *     tags={"Fornecedores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fornecedor criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|unique:fornecedores',
            'ativo' => 'required|boolean',
        ]);

        $fornecedor = Fornecedor::create($request->only(['nome', 'documento', 'ativo']));

        if ($request->has('telefones')) {
            foreach ($request->telefones as $telefone) {
                Telefone::create([
                    'fornecedor_id' => $fornecedor->id,
                    'telefone' => $telefone,
                ]);
            }
        }

        if ($request->has('enderecos')) {
            foreach ($request->enderecos as $endereco) {
                Endereco::create([
                    'fornecedor_id' => $fornecedor->id,
                    'rua' => $endereco['rua'],
                    'numero' => $endereco['numero'],
                    'complemento' => $endereco['complemento'] ?? null,
                    'bairro' => $endereco['bairro'],
                    'cidade' => $endereco['cidade'],
                    'uf' => $endereco['uf'],
                    'cep' => $endereco['cep'],
                ]);
            }
        }

        return response()->json($fornecedor, 201);
    }

    /**
     * @OA\Get(
     *     path="/fornecedores/{id}",
     *     summary="Mostrar um fornecedor específico",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $fornecedor = Fornecedor::with(['telefones', 'enderecos'])->findOrFail($id);
        return response()->json($fornecedor);
    }

    /**
     * @OA\Put(
     *     path="/fornecedores/{id}",
     *     summary="Atualizar um fornecedor",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|unique:fornecedores,documento,' . $fornecedor->id,
            'ativo' => 'required|boolean',
        ]);

        $fornecedor->update($request->only(['nome', 'documento', 'ativo']));

        return response()->json($fornecedor);
    }

    /**
     * @OA\Delete(
     *     path="/fornecedores/{id}",
     *     summary="Deletar um fornecedor",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fornecedor excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->delete();
        return response()->json(['message' => 'Fornecedor excluído com sucesso.']);
    }
}
