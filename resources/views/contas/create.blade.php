@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Conta</div>
            <div class="card-body">
                <form action="{{ route('contas.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Devedor</label>
                                <select class="form-control" name="devedor_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($devedores as $devedor)
                                        <option value="{{ $devedor->id }}">{{ $devedor->apelido }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select class="form-control" name="fornecedor_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->apelido }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input class="form-control" type="text" name="descricao" required>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cobrança</label>
                                <div class="input-group">
                                    <input class="form-control" type="file" name="cobranca" accept="image/*,application/pdf">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor</label>
                                <input class="form-control" type="number" name="valor" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Vencimento</label>
                                <input class="form-control" type="date" name="vencimento" required>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="recorrente" id="recorrente">
                                <label class="form-check-label" for="recorrente">
                                    Essa é uma conta recorrente que incidirá todos os meses
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="btn-group" role="group">
                        <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Voltar</a>
                        <button type="submit" class="btn btn-warning">Salvar</button>
                    </div>

                </form>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
@endsection
