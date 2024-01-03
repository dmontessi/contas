@extends('layouts.app')

@section('content')
<div class="row">

    @php
    $contador = $contas->count();
    @endphp

    <div class="d-flex flex-column my-4">

        <div class="d-flex justify-content-center p-3 mx-5 my-2">
            <div class="col-md-4">
                <div class="card p-2 mx-2 text-bg-success">
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <span class="fs-5 fw-bold">
                            <i class="bi bi-check-circle me-1"></i>Pagos
                        </span>
                        <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM [de] YYYY')) }}</small>
                    </div>

                    <hr class="my-1">

                    <div class="d-flex justify-content-center">
                        <span class="fs-3 fw-bold">{{ number_format($pagos, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-2 mx-2 text-bg-danger">
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <span class="fs-5 fw-bold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Em aberto
                        </span>
                        <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM [de] YYYY')) }}</small>
                    </div>

                    <hr class="my-1">

                    <div class="d-flex justify-content-center">
                        <span class="fs-3 fw-bold">{{ number_format($abertos, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-2 mx-2 text-bg-primary">
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <span class="fs-5 fw-bold">
                            <i class="bi bi-clipboard-data me-1"></i>Total
                        </span>
                        <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM [de] YYYY')) }}</small>
                    </div>

                    <hr class="my-1">

                    <div class="d-flex justify-content-center">
                        <span class="fs-3 fw-bold">{{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($contador > 0)
        <div class="d-flex justify-content-center mt-4">
            <small class="d-inline-flex px-2 py-1 fw-semibold text-danger bg-danger-subtle border border-danger-subtle rounded-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <span>Você possui contas vencendo hoje! 💸</span>
            </small>
        </div>

        <div class="d-flex justify-content-center card p-3 mx-5 my-2">
            <div class="table-responsive min-table-height">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center align-middle m-0 py-0 px-1" width="30%">Descrição</th>
                            <th class="text-center align-middle m-0 py-0 px-1" width="40%">Fornecedor</th>
                            <th class="text-center align-middle m-0 py-0 px-1" width="30%">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contas as $conta)
                        <tr>
                            <td class="text-center align-middle m-0 py-0 px-1" style="color:{{$conta->devedor->cor}}">
                                <a href="{{ route('contas.edit', $conta->id) }}" class="list-group-item list-group-item-action">
                                    {{ $conta->descricao }}
                                </a>
                            </td>
                            <td class="text-center align-middle m-0 py-0 px-1" style="color:{{$conta->devedor->cor}}">
                                <a href="{{ route('contas.edit', $conta->id) }}" class="list-group-item list-group-item-action">
                                    {{ $conta->fornecedor->apelido }}
                                </a>
                            </td>
                            <td class="text-center align-middle m-0 py-0 px-1" style="color:{{$conta->devedor->cor}}">
                                <a href="{{ route('contas.edit', $conta->id) }}" class="list-group-item list-group-item-action">
                                    {{ number_format($conta->valor, 2, ',', '.') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="d-flex justify-content-center mt-4">
                    <small class="d-inline-flex mb-1 px-2 py-1 fw-semibold text-success bg-success-subtle border border-success-subtle rounded-2">
                        <i class="bi bi-check2-circle me-1"></i>
                        <span>Parabéns, você está em dia com suas contas! 🤑</span>
                    </small>
                </div>
                <div class="d-flex justify-content-center">
                    <small>Não há contas vencendo hoje</small>
                </div>
                @endif
            </div>
        </div>

    </div>
    @endsection
