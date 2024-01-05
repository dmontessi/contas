@extends('layouts.app')

@section('content')

@if ($conta->cobranca)
@php
$extensao = strtolower(pathinfo($conta->cobranca, PATHINFO_EXTENSION));
@endphp
@endif


<div class="row">

    @if ($conta->cobranca)
    <div class="col-md-6 d-flex flex-column my-4">
        <div class="card flex-grow-1">

            @if ($extensao == 'pdf')
            <div style="height: 80vh;">
                <embed src="{{ asset($conta->cobranca) }}" type="application/pdf" width="100%" height="100%">
            </div>
            @elseif (in_array($extensao, ['png', 'gif', 'jpg', 'bmp', 'jpeg']))
            <div style="height: 80vh; overflow: auto;">
                <img src="{{ asset($conta->cobranca) }}" alt="Cobrança Imagem" style="width: 100%; height: auto;">
            </div>
            @endif

        </div>
    </div>
    @else
    <div class="col-md-3"></div>
    @endif

    <div class="col-md-6 d-flex flex-column justify-content-center my-4">
        <form action="{{ route('contas.update', $conta->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card bg-warning-subtle border-warning-subtle py-4 px-3">
                <div class="row my-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Valor Pago</label>
                            <input class="form-control" type="number" name="valor_pago" step="0.01" value="{{ $conta->valor_pago > 0 ? $conta->valor_pago : $conta->valor }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data de Pagamento</label>
                            <input class="form-control" type="date" name="data_pagamento" value="{{ $conta->data_pagamento ?? now()->format('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Forma de Pagamento</label>
                            <select class="form-control" name="formapagamento_id">
                                <option selected value disabled>Selecione</option>
                                @foreach($formaspagamentos as $formapagamento)
                                <option value="{{ $formapagamento->id }}" {{ $conta->formapagamento_id == $formapagamento->id ? 'selected' : '' }}>
                                    {{ $formapagamento->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Conta de Pagamento</label>
                            <select class="form-control" name="contabancaria_pagamento_id">
                                <option selected value disabled>Selecione</option>
                                @foreach($contaspagamentos as $contapagamento)
                                <option value="{{ $contapagamento->id }}" {{ $conta->contabancaria_pagamento_id == $contapagamento->id ? 'selected' : '' }}>
                                    {{ $contapagamento->banco->nome }} | {{ $contapagamento->agencia }} | {{ $contapagamento->conta }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comprovante</label>
                            <div class="input-group">
                                <input class="form-control" type="file" name="comprovante" accept="image/*,application/pdf">
                                @if ($conta->comprovante)
                                <a class="btn btn-warning" href="{{ asset($conta->comprovante) }}" target="_blank">Ver Comprovante</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="recorrente" id="recorrente" {{ $conta->recorrente ? 'checked' : '' }}>
                            <label class="form-check-label" for="recorrente">
                                Essa é uma conta recorrente que incidirá todos os meses
                            </label>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="btn-group" role="group">
                    <a href="{{ URL::previous() }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>

    @if (!$conta->cobranca)
    <div class="col-md-3"></div>
    @endif

</div>
@endsection
