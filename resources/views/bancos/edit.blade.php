@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Editar Banco</div>
            <div class="card-body">
                <form action="{{ route('bancos.update', $banco->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código</label>
                                <input class="form-control" type="number" name="codigo" value="{{ $banco->codigo }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control" type="text" name="nome" value="{{ $banco->nome }}" required>
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
