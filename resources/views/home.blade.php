@extends('layouts.app')

@section('content')

@php
$contador = $vencidas_vencendo->count();
@endphp

<div class="d-flex flex-column my-4">

    <div class="d-flex justify-content-center row">
        @if($contas_vencidas->count() > 0)
            <div class="col-md-2">
                <div class="card p-2 mb-2 text-bg-dark">
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <span class="fs-6 fw-bold">
                            🚨 Vencidas
                        </span>
                        <small class="fw-light"></small>
                    </div>

                    <hr class="my-1">

                    <div class="d-flex justify-content-center">
                        <span class="fs-3 fw-bold">{{ number_format($contas_vencidas->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="@if($contas_vencidas->count() > 0) col-md-2 @else col-md-3 @endif">
            <div class="card p-2 mb-2 text-bg-danger">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <span class="fs-6 fw-bold">
                        ⚠️ Vencendo
                    </span>
                    <small class="fw-light"></small>
                </div>

                <hr class="my-1">

                <div class="d-flex justify-content-center">
                    <span class="fs-3 fw-bold">{{ number_format($contas_vencendo->sum('valor'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="@if($contas_vencidas->count() > 0) col-md-2 @else col-md-3 @endif">
            <div class="card p-2 mb-2 text-bg-success">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <span class="fs-6 fw-bold">
                        ✅ Pagos
                    </span>
                    <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM')) }}</small>
                </div>

                <hr class="my-1">

                <div class="d-flex justify-content-center">
                    <span class="fs-3 fw-bold">{{ number_format($pagos, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="@if($contas_vencidas->count() > 0) col-md-2 @else col-md-3 @endif">
            <div class="card p-2 mb-2 text-bg-warning">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <span class="fs-6 fw-bold">
                        📢 Aberto
                    </span>
                    <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM')) }}</small>
                </div>

                <hr class="my-1">

                <div class="d-flex justify-content-center">
                    <span class="fs-3 fw-bold">{{ number_format($abertos, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="@if($contas_vencidas->count() > 0) col-md-2 @else col-md-3 @endif">
            <div class="card p-2 mb-2 text-bg-primary">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <span class="fs-6 fw-bold">
                        📊 Total
                    </span>
                    <small class="fw-light">{{ ucfirst(\Carbon\Carbon::now()->locale('pt-BR')->isoFormat('MMMM')) }}</small>
                </div>

                <hr class="my-1">

                <div class="d-flex justify-content-center">
                    <span class="fs-3 fw-bold">{{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4 row">
        <div class="col-md-6">
            <div class="card p-3 mx-1 mb-2">
                <canvas id="grafico1"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 mx-1 mb-2">
                <canvas id="grafico2"></canvas>
            </div>
        </div>
    </div>

    @if ($contador > 0)
    <div class="d-flex justify-content-center mt-4">
        <small class="d-inline-flex px-2 py-1 fw-semibold text-danger bg-danger-subtle border border-danger-subtle rounded-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <span>Você possui contas @if($contas_vencidas->count() > 0) vencidas @endif @if($contas_vencidas->count() > 0 && $vencendo) e @endif @if($vencendo) vencendo {{ $vencendo }}@endif 💸</span>
        </small>
    </div>

    <div class="d-flex justify-content-center card p-3 mt-2">
        <div class="table-responsive min-table-height">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center align-middle m-0 py-0 px-1" width="20%">Devedor</th>
                        <th class="text-center align-middle m-0 py-0 px-1" width="20%">Descrição</th>
                        <th class="text-center align-middle m-0 py-0 px-1" width="20%">Fornecedor</th>
                        <th class="text-center align-middle m-0 py-0 px-1" width="20%">Vencimento</th>
                        <th class="text-center align-middle m-0 py-0 px-1" width="20%">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vencidas_vencendo as $conta)
                    <tr>
                        <td class="text-center align-middle m-0 py-0 px-1 @if (\Carbon\Carbon::parse($conta->vencimento)->lt(now()->startOfDay())) table-danger text-danger @endif" style="color:{{$conta->devedor->cor}}">
                            <a href="{{ route('contas.pay', $conta->id) }}" class="list-group-item list-group-item-action">
                                {{ $conta->devedor->apelido }}
                            </a>
                        </td>
                        <td class="text-center align-middle m-0 py-0 px-1 @if (\Carbon\Carbon::parse($conta->vencimento)->lt(now()->startOfDay())) table-danger text-danger @endif" style="color:{{$conta->devedor->cor}}">
                            <a href="{{ route('contas.pay', $conta->id) }}" class="list-group-item list-group-item-action">
                                {{ $conta->descricao }}
                            </a>
                        </td>
                        <td class="text-center align-middle m-0 py-0 px-1 @if (\Carbon\Carbon::parse($conta->vencimento)->lt(now()->startOfDay())) table-danger text-danger @endif" style="color:{{$conta->devedor->cor}}">
                            <a href="{{ route('contas.pay', $conta->id) }}" class="list-group-item list-group-item-action">
                                {{ $conta->fornecedor->apelido }}
                            </a>
                        </td>
                        <td class="text-center align-middle m-0 py-0 px-1 @if (\Carbon\Carbon::parse($conta->vencimento)->lt(now()->startOfDay())) table-danger text-danger @endif" style="color:{{$conta->devedor->cor}}">
                            <a href="{{ route('contas.pay', $conta->id) }}" class="list-group-item list-group-item-action">
                                {{ date('d/m/Y', strtotime($conta->vencimento)) }}
                            </a>
                        </td>
                        <td class="text-center align-middle m-0 py-0 px-1 @if (\Carbon\Carbon::parse($conta->vencimento)->lt(now()->startOfDay())) table-danger text-danger @endif" style="color:{{$conta->devedor->cor}}">
                            <a href="{{ route('contas.pay', $conta->id) }}" class="list-group-item list-group-item-action">
                                {{ number_format($conta->valor, 2, ',', '.') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @else

    <div class="d-flex justify-content-center mt-4">
        <small class="d-inline-flex mb-1 px-2 py-1 fw-semibold text-success bg-success-subtle border border-success-subtle rounded-2">
            <i class="bi bi-check2-circle me-1"></i>
            <span>Parabéns, você está em dia com suas contas! 🤑</span>
        </small>
    </div>
    <div class="d-flex justify-content-center">
        <small class="text-secondary">Não há contas vencendo hoje</small>
    </div>

    @endif
</div>

<script type="module">
    window.formatCurrency = function(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    var dados = @json($grafico1);
    var grafico1 = new Chart(document.getElementById('grafico1').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(dados),
            datasets: [{
                label: 'Valor por devedor',
                data: Object.values(dados).map(item => item.valor),
                backgroundColor: Object.values(dados).map(item => item.cor),
                borderWidth: 1
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return formatCurrency(tooltipItem.yLabel);
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });

    var dados = @json($grafico2);
    var grafico2 = new Chart(document.getElementById('grafico2').getContext('2d'), {
        type: 'line',
        data: {
            labels: Object.keys(dados),
            datasets: Object.keys(dados[Object.keys(dados)[0]]).map(function (devedor) {
                return {
                    label: devedor,
                    data: Object.values(dados).map(function (valores) {
                        return valores[devedor].valor || 0;
                    }),
                    borderColor: dados[Object.keys(dados)[0]][devedor].cor || '#CCCCCC', // Corrigido aqui
                    fill: false,
                };
            }),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
</script>
@endsection
