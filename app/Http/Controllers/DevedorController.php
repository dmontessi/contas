<?php

namespace App\Http\Controllers;

use App\Models\Devedor;
use Illuminate\Http\Request;

class DevedorController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');

        $devedores = Devedor::orderBy('id', 'desc')
            ->where(function ($query) use ($nome) {
                if ($nome) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }
            })
            ->where('ativo', 1)->paginate(15);

        $devedores->appends(['nome' => $nome]);

        return view('devedores.index', compact('devedores'));
    }

    public function create()
    {
        return view('devedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
        ]);

        $devedor = Devedor::create($request->all());

        return redirect()->route('devedores.index');
    }

    public function show(Devedor $devedor)
    {
        return view('devedores.show', compact('devedor'));
    }

    public function edit(Devedor $devedor)
    {
        return view('devedores.edit', compact('devedor'));
    }

    public function update(Request $request, Devedor $devedor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
        ]);

        $devedor->update($request->all());

        return redirect()->route('devedores.index');
    }

    public function destroy(Devedor $devedor)
    {
        $devedor->delete();
        return redirect()->route('devedores.index');
    }
}
