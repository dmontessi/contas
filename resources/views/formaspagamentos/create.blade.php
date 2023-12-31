@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Forma de Pagamento</div>
            <div class="card-body">
                <form action="{{ route('formaspagamentos.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control" type="text" name="nome" required>
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
