<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Telefone;
use App\Models\Endereco;
use App\Rules\CpfOuCnpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
     *     summary="Listar fornecedores com paginação, filtragem e ordenação",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página para paginação",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Quantidade de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Critérios de filtragem, por exemplo, 'nome=fornecedor1'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Campo para ordenação, por exemplo, 'nome' ou '-nome' para decrescente",
     *         required=false,
     *         @OA\Schema(type="string", example="nome")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de fornecedores",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Fornecedor")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro no servidor"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Busca por nome ou outros campos
        $sortBy = $request->input('sort_by', 'nome'); // Campo para ordenação (padrão: 'nome')
        $sortDirection = $request->input('sort_direction', 'asc'); // Direção da ordenação (padrão: 'asc')
        $perPage = $request->input('per_page', 10); // Quantidade de itens por página (padrão: 10)

        // Query base com relacionamentos
        $query = Fornecedor::with(['telefones', 'enderecos']);

        // Adiciona filtro por busca (se aplicável)
        if ($search) {
            $query->where('nome', 'like', '%' . $search . '%')
                ->orWhere('documento', 'like', '%' . $search . '%');
        }

        // Aplica ordenação
        $query->orderBy($sortBy, $sortDirection);

        // Pagina os resultados
        $fornecedores = $query->paginate($perPage);

        // Retorna os dados em JSON ou para uma view
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
            'documento' => ['required', 'string', 'unique:fornecedores', new CpfOuCnpj],
            'ativo' => 'boolean',
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
            'documento' => ['required', 'string', 'unique:fornecedores,documento,' . $fornecedor->id, new CpfOuCnpj],
            'ativo' => 'boolean',
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

    /**
     * @OA\Get(
     *     path="/fornecedores/cnpj/{cnpj}",
     *     summary="Buscar um fornecedor pelo CNPJ",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="cnpj",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor nao encontrado"
     *     )
     * )
     * */
    public function buscarCnpj($cnpj)
    {
        $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

        if ($response->failed()) {
            return response()->json(['error' => 'CNPJ não encontrado'], 404);
        }

        return response()->json($response->json());
    }
}
