<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use Illuminate\Http\Request;

class FormaPagamentoController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');

        $formaspagamentos = FormaPagamento::orderBy('id', 'desc')
            ->where(function ($query) use ($nome) {
                if ($nome) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }
            })->paginate(15);

        $formaspagamentos->appends(['nome' => $nome]);

        return view('formaspagamentos.index', compact('formaspagamentos'));
    }

    public function create()
    {
        return view('formaspagamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $formapagamento = FormaPagamento::create($request->all());

        return redirect()->route('formaspagamentos.index');
    }

    public function show(FormaPagamento $formapagamento)
    {
        return view('formaspagamentos.show', compact('formapagamento'));
    }

    public function edit(FormaPagamento $formapagamento)
    {
        return view('formaspagamentos.edit', compact('formapagamento'));
    }

    public function update(Request $request, FormaPagamento $formapagamento)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $formapagamento->update($request->all());

        return redirect()->route('formaspagamentos.index');
    }

    public function destroy(FormaPagamento $formapagamento)
    {
        $formapagamento->delete();
        return redirect()->route('formaspagamentos.index');
    }
}
