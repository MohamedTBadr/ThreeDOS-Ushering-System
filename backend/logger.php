<?php

define('LOG_DIR', __DIR__ . '/logs/');

if (!file_exists(LOG_DIR)) {
    mkdir(LOG_DIR, 0755, true);
}

function writeLog($level, $message, $context = [])
{
    $file = LOG_DIR . date('Y-m-d') . '.log';

    $logEntry = [
        'time' => date('Y-m-d H:i:s'),
        'level' => strtoupper($level),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'method' => $_SERVER['REQUEST_METHOD'] ?? null,
        'url' => $_SERVER['REQUEST_URI'] ?? null,
        'message' => $message,
        'context' => $context
    ];

    file_put_contents(
        $file,
        json_encode($logEntry, JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND
    );
}
