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
        $hoje = Carbon::now();
        $mes_atual = ucfirst($hoje->locale('pt-BR')->isoFormat('MMMM'));

        $mensais = Conta::orderBy('vencimento', 'asc')
            ->where('user_id', auth()->id())
            ->whereYear('vencimento', $hoje->format('Y'))
            ->whereMonth('vencimento', $hoje->format('m'))
            ->get();

        $total_mensal = $mensais->sum('valor');

        $total_aberto = Conta::orderBy('vencimento', 'asc')
            ->where('user_id', auth()->id())
            ->whereNull('data_pagamento')
            ->whereYear('vencimento', $hoje->format('Y'))
            ->whereMonth('vencimento', $hoje->format('m'))
            ->sum('valor');

        $total_pago = Conta::orderBy('vencimento', 'asc')
            ->where('user_id', auth()->id())
            ->whereNotNull('data_pagamento')
            ->whereYear('vencimento', $hoje->format('Y'))
            ->whereMonth('vencimento', $hoje->format('m'))
            ->sum('valor');

        if ($hoje->dayOfWeek === Carbon::FRIDAY) {
            $devendo = Conta::orderBy('vencimento', 'asc')
                ->where('user_id', auth()->id())
                ->whereNull('data_pagamento')
                ->where(function ($query) use ($hoje) {
                    $query->where('vencimento', '<', $hoje->format('Y-m-d'))
                        ->orWhere('vencimento', $hoje->format('Y-m-d'))
                        ->orWhere('vencimento', $hoje->copy()->next(Carbon::SATURDAY))
                        ->orWhere('vencimento', $hoje->copy()->next(Carbon::SUNDAY));
                })
                ->get();
        } else {
            $devendo = Conta::orderBy('vencimento', 'asc')
                ->where('user_id', auth()->id())
                ->whereNull('data_pagamento')
                ->where(function ($query) use ($hoje) {
                    $query->where('vencimento', '<', $hoje->format('Y-m-d'))
                        ->orWhere('vencimento', $hoje->format('Y-m-d'));
                })
                ->get();
        }

        $total_vencido = $devendo->where('vencimento', '<', $hoje->format('Y-m-d'))->sum('valor');
        $total_vencendo = $devendo->where('vencimento', '>=', $hoje->format('Y-m-d'))->sum('valor');

        $grafico1 = [];

        foreach ($mensais as $mensal) {
            $valor_atual = $grafico1[$mensal->devedor->apelido]['valor'] ?? 0;
            $grafico1[$mensal->devedor->apelido]['valor'] = $valor_atual + $mensal->valor;
            $grafico1[$mensal->devedor->apelido]['cor'] = $mensal->devedor->cor;
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

        return view('home', compact('mes_atual', 'devendo', 'total_vencido', 'total_vencendo', 'total_aberto', 'total_pago', 'total_mensal', 'grafico1', 'grafico2'));
    }
}
