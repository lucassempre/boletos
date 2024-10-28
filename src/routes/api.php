<?php

use App\Infrastructure\Controllers\{BoletoController, FileController, OperacaoController};
use Illuminate\Support\Facades\Route;

Route::post('/files/upload', [FileController::class, 'upload']);
Route::get('/files/{uuid}/download', [FileController::class, 'download']);
Route::delete('/files/{uuid}', [FileController::class, 'delete']);
Route::get('/files', [FileController::class, 'list']);

Route::post('/processar/{arquivo_uuid}', [OperacaoController::class, 'processar']);

Route::get('/boletos/', [BoletoController::class, 'list']);
Route::get('/boletos/{uuid}', [BoletoController::class, 'show']);
Route::get('/boletos/status/{status}', [BoletoController::class, 'status']);
