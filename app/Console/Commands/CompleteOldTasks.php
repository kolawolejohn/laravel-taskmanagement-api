<?php

namespace App\Console\Commands;

use App\Jobs\AblyPublishJob;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompleteOldTasks extends Command
{
    protected $signature = 'tasks:complete-old';
    protected $description = 'Mark tasks older than 7 days as completed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(7);

        $tasks = Task::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        $updatedCount = 0;

        foreach ($tasks as $task) {
            $task->status = 'completed';
            $task->save();

            // Dispatch Ably job for task.completed event
            AblyPublishJob::dispatch('task.completed', $task->toArray());

            $updatedCount++;
        }

        $this->info("Marked $updatedCount task(s) as completed.");

        return self::SUCCESS;
    }
}
