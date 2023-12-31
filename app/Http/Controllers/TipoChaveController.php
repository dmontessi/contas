<?php

namespace App\Http\Controllers;

use App\Models\TipoChave;
use Illuminate\Http\Request;

class TipoChaveController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');

        $tiposchaves = TipoChave::orderBy('id', 'desc')
            ->where(function ($query) use ($nome) {
                if ($nome) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }
            })->paginate(15);

        $tiposchaves->appends(['nome' => $nome]);

        return view('tiposchaves.index', compact('tiposchaves'));
    }

    public function create()
    {
        return view('tiposchaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $tipochave = TipoChave::create($request->all());

        return redirect()->route('tiposchaves.index');
    }

    public function show(TipoChave $tipochave)
    {
        return view('tiposchaves.show', compact('tipochave'));
    }

    public function edit(TipoChave $tipochave)
    {
        return view('tiposchaves.edit', compact('tipochave'));
    }

    public function update(Request $request, TipoChave $tipochave)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $tipochave->update($request->all());

        return redirect()->route('tiposchaves.index');
    }

    public function destroy(TipoChave $tipochave)
    {
        $tipochave->delete();
        return redirect()->route('tiposchaves.index');
    }
}
