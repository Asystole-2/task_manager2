<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \DB::table('migrations')->insert([
            ['migration' => '0001_01_01_000000_create_users_table', 'batch' => 1],
            ['migration' => '0001_01_01_000001_create_cache_table', 'batch' => 1],
            ['migration' => '0001_01_01_000002_create_jobs_table', 'batch' => 1]
        ]);
        $this->info('Migration records added successfully!');
    }
}
