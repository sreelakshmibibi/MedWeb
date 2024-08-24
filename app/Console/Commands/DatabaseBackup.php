<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $databaseName = 'medweb';
            $username = 'root';
            $password = '';
            $host = 'localhost';

            $filename = "backup-" . date('Y-m-d-H-i-s') . ".sql";
            $path = storage_path("app/backups/{$filename}");
            $directory = dirname($path);

            // Check if the directory exists
            if (!File::exists($directory)) {
                // Create the directory
                File::makeDirectory($directory, 0755, true);
            }
            
            // Full path to mysqldump
            $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
            
            // Escape arguments and paths
            $pathEscaped = escapeshellarg($path);
            
            // Backup command for MySQL
            $command = "$mysqldumpPath --user=$username --password=$password --host=$host $databaseName > $pathEscaped";

            // Execute the command
            $output = [];
            $returnVar = 0;
            exec($command . " 2>&1", $output, $returnVar);
            
            if ($returnVar !== 0) {
                $this->error('Backup failed: ' . implode("\n", $output));
                return 1;
            }

            $this->info('Backup successful: ' . $path);
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
