<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        // "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/getUpdates"

        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->url = 'https://api.telegram.org/bot' . $this->token . '/';
    }

    public function sendMessage($text, $chatId = 460636775)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($this->url . 'sendMessage', [
            'parse_mode' => 'HTML',
            'chat_id' => $chatId,
            'text' => $text
        ]);

        return $response->ok();
    }

    public function sendDocument($filePath, $legenda = null, $chatId = 460636775)
    {
        $fileInfo = pathinfo($filePath);
        $fileName = $fileInfo['basename'];
    
        $options = [
            'chat_id' => $chatId,
        ];

        if ($legenda) {
            $options['caption'] = $legenda;
            $options['parse_mode'] = 'HTML';
        }

        $response = Http::attach(
            'document',
            file_get_contents($filePath),
            $fileName
        )->post($this->url . 'sendDocument', $options);
    
        return $response->ok();
    }
}
