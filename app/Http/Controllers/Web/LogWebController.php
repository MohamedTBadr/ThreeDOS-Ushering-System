<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogWebController extends Controller
{
    public function index(Request $request)
    {
        $logDir = storage_path('logs').DIRECTORY_SEPARATOR;
        $files = glob($logDir.'*.log');

        $logFiles = [];
        if ($files) {
            foreach ($files as $f) {
                $logFiles[] = basename($f);
            }
        }

        // Show the dedicated structured (ushering) logs first, newest first,
        // then any other log files (e.g. laravel.log).
        usort($logFiles, function ($a, $b) {
            $aUshering = str_starts_with($a, 'ushering') ? 0 : 1;
            $bUshering = str_starts_with($b, 'ushering') ? 0 : 1;

            return $aUshering === $bUshering ? strcmp($b, $a) : $aUshering <=> $bUshering;
        });

        // Default to the newest structured log so the viewer opens on real data.
        $selectedFile = $request->query('file');
        if (!$selectedFile) {
            foreach ($logFiles as $file) {
                if (str_starts_with($file, 'ushering')) {
                    $selectedFile = $file;
                    break;
                }
            }
        }

        $logs = [];
        if ($selectedFile) {
            $path = $logDir.basename($selectedFile);
            if (file_exists($path)) {
                $content = file_get_contents($path);
                foreach (explode(PHP_EOL, $content) as $line) {
                    if (trim($line)==='') continue;
                    $decoded = json_decode($line, true);
                    if (is_array($decoded)) {
                        $logs[] = $decoded + ['level' => $decoded['level'] ?? 'INFO', 'message' => $decoded['message'] ?? '', 'time' => $decoded['time'] ?? ''];
                    } else {
                        $logs[] = ['message'=>$line,'level'=>'UNKNOWN','time'=>''];
                    }
                }
                $logs = array_reverse($logs);
            }
        }

        return view('logs.index', compact('logFiles', 'logs', 'selectedFile'));
    }
}
