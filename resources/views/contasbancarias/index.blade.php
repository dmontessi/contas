@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-2 my-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('contasbancarias.create') }}" type="submit" class="btn btn-success w-100">
                    <i class="bi bi-plus"></i> Novo
                </a>
                <hr class="my-3">
                <div class="form-group">
                    <label class="pb-1">Buscar:</label>
                    <form action="{{ route('contasbancarias.index') }}" method="GET" autocomplete="off">
                        <select class="form-control mb-2" name="banco_id">
                            <option selected value disabled>Selecione</option>
                            @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ Request::input('banco_id') == $banco->id ? 'selected' : '' }}>{{ $banco->nome }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
    $contador = $contasbancarias->total();
    @endphp

    <div class="col-md-10 my-4">
        <div class="card">
            <div class="card-header">Contas Bancárias</div>
            <div class="card-body p-2">
                <div class="table-responsive min-table-height">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center align-middle m-0 py-0 px-1" width="20%">Agência</th>
                                <th class="text-center align-middle m-0 py-0 px-1" width="20%">Conta</th>
                                <th class="text-center align-middle m-0 py-0 px-1" width="40%">Banco</th>
                                <th class="text-center align-middle m-0 py-0 px-1" width="10%">Ativa</th>
                                <th class="text-center align-middle m-0 py-0 px-1" width="10%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($contador > 0)
                            @foreach ($contasbancarias as $contabancaria)
                            <tr>
                                <td class="text-center align-middle m-0 py-0 px-1">
                                    <a href="{{ route('contasbancarias.edit', $contabancaria->id) }}" class="list-group-item list-group-item-action">
                                        {{ $contabancaria->agencia }}
                                    </a>
                                </td>
                                <td class="text-center align-middle m-0 py-0 px-1">
                                    <a href="{{ route('contasbancarias.edit', $contabancaria->id) }}" class="list-group-item list-group-item-action">
                                        {{ $contabancaria->conta }}
                                    </a>
                                </td>
                                <td class="text-center align-middle m-0 py-0 px-1">
                                    <a href="{{ route('contasbancarias.edit', $contabancaria->id) }}" class="list-group-item list-group-item-action">
                                        {{ $contabancaria->banco->nome }}
                                    </a>
                                </td>
                                <td class="text-center align-middle m-0 py-0 px-1">
                                    <a href="{{ route('contasbancarias.edit', $contabancaria->id) }}" class="list-group-item list-group-item-action">
                                        {{ $contabancaria->ativa ? 'Sim' : 'Não' }}
                                    </a>
                                </td>
                                <td class="text-center align-middle m-0 py-0 px-1">
                                    <div class="dropdown">
                                        <button class="btn py-0 px-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-list"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('contasbancarias.destroy', $contabancaria->id) }}" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    @csrf
                                                    <a href="#" class="dropdown-item py-0" onclick="this.parentNode.submit(); return false;">Excluir</a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center align-middle m-0 py-0 px-1" colspan="5">Nenhum resultado encontrado</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center min-pagination-height">{!! $contasbancarias->links() !!}</div>
            </div>
            <div class="card-footer">
                {{ $contador > 0 ? ($contador . ($contador > 1 ? ' Registros ' : ' Registro ') . ($contador > 1 ? 'Encontrados' : 'Encontrado')) : '' }}
            </div>
        </div>
    </div>

</div>
@endsection
