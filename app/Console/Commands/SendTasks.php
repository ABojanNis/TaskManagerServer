<?php

namespace App\Console\Commands;

use App\Helpers\RoleTypes;
use App\Mail\TaskMail;
use App\Task;
use App\User;
use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending tasks to user emails';

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
        $expired_at = Carbon::parse($now)->addHour()->toDateTimeString();
        $tasksToSend = Task::where('send_at', $now)->where('is_sent', 0)->get();

        $users = User::select('id', 'email')->role(RoleTypes::USER)->get();

        $data = [];
        foreach ($tasksToSend as $task) {
            Mail::to($users)->send(new TaskMail($task));
            foreach ($users as $user) {
                $createdAt = now();
                $data[] = [
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'expired_at' => $expired_at,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }
        }

        UserTasks::insert($data);
        Task::whereIn('id', $tasksToSend->pluck('id'))->update(['send_at' => $now, 'is_sent' => 1]);
    }
}
