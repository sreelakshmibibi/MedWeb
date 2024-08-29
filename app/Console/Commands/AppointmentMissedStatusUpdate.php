<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AppointmentMissedStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appointment-missed-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Scheduled appointments to missed status daily at 11:55pm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Daily update task running...');

        $appointments = Appointment::where('app_status', AppointmentStatus::SCHEDULED)
                        ->where('app_date', date('Y-m-d'))
                        ->get();
        if (!$appointments->isEmpty()){
            foreach($appointments as $appointment) {
                $appointment->app_status = AppointmentStatus::MISSED;
                $appointment->save();
            }
            $this->info('Appointment missed status update completed successfully!');
        } else {
            $this->info('No missed appointments!');
        }
    }
}
