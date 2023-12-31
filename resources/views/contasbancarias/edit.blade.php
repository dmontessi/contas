@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Editar Conta Bancária</div>
            <div class="card-body">
                <form action="{{ route('contasbancarias.update', $contabancaria->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Banco</label>
                                <select class="form-control" name="banco_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($bancos as $banco)
                                    <option value="{{ $banco->id }}" {{ $contabancaria->banco_id == $banco->id ? 'selected' : '' }}>
                                        {{ $banco->nome }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agência</label>
                                <input class="form-control" type="text" name="agencia" value="{{ $contabancaria->agencia }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Conta</label>
                                <input class="form-control" type="text" name="conta" value="{{ $contabancaria->conta }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Chave</label>
                                <select class="form-control" name="tipochave_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($tiposchaves as $tipochave)
                                    <option value="{{ $tipochave->id }}" {{ $contabancaria->tipochave_id == $tipochave->id ? 'selected' : '' }}>
                                        {{ $tipochave->nome }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chave PIX</label>
                                <input class="form-control" type="text" name="chave_pix" value="{{ $contabancaria->chave_pix }}" required>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="btn-group" role="group">
                        <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                    </div>

                </form>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
@endsection
