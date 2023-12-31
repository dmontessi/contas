<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');

        $fornecedores = Fornecedor::orderBy('id', 'desc')
            ->where(function ($query) use ($nome) {
                if ($nome) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }
            })
            ->where('ativo', 1)->paginate(15);

        $fornecedores->appends(['nome' => $nome]);

        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
        ]);

        $fornecedor = Fornecedor::create($request->all());

        return redirect()->route('fornecedores.index');
    }

    public function show(Fornecedor $fornecedor)
    {
        return view('fornecedores.show', compact('fornecedor'));
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
        ]);

        $fornecedor->update($request->all());

        return redirect()->route('fornecedores.index');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index');
    }
}
