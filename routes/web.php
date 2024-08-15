<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagamentosController;

Route::get('/', [PagamentosController::class, 'index'])->name('dashboard');

Route::prefix('pagamentos')->group(function () {
    Route::get('/', [PagamentosController::class, 'listar'])->name('pagamentos.listar');
    Route::post('/processamento', [PagamentosController::class, 'processamento'])->name('pagamentos.processamento');
    Route::get('/finalizar', [PagamentosController::class, 'finalizar'])->name('pagamentos.finalizar');
    Route::get('/qr-code', [PagamentosController::class, 'showQrCode']);
    Route::delete('/pagamentos/{id}/delete', [PagamentosController::class, 'destroy']);
});
