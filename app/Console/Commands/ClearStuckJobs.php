<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearStuckJobs extends Command
{
    protected $signature = 'queue:clear-stuck';
    protected $description = 'Delete stuck jobs (attempts >= 255) from jobs table';

    public function handle()
    {
        $count = DB::table('jobs')->where('attempts', '>=', 255)->delete();
        $this->info("Deleted {$count} stuck jobs.");
    }
}