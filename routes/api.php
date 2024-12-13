<?php

/**
 * @OA\Info(
 *     title="Fornecedores API",
 *     description="Esta é a documentação da minha API de fornecedores.",
 *     version="1.0.0"
 * )
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\TelefoneController;
use App\Http\Controllers\EnderecoController;

Route::apiResource('fornecedores', FornecedorController::class);
Route::apiResource('telefones', TelefoneController::class);
Route::apiResource('enderecos', EnderecoController::class);
