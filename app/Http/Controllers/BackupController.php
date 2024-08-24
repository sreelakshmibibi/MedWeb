<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings db_backup', ['only' => ['index']]);
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $databaseName = "medweb";
            $username = "root";
            $password = "";
            $host = "127.0.0.1";

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

            // Debugging command
            // echo "<pre>$command</pre>";
            // exit;
            
            // Execute the command
            $output = [];
            $returnVar = 0;
            exec($command . " 2>&1", $output, $returnVar);
            
            if ($returnVar !== 0) {
                return response()->json(['error' => 'Backup failed', 'details' => implode("\n", $output)], 500);
            }

            if (file_exists($path)) {
                return response()->download($path);
            } else {
                return response()->json(['error' => 'Backup file not found'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred', 'details' => $e->getMessage()], 500);
        }
    }
}
