<?php

namespace App\Jobs;

use App\Models\Conta;
use App\Services\TelegramService;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlertasJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 8000;

    protected $env;

    public function handle()
    {
        $bloqueio = Cache::lock('AlertasJob', 8000);
        if ($bloqueio->get()) {
            $this->send();
            $bloqueio->release();
        }
    }

    public function send()
    {
        $telegramService = new TelegramService();

        $hoje = Carbon::today();
        $diaDaSemana = $hoje->dayOfWeek;

        if ($diaDaSemana === Carbon::FRIDAY) {
            $contas = Conta::orderBy('vencimento', 'asc')
                ->whereNull('data_pagamento')
                ->where(function ($query) use ($hoje) {
                    $query->whereDate('vencimento', $hoje);
                    $query->orWhere(function ($query) use ($hoje) {
                        $query->whereDate('vencimento', $hoje->copy()->next(Carbon::SATURDAY));
                    });
                    $query->orWhere(function ($query) use ($hoje) {
                        $query->whereDate('vencimento', $hoje->copy()->next(Carbon::SUNDAY));
                    });
                })
                ->get();
        } else {
            $contas = Conta::orderBy('vencimento', 'asc')
                ->whereNull('data_pagamento')
                ->whereDate('vencimento', $hoje)
                ->get();
        }

        if ($contas->count() > 0) {
            foreach ($contas as $conta) {
                $message = '👤 ' . '<strong>' . $conta->devedor->apelido . '</strong>' . "\n" .
                    '➡️ ' . '<strong>' . $conta->descricao . '</strong>' . ' de ' . $conta->fornecedor->apelido . ' vencendo ' . Carbon::parse($conta->vencimento)->isoFormat('dddd, DD [de] MMMM [de] YYYY') . "\n" .
                    '💰 ' . '<strong>Valor</strong> R$ ' . number_format($conta->valor, 2, ',', '.');

                if ($conta->cobranca) {
                    $file_path = public_path($conta->cobranca);

                    if (file_exists($file_path)) {
                        if ($conta->user->telegram_id) {
                            $ids = explode(',', $conta->user->telegram_id);
                            $ids = array_map('trim', $ids);
                            $ids = array_filter($ids);

                            foreach ($ids as $id) {
                                $telegramService->sendDocument($file_path, $message, $id);
                            }
                        }
                    } else {
                        if ($conta->user->telegram_id) {
                            $ids = explode(',', $conta->user->telegram_id);
                            $ids = array_map('trim', $ids);
                            $ids = array_filter($ids);

                            foreach ($ids as $id) {
                                $telegramService->sendMessage($message, $id);
                            }
                        }
                    }
                } else {
                    if ($conta->user->telegram_id) {
                        $ids = explode(',', $conta->user->telegram_id);
                        $ids = array_map('trim', $ids);
                        $ids = array_filter($ids);

                        foreach ($ids as $id) {
                            $telegramService->sendMessage($message, $id);
                        }
                    }
                }
            }
        }
    }
}
