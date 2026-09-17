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
            foreach ($files as $f) $logFiles[] = basename($f);
            rsort($logFiles);
        }

        $selectedFile = $request->query('file');
        $logs = [];
        if ($selectedFile) {
            $path = $logDir.basename($selectedFile);
            if (file_exists($path)) {
                $content = file_get_contents($path);
                foreach (explode(PHP_EOL, $content) as $line) {
                    if (trim($line)==='') continue;
                    $decoded = json_decode($line, true);
                    $logs[] = $decoded ?: ['message'=>$line,'level'=>'UNKNOWN','time'=>''];
                }
                $logs = array_reverse($logs);
            }
        }

        return view('logs.index', compact('logFiles', 'logs', 'selectedFile'));
    }
}
