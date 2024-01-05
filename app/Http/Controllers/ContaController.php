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
            ->orderBy('vencimento', 'asc')
            ->orderBy('id', 'desc')
            ->where('user_id', auth()->id())
            ->whereNull('data_pagamento')
            ->where(function ($query) use ($descricao) {
                if ($descricao) {
                    $query->where('descricao', 'LIKE', "%$descricao%");
                }
            })->paginate(100);

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
        if ($conta->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }

        return view('contas.show', compact('conta'));
    }

    public function edit(Conta $conta)
    {
        if ($conta->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }

        $formaspagamentos = FormaPagamento::all();
        $devedores = Devedor::where('user_id', auth()->id())->get();
        $fornecedores = Fornecedor::where('user_id', auth()->id())->get();
        $contaspagamentos = ContaBancaria::where('user_id', auth()->id())->get();
        return view('contas.edit', compact('conta', 'devedores', 'fornecedores', 'formaspagamentos', 'contaspagamentos'));
    }

    public function pay(Conta $conta)
    {
        if ($conta->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }

        $formaspagamentos = FormaPagamento::all();
        $devedores = Devedor::where('user_id', auth()->id())->get();
        $fornecedores = Fornecedor::where('user_id', auth()->id())->get();
        $contaspagamentos = ContaBancaria::where('user_id', auth()->id())->get();
        return view('contas.pay', compact('conta', 'devedores', 'fornecedores', 'formaspagamentos', 'contaspagamentos'));
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

        if ($request->has('data_pagamento')) {

            $proximo_vencimento = Carbon::parse($conta->vencimento)->addMonth();

            $proxima_conta = Conta::where('fornecedor_id', $conta->fornecedor_id)
                ->where('devedor_id', $conta->devedor_id)
                ->where('descricao', $conta->descricao)
                ->where('vencimento', $proximo_vencimento)
                ->where('valor', $conta->valor)
                ->first();

            if (!$proxima_conta) {
                $prox_conta = $conta->replicate();
                unset($prox_conta['cobranca']);
                unset($prox_conta['valor_pago']);
                unset($prox_conta['data_pagamento']);
                unset($prox_conta['formapagamento_id']);
                unset($prox_conta['contabancaria_pagamento_id']);
                unset($prox_conta['comprovante']);
                $prox_conta->vencimento = Carbon::parse($conta->vencimento)->addMonth();
                $prox_conta->created_at = now();
                $prox_conta->updated_at = null;
                $prox_conta->save();
            }
        }

        $request->merge(['recorrente' => $request->input('recorrente', 0)]);
        $conta->update($request->except('cobranca', 'comprovante'));

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
        if ($conta->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }
        
        $conta->delete();
        return redirect()->route('contas.index');
    }
}
