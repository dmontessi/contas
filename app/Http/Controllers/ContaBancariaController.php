<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\TipoChave;
use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class ContaBancariaController extends Controller
{
    public function index(Request $request)
    {
        $banco_id = $request->input('banco_id');

        $contasbancarias = ContaBancaria::orderBy('id', 'desc')
            ->where(function ($query) use ($banco_id) {
                if ($banco_id) {
                    $query->where('banco_id', $banco_id);
                }
            })->paginate(15);

        $contasbancarias->appends(['banco_id' => $banco_id]);

        $bancos = Banco::all();

        return view('contasbancarias.index', compact('contasbancarias', 'bancos'));
    }

    public function create()
    {
        $bancos = Banco::all();
        $tiposchaves = TipoChave::all();
        return view('contasbancarias.create', compact('bancos', 'tiposchaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'agencia' => 'nullable|numeric',
            'conta' => 'nullable|numeric',
            'tipochave_id' => 'nullable|exists:tipos_chaves,id',
            'chave_pix' => 'nullable|string',
        ]);

        $contabancaria = ContaBancaria::create($request->all());

        return redirect()->route('contasbancarias.index');
    }

    public function show(ContaBancaria $contabancaria)
    {
        return view('contasbancarias.show', compact('contabancaria'));
    }

    public function edit(ContaBancaria $contabancaria)
    {
        $bancos = Banco::all();
        $tiposchaves = TipoChave::all();
        return view('contasbancarias.edit', compact('contabancaria', 'bancos', 'tiposchaves'));
    }

    public function update(Request $request, ContaBancaria $contabancaria)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'agencia' => 'nullable|integer',
            'conta' => 'nullable|integer',
            'tipochave_id' => 'nullable|exists:tipos_chaves,id',
            'chave_pix' => 'nullable|string',
        ]);

        $contabancaria->update($request->all());

        return redirect()->route('contasbancarias.index');
    }

    public function destroy(ContaBancaria $contabancaria)
    {
        $contabancaria->delete();
        return redirect()->route('contasbancarias.index');
    }
}
