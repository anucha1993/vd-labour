<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ตรวจสอบการแจ้งเตือนใบสมัครงานทุกนาที (สำหรับทดสอบ)
        // เปลี่ยนเป็น dailyAt('09:00') เมื่อใช้งานจริง
        $schedule->command('joblead:check-notifications')->everyMinute();
        
        // หรือใช้แบบนี้สำหรับ production:
        // $schedule->command('joblead:check-notifications')->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
