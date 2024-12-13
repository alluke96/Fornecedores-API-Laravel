<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Endereços",
 *     description="Gerenciamento dos endereços"
 * )
 */
class EnderecoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/enderecos",
     *     summary="Listar todos os endereços",
     *     tags={"Endereços"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de endereços",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Endereco"))
     *     )
     * )
     */
    public function index()
    {
        return Endereco::all();
    }

    /**
     * @OA\Post(
     *     path="/enderecos",
     *     summary="Criar um novo endereço",
     *     tags={"Endereços"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Endereço criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            'cep' => 'required|string|max:10',
        ]);

        $endereco = Endereco::create($validated);
        return response()->json($endereco, 201);
    }

    /**
     * @OA\Get(
     *     path="/enderecos/{id}",
     *     summary="Mostrar um endereço específico",
     *     tags={"Endereços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do endereço",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do endereço",
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado",
     *     )
     * )
     */
    public function show($id)
    {
        $endereco = Endereco::find($id);

        if (!$endereco) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        return response()->json($endereco);
    }

    /**
     * @OA\Put(
     *     path="/enderecos/{id}",
     *     summary="Atualizar um endereço",
     *     tags={"Endereços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do endereço",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço atualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado",
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $endereco = Endereco::find($id);

        if (!$endereco) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        $validated = $request->validate([
            'rua' => 'sometimes|string|max:255',
            'numero' => 'sometimes|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'sometimes|string|max:255',
            'cidade' => 'sometimes|string|max:255',
            'uf' => 'sometimes|string|max:2',
            'cep' => 'sometimes|string|max:10',
        ]);

        $endereco->update($validated);
        return response()->json($endereco);
    }

    /**
     * @OA\Delete(
     *     path="/enderecos/{id}",
     *     summary="Deletar um endereço",
     *     tags={"Endereços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do endereço",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço deletado",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado",
     *     )
     * )
     */
    public function destroy($id)
    {
        $endereco = Endereco::find($id);

        if (!$endereco) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        $endereco->delete();
        return response()->json(['message' => 'Endereço deletado com sucesso'], 200);
    }
}
