<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database Backup';

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
     * @return mixed
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
        $command = "mysqldump --user=" . config('database.connections.mysql.username') ." --password=" . config('database.connections.mysql.password') . " --host=" . config('database.connections.mysql.host') . " " . config('database.connections.mysql.database') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
        var_dump($command);
        $returnVar = NULL;
        $output  = NULL;
        // exec($command, $output, $returnVar);
    }
}
