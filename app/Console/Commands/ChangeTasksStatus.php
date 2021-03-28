<?php

namespace App\Console\Commands;

use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeTasksStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:change-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing unsolved tasks status to failed after expiration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->startOfMinute()->toDateTimeString();
        UserTasks::whereNull('status')->where('expired_at', '<', $now)->update(['status' => 'failed']);
    }
}
