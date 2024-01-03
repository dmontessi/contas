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

        $abertos = Conta::whereNull('data_pagamento')->whereDate('vencimento', Carbon::today()->toDateString())->sum('valor');
        $pagos = Conta::whereNotNull('data_pagamento')->whereDate('vencimento', Carbon::today()->toDateString())->sum('valor');
        $total = Conta::whereDate('vencimento', Carbon::today()->toDateString())->sum('valor');

        return view('home', compact('contas', 'pagos', 'abertos', 'total'));
    }
}
