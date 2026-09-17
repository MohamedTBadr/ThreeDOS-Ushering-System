<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function handle(Request $request)
    {
        $user = $request->attributes->get('auth_user');

        $council = $user['council'] ?? $user->council ?? '';
        // Support both array and Eloquent model
        if (is_object($user) && isset($user->council)) {
            $council = $user->council;
        } elseif (is_array($user) && isset($user['council'])) {
            $council = $user['council'];
        }

        if ($council !== 'Backend Development') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access to logs. Backend Development council only.', 'data' => null], 403);
        }

        $logDir = storage_path('logs').DIRECTORY_SEPARATOR;

        if ($request->has('list')) {
            $files = glob($logDir.'*.log');
            $logFiles = [];
            if ($files) {
                foreach ($files as $file) {
                    $logFiles[] = basename($file);
                }
                rsort($logFiles);
            }
            return response()->json(['status' => 'success', 'message' => 'Log files retrieved', 'data' => array_values($logFiles)]);
        }

        if ($request->has('view') && $request->filled('file')) {
            $filename = basename($request->query('file'));

            if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
                return response()->json(['status' => 'error', 'message' => 'Invalid filename', 'data' => null], 400);
            }

            $filePath = $logDir.$filename;
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $lines = explode(PHP_EOL, $content);
                $parsed = [];
                foreach ($lines as $line) {
                    if (trim($line) === '') continue;
                    $decoded = json_decode($line, true);
                    if ($decoded) {
                        $parsed[] = $decoded;
                    } else {
                        $parsed[] = ['message' => $line, 'level' => 'UNKNOWN', 'time' => ''];
                    }
                }
                $parsed = array_reverse($parsed);
                return response()->json(['status' => 'success', 'message' => 'Log content retrieved', 'data' => $parsed]);
            }
            return response()->json(['status' => 'error', 'message' => 'Log file not found', 'data' => null], 404);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid parameters. Use ?list=1 or ?view=1&file=...', 'data' => null], 400);
    }
}
