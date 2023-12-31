<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');

        $bancos = Banco::orderBy('id', 'desc')
            ->where(function ($query) use ($nome) {
                if ($nome) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }
            })->paginate(15);

        $bancos->appends(['nome' => $nome]);

        return view('bancos.index', compact('bancos'));
    }

    public function create()
    {
        return view('bancos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'nome' => 'required|string|max:255',
        ]);

        $banco = Banco::create($request->all());

        return redirect()->route('bancos.index');
    }

    public function show(Banco $banco)
    {
        return view('bancos.show', compact('banco'));
    }

    public function edit(Banco $banco)
    {
        return view('bancos.edit', compact('banco'));
    }

    public function update(Request $request, Banco $banco)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'nome' => 'required|string|max:255',
        ]);

        $banco->update($request->all());

        return redirect()->route('bancos.index');
    }

    public function destroy(Banco $banco)
    {
        $banco->delete();
        return redirect()->route('bancos.index');
    }
}
