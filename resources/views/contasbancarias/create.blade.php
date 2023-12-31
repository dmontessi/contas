@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Conta Bancária</div>
            <div class="card-body">
                <form action="{{ route('contasbancarias.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Banco</label>
                                <select class="form-control" name="banco_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($bancos as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Devedor</label>
                                <select class="form-control" name="devedor_id">
                                    <option selected value disabled>Selecione</option>
                                    @foreach($devedores as $devedor)
                                    <option value="{{ $devedor->id }}">{{ $devedor->apelido }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agência</label>
                                <input class="form-control" type="text" name="agencia" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Conta</label>
                                <input class="form-control" type="text" name="conta" required>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Chave</label>
                                <select class="form-control" name="tipochave_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($tiposchaves as $tipochave)
                                    <option value="{{ $tipochave->id }}">{{ $tipochave->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chave PIX</label>
                                <input class="form-control" type="text" name="chave_pix" required>
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
