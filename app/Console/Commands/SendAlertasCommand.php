<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AlertasJob;

class SendAlertasCommand extends Command
{
    protected $signature = 'alertas:send';
    protected $description = 'envia os alertas do dia';
    
    public function handle()
    {
        dispatch(new AlertasJob);
        return Command::SUCCESS;
    }
}
