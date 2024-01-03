<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Conta;
use App\Models\Devedor;
use App\Models\Fornecedor;
use App\Models\ContaBancaria;
use App\Models\FormaPagamento;

class ContaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $descricao = $request->input('descricao');

        $contas = Conta::orderByRaw("CASE WHEN vencimento = '" . Carbon::today()->toDateString() . "' THEN 1 ELSE 2 END")
            ->where(function ($query) use ($descricao) {
                if ($descricao) {
                    $query->where('descricao', 'LIKE', "%$descricao%");
                }
            })->paginate(15);

        $contas->appends(['descricao' => $descricao]);

        return view('contas.index', compact('contas'));
    }

    public function create()
    {
        $devedores = Devedor::all();
        $fornecedores = Fornecedor::all();
        return view('contas.create', compact('devedores', 'fornecedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'devedor_id' => 'nullable|exists:devedores,id',
            'cobranca' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'descricao' => 'nullable|string',
            'vencimento' => 'nullable|date',
            'valor' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
            'valor_pago' => 'nullable|numeric|min:0',
            'formapagamento_id' => 'nullable|exists:formas_pagamentos,id',
            'contabancaria_pagamento_id' => 'nullable|exists:contas_bancarias,id',
            'comprovante' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $conta = Conta::create($request->except('cobranca'));

        if ($request->hasFile('cobranca')) {

            if ($conta->cobranca) {
                $oldFilePath = public_path($conta->cobranca);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $path = public_path('arquivos/cobrancas');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileName = 'cobranca_' . $conta->id . '_' . now()->format('Ymd_His') . '.' . $request->file('cobranca')->getClientOriginalExtension();

            $request->file('cobranca')->move($path, $fileName);

            $conta->cobranca = 'arquivos/cobrancas/' . $fileName;
            $conta->save();
        }

        return redirect()->route('contas.index');
    }

    public function show(Conta $conta)
    {
        return view('contas.show', compact('conta'));
    }

    public function edit(Conta $conta)
    {
        $devedores = Devedor::all();
        $fornecedores = Fornecedor::all();
        $formaspagamentos = FormaPagamento::all();
        $contaspagamentos = ContaBancaria::all();
        return view('contas.edit', compact('conta', 'devedores', 'fornecedores', 'formaspagamentos', 'contaspagamentos'));
    }

    public function update(Request $request, Conta $conta)
    {
        $request->validate([
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'devedor_id' => 'nullable|exists:devedores,id',
            'cobranca' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'descricao' => 'nullable|string',
            'vencimento' => 'nullable|date',
            'valor' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
            'valor_pago' => 'nullable|numeric|min:0',
            'formapagamento_id' => 'nullable|exists:formas_pagamentos,id',
            'contabancaria_pagamento_id' => 'nullable|exists:contas_bancarias,id',
            'comprovante' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $conta->update($request->except('comprovante'));

        if ($request->hasFile('comprovante')) {

            if ($conta->comprovante) {
                $oldFilePath = public_path($conta->comprovante);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $path = public_path('arquivos/comprovantes');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileName = 'comprovante_' . $conta->id . '_' . now()->format('Ymd_His') . '.' . $request->file('comprovante')->getClientOriginalExtension();

            $request->file('comprovante')->move($path, $fileName);

            $conta->comprovante = 'arquivos/comprovantes/' . $fileName;
            $conta->save();
        }

        return redirect()->route('contas.index');
    }

    public function destroy(Conta $conta)
    {
        $conta->delete();
        return redirect()->route('contas.index');
    }
}
