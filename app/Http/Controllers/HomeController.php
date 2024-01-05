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
        $hoje = Carbon::today();
        $diaDaSemana = $hoje->dayOfWeek;

        if ($diaDaSemana === Carbon::FRIDAY) {
            $contas_vencendo = Conta::orderBy('vencimento', 'asc')
                ->whereNull('data_pagamento')
                ->where('user_id', auth()->id())
                ->where(function ($query) use ($hoje) {
                    $query->whereDate('vencimento', $hoje);
                    $query->orWhere(function ($query) use ($hoje) {
                        $query->whereDate('vencimento', $hoje->copy()->next(Carbon::SATURDAY));
                    });
                    $query->orWhere(function ($query) use ($hoje) {
                        $query->whereDate('vencimento', $hoje->copy()->next(Carbon::SUNDAY));
                    });
                })
                ->get();
        } else {
            $contas_vencendo = Conta::orderBy('vencimento', 'asc')
                ->whereNull('data_pagamento')
                ->whereDate('vencimento', $hoje)
                ->where('user_id', auth()->id())
                ->get();
        }

        $contas_vencidas = Conta::orderBy('vencimento', 'asc')
            ->whereNull('data_pagamento')
            ->whereDate('vencimento', '<', $hoje)
            ->where('user_id', auth()->id())
            ->get();

        $vencendo_hoje = $contas_vencendo->contains(function ($conta) use ($hoje) {
            return Carbon::parse($conta->vencimento)->toDateString() === $hoje->toDateString();
        });

        $vencendo_sabado = $contas_vencendo->contains(function ($conta) {
            return Carbon::parse($conta->vencimento)->dayOfWeek === Carbon::SATURDAY;
        });

        $vencendo_domingo = $contas_vencendo->contains(function ($conta) {
            return Carbon::parse($conta->vencimento)->dayOfWeek === Carbon::SUNDAY;
        });

        $vencendo = '';

        if ($vencendo_hoje) {
            $vencendo .= 'hoje';
        }

        if ($vencendo_sabado) {
            if ($vencendo !== '') {
                $vencendo .= $vencendo_domingo ? ', ' : ' e ';
            }
            $vencendo .= 'sábado';
        }

        if ($vencendo_domingo) {
            if ($vencendo !== '') {
                $vencendo .= ' e ';
            }
            $vencendo .= 'domingo';
        }

        $vencidas_vencendo = $contas_vencidas->merge($contas_vencendo);

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

        return view('home', compact('vencidas_vencendo', 'contas_vencidas', 'contas_vencendo', 'vencendo', 'pagos', 'abertos', 'total', 'grafico1', 'grafico2'));
    }
}
