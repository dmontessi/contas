@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 my-2">
        <div class="card">
            <div class="card-header">Editar Conta</div>
            <div class="card-body">
                <form action="{{ route('contas.update', $conta->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Devedor</label>
                                <select class="form-control" name="devedor_id" required>
                                    <option selected value disabled>Selecione</option>
                                    @foreach($devedores as $devedor)
                                    <option value="{{ $devedor->id }}" {{ $conta->devedor_id == $devedor->id ? 'selected' : '' }}>
                                        {{ $devedor->apelido }}
                                    </option>
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
                                    <option value="{{ $fornecedor->id }}" {{ $conta->fornecedor_id == $fornecedor->id ? 'selected' : '' }}>
                                        {{ $fornecedor->apelido }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input class="form-control" type="text" name="descricao" value="{{ $conta->descricao }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cobrança</label>
                                <div class="input-group">
                                    <input class="form-control" type="file" name="cobranca" accept="image/*,application/pdf">
                                    @if ($conta->cobranca)
                                    <a class="btn btn-warning" href="{{ asset($conta->cobranca) }}" target="_blank">Ver Cobrança</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor</label>
                                <input class="form-control" type="number" name="valor" step="0.01" value="{{ $conta->valor }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Vencimento</label>
                                <input class="form-control" type="date" name="vencimento" value="{{ $conta->vencimento }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-warning-subtle border-warning-subtle my-2 py-2 px-3">
                        <div class="row py-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valor Pago</label>
                                    <input class="form-control" type="number" name="valor_pago" step="0.01" value="{{ $conta->valor_pago }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data de Pagamento</label>
                                    <input class="form-control" type="date" name="data_pagamento" value="{{ $conta->data_pagamento }}">
                                </div>
                            </div>
                        </div>

                        <div class="row py-2">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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

                        <div class="row py-2">
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
