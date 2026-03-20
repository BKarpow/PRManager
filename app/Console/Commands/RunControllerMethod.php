<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CronController;
use App\Services\CronHandler;

class RunControllerMethod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Крон метод';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cron = new CronHandler(false);
        $this->info("Запуск завдань крон");
        $this->info($cron->execute());
        $this->info("Запуск завдань крон");
    }
}
