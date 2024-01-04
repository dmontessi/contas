<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Devedor;
use App\Models\TipoChave;
use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class ContaBancariaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $banco_id = $request->input('banco_id');

        $contasbancarias = ContaBancaria::orderBy('id', 'desc')
            ->where('user_id', auth()->id())
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
        $devedores = Devedor::where('user_id', auth()->id())->get();
        return view('contasbancarias.create', compact('bancos', 'tiposchaves', 'devedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'devedor_id' => 'nullable|exists:devedores,id',
            'agencia' => 'nullable|string|max:255',
            'conta' => 'nullable|string|max:255',
            'tipochave_id' => 'nullable|exists:tipos_chaves,id',
            'chave_pix' => 'nullable|string',
        ]);

        $contabancaria = ContaBancaria::create($request->all());

        return redirect()->route('contasbancarias.index');
    }

    public function show(ContaBancaria $contabancaria)
    {
        if ($contabancaria->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }

        return view('contasbancarias.show', compact('contabancaria'));
    }

    public function edit(ContaBancaria $contabancaria)
    {
        if ($contabancaria->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }

        $bancos = Banco::all();
        $tiposchaves = TipoChave::all();
        $devedores = Devedor::where('user_id', auth()->id())->get();
        return view('contasbancarias.edit', compact('contabancaria', 'bancos', 'tiposchaves', 'devedores'));
    }

    public function update(Request $request, ContaBancaria $contabancaria)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'devedor_id' => 'nullable|exists:devedores,id',
            'agencia' => 'nullable|string|max:255',
            'conta' => 'nullable|string|max:255',
            'tipochave_id' => 'nullable|exists:tipos_chaves,id',
            'chave_pix' => 'nullable|string',
        ]);

        $contabancaria->update($request->all());

        return redirect()->route('contasbancarias.index');
    }

    public function destroy(ContaBancaria $contabancaria)
    {
        if ($contabancaria->user_id !== auth()->id()) {
            abort(403, 'Não autorizado');
        }
        
        $contabancaria->delete();
        return redirect()->route('contasbancarias.index');
    }
}
