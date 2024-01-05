<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Conta;
use App\Models\Devedor;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contas_vencendo = Conta::orderByRaw("CASE WHEN vencimento = '" . Carbon::today()->toDateString() . "' THEN 1 ELSE 2 END")
            ->orderBy('vencimento', 'asc')
            ->orderBy('id', 'desc')
            ->where('user_id', auth()->id())
            ->whereDate('vencimento', Carbon::today()->toDateString())->get();

        $abertos = Conta::whereNull('data_pagamento')
            ->whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->where('user_id', auth()->id())
            ->sum('valor');

        $pagos = Conta::whereNotNull('data_pagamento')
            ->whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->where('user_id', auth()->id())
            ->sum('valor');

        $total = Conta::whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->where('user_id', auth()->id())
            ->sum('valor');

        $_contas = Conta::whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->where('user_id', auth()->id())
            ->get();

        $grafico1 = [];

        foreach ($_contas as $_conta) {
            $valor_atual = $grafico1[$_conta->devedor->apelido]['valor'] ?? 0;
            $grafico1[$_conta->devedor->apelido]['valor'] = $valor_atual + $_conta->valor;
            $grafico1[$_conta->devedor->apelido]['cor'] = $_conta->devedor->cor;
        }

        $devedores = Devedor::where('user_id', auth()->id())->pluck('apelido', 'id')->all();

        $grafico2 = [];

        for ($i = 2; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $grafico2[$data->locale('pt-BR')->isoFormat('MMMM[/]YYYY')] = [];

            foreach ($devedores as $key => $devedor) {
                $cor = Devedor::find($key)->cor;

                $contas = Conta::where('devedor_id', $key)
                    ->whereYear('vencimento', $data->year)
                    ->whereMonth('vencimento', $data->month)
                    ->where('user_id', auth()->id())
                    ->get();

                $valor = $contas->sum('valor');

                $grafico2[$data->locale('pt-BR')->isoFormat('MMMM[/]YYYY')][$devedor] = [
                    'valor' => $valor,
                    'cor' => $cor,
                ];
            }
        }

        return view('home', compact('contas_vencendo', 'pagos', 'abertos', 'total', 'grafico1', 'grafico2'));
    }
}
