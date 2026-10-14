<?php

namespace App\Logging;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

/**
 * Formats each log record as a single JSON line with a fixed, full structure.
 *
 * Output shape (one JSON object per line):
 * {
 *   "time": "2026-10-14 12:34:56",
 *   "level": "INFO",
 *   "channel": "ushering",
 *   "message": "User logged in",
 *   "ip": "127.0.0.1",
 *   "user": "head_backend",
 *   "method": "POST",
 *   "url": "https://host/login",
 *   "status": 200,
 *   "duration_ms": 42.5,
 *   "user_agent": "Mozilla/5.0 ...",
 *   "context": { ... }
 * }
 */
class UsheringFormatter implements FormatterInterface
{
    /**
     * Keys promoted to top-level fields (removed from the nested context).
     */
    private const RESERVED = [
        'ip',
        'user',
        'method',
        'url',
        'status',
        'duration_ms',
        'user_agent',
    ];

    public function format(LogRecord $record): string
    {
        $context = $record->context;

        $ip = $this->pull($context, 'ip');
        $user = $this->pull($context, 'user');
        $method = $this->pull($context, 'method');
        $url = $this->pull($context, 'url');
        $status = $this->pull($context, 'status');
        $duration = $this->pull($context, 'duration_ms');
        $userAgent = $this->pull($context, 'user_agent');

        $data = [
            'time' => $record->datetime->format('Y-m-d H:i:s'),
            'level' => strtoupper($record->level->getName()),
            'channel' => $record->channel,
            'message' => $record->message,
            'ip' => $ip,
            'user' => $user,
            'method' => $method,
            'url' => $url,
            'status' => $status,
            'duration_ms' => $duration,
            'user_agent' => $userAgent,
            'context' => $context,
        ];

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }

    /**
     * @param  array<int, LogRecord>  $records
     */
    public function formatBatch(array $records): string
    {
        $output = '';
        foreach ($records as $record) {
            $output .= $this->format($record);
        }

        return $output;
    }

    private function pull(array &$context, string $key): mixed
    {
        if (!array_key_exists($key, $context)) {
            return null;
        }

        $value = $context[$key];
        unset($context[$key]);

        return $value;
    }
}
