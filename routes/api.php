<?php
  
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\TelefoneController;
use App\Http\Controllers\EnderecoController;

Route::apiResource('fornecedores', FornecedorController::class);
Route::apiResource('telefones', TelefoneController::class);
Route::apiResource('enderecos', EnderecoController::class);
Route::get('/buscar-cnpj/{cnpj}', [FornecedorController::class, 'buscarCnpj']);
