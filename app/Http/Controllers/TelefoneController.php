<?php

namespace App\Http\Controllers;

use App\Models\Telefone;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Telefones",
 *     description="Gerenciamento dos telefones"
 * )
 */
class TelefoneController extends Controller
{
    /**
     * @OA\Get(
     *     path="/telefones",
     *     summary="Listar todos os telefones",
     *     tags={"Telefones"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de telefones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Telefone"))
     *     )
     * )
     */
    public function index()
    {
        return Telefone::all();
    }

    /**
     * @OA\Post(
     *     path="/telefones",
     *     summary="Criar um novo telefone",
     *     tags={"Telefones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Telefone")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Telefone criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Telefone")
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
            'numero' => 'required|string|max:15',
        ]);

        $telefone = Telefone::create($validated);
        return response()->json($telefone, 201);
    }

    /**
     * @OA\Get(
     *     path="/telefones/{id}",
     *     summary="Mostrar um telefone específico",
     *     tags={"Telefones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do telefone",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Telefone encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Telefone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Telefone não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $telefone = Telefone::find($id);

        if (!$telefone) {
            return response()->json(['message' => 'Telefone não encontrado'], 404);
        }

        return response()->json($telefone);
    }

    /**
     * @OA\Put(
     *     path="/telefones/{id}",
     *     summary="Atualizar um telefone",
     *     tags={"Telefones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do telefone",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Telefone")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Telefone atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Telefone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Telefone não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $telefone = Telefone::find($id);

        if (!$telefone) {
            return response()->json(['message' => 'Telefone não encontrado'], 404);
        }

        $validated = $request->validate([
            'numero' => 'sometimes|string|max:15',
        ]);

        $telefone->update($validated);
        return response()->json($telefone);
    }

    /**
     * @OA\Delete(
     *     path="/telefones/{id}",
     *     summary="Deletar um telefone",
     *     tags={"Telefones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do telefone",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Telefone deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Telefone deletado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Telefone não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $telefone = Telefone::find($id);

        if (!$telefone) {
            return response()->json(['message' => 'Telefone não encontrado'], 404);
        }

        $telefone->delete();
        return response()->json(['message' => 'Telefone deletado com sucesso']);
    }
}
