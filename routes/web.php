<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('fornecedores', App\Http\Controllers\FornecedorController::class)
    ->parameters(['fornecedores' => 'fornecedor']);

Route::resource('devedores', App\Http\Controllers\DevedorController::class)
    ->parameters(['devedores' => 'devedor']);

Route::put('/contas/{conta}/paycancel', [App\Http\Controllers\ContaController::class, 'paycancel'])->name('contas.paycancel');
Route::get('contas/{conta}/pay', [App\Http\Controllers\ContaController::class, 'pay'])->name('contas.pay');
Route::get('/contas/pagas', [App\Http\Controllers\ContaController::class, 'pagas'])->name('contas.pagas');
Route::get('/contas/pendentes', [App\Http\Controllers\ContaController::class, 'pendentes'])->name('contas.pendentes');

Route::resource('contas', App\Http\Controllers\ContaController::class)
    ->parameters(['contas' => 'conta']);

Route::resource('bancos', App\Http\Controllers\BancoController::class)
    ->parameters(['bancos' => 'banco']);

Route::resource('tiposchaves', App\Http\Controllers\TipoChaveController::class)
    ->parameters(['tiposchaves' => 'tipochave']);

Route::resource('contasbancarias', App\Http\Controllers\ContaBancariaController::class)
    ->parameters(['contasbancarias' => 'contabancaria']);

Route::resource('formaspagamentos', App\Http\Controllers\FormaPagamentoController::class)
    ->parameters(['formaspagamentos' => 'formapagamento']);
