<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    public function download()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableKey = "Tables_in_{$dbName}";

        $sql = "-- Tayabas ICT Hub Database Backup\n";
        $sql .= '-- Generated: '.now()->format('Y-m-d H:i:s')."\n";
        $sql .= "-- Database: {$dbName}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Drop & create
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'}.";\n\n";

            // Insert data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                $values = [];
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $escaped = array_map(function ($val) {
                        if (is_null($val)) {
                            return 'NULL';
                        }

                        return "'".addslashes($val)."'";
                    }, $rowArray);
                    $values[] = '('.implode(', ', $escaped).')';
                }
                $sql .= implode(",\n", $values).";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Log backup action
        AuditTrail::create([
            'user_id' => Auth::id(),
            'action_done' => 'Downloaded database backup',
            'action_at' => now(),
        ]);

        $filename = 'icthub_backup_'.now()->format('Ymd_His').'.sql';

        return response($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
