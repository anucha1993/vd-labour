<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobLeadNotificationService;

class CheckJobLeadNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joblead:check-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and create notifications for job leads based on their status duration';

    protected $notificationService;
    
    public function __construct(JobLeadNotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking job lead notifications...');
        
        $this->notificationService->checkAndCreateNotifications();
        
        $this->info('Job lead notifications check completed!');
        
        return Command::SUCCESS;
    }
}
