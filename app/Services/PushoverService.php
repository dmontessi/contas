<?php

namespace App\Services;

use GuzzleHttp\Client;

class PushoverService
{
    protected $token;
    protected $user;
    protected $device;
    protected $title;
    protected $message;

    public function __construct()
    {
        // use App\Services\PushoverService;
        // $PushoverService = new PushoverService();
        // $PushoverService->sendNotification("título de teste", "mensagem de teste", "", "");

        $this->token = env('PUSHOVER_API_TOKEN');
    }

    public function sendNotification($title, $message, $user, $device)
    {
        $client = new Client();

        $response = $client->post('https://api.pushover.net/1/messages.json', [
            'form_params' => [
                'token' => $this->token,
                'user' => $user,
                'device' => $device,
                'title' => $title,
                'message' => $message,
            ],
        ]);

        return $response->getStatusCode() === 200;
    }
}
