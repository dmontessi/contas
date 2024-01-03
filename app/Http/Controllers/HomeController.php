<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Conta;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contas = Conta::orderByRaw("CASE WHEN vencimento = '" . Carbon::today()->toDateString() . "' THEN 1 ELSE 2 END")
            ->orderBy('vencimento', 'asc')
            ->orderBy('id', 'desc')
            ->whereDate('vencimento', Carbon::today()->toDateString())->get();

        $abertos = Conta::whereNull('data_pagamento')
            ->whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->sum('valor');

        $pagos = Conta::whereNotNull('data_pagamento')
            ->whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->sum('valor');

        $total = Conta::whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->sum('valor');

        $_contas = Conta::whereYear('vencimento', Carbon::today()->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->format('m'))
            ->get();

        $grafico = [];
        $mesatual = 0;

        foreach ($_contas as $_conta) {
            $valor_atual = $grafico[$_conta->devedor->apelido]['valor'] ?? 0;
            $grafico[$_conta->devedor->apelido]['valor'] = $valor_atual + $_conta->valor;

            $mesatual = $mesatual + $_conta->valor;
        }

        $grafico2 = [];

        $mesretrasado_label = ucfirst(Carbon::now()->subMonths(2)->locale('pt-BR')->isoFormat('MMMM[/]YYYY'));
        $mesretrasado = Conta::whereYear('vencimento', Carbon::today()->subMonths(2)->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->subMonths(2)->format('m'))
            ->sum('valor');

        $grafico2[$mesretrasado_label]['valor'] = $mesretrasado;

        $mespassado_label = ucfirst(Carbon::now()->subMonths(1)->locale('pt-BR')->isoFormat('MMMM[/]YYYY'));
        $mespassado = Conta::whereYear('vencimento', Carbon::today()->subMonths(1)->format('Y'))
            ->whereMonth('vencimento', Carbon::today()->subMonths(1)->format('m'))
            ->sum('valor');

        $grafico2[$mespassado_label]['valor'] = $mespassado;

        $mesatual_label = ucfirst(Carbon::now()->locale('pt-BR')->isoFormat('MMMM[/]YYYY'));
        $mesatual;

        $grafico2[$mesatual_label]['valor'] = $mesatual;

        return view('home', compact('contas', 'pagos', 'abertos', 'total', 'grafico', 'grafico2'));
    }
}
