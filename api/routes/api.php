<?php

use App\Http\Controllers\DeputadoController;
use App\Http\Controllers\PartidoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DespesaController;

Route::prefix('deputados')->group(function () {
    // Lista todos os deputados (paginado)
    Route::get('/', [DeputadoController::class, 'index']);

    // Busca deputado por ID
    Route::get('/buscar/{id}', [DeputadoController::class, 'buscaDeputadoPorId']);

    // Busca deputados por nome (parcial)
    Route::get('/buscar/nome/{nome}', [DeputadoController::class, 'buscarPorNome']);


});

Route::prefix('partidos')->group(function () {
    Route::get('/', [PartidoController::class, 'index']);
    Route::get('/buscar/id/{id}', [PartidoController::class, 'buscaPartidoPorId']);
    Route::get('/buscar/nome/{nome}', [PartidoController::class, 'buscaPorNome']);
    Route::get('/buscar/sigla/{sigla}', [PartidoController::class, 'buscaPorSigla']);
});

Route::prefix('despesas')->group(function () {
    Route::get('/findAll/{deputado_id}', [DespesaController::class, 'listarTodos']);
});
