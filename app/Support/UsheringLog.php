<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Convenience wrapper for the dedicated "ushering" log channel.
 *
 * Automatically enriches every entry with request/user metadata so that the
 * resulting JSON line always has the full structure the System Logs viewer
 * expects (ip, user, method, url, ...).
 */
class UsheringLog
{
    public static function record(string $message, array $context = [], string $level = 'info'): void
    {
        // Allow callers to nest extra fields under a "context" key; flatten it
        // so the formatter can render a single, non-nested context object.
        if (isset($context['context']) && is_array($context['context'])) {
            $context = array_merge($context, $context['context']);
            unset($context['context']);
        }

        try {
            Log::channel('ushering')->log($level, $message, array_merge(static::requestContext(), $context));
        } catch (Throwable $e) {
            // Logging must never break the request.
            try {
                Log::channel('single')->error('UsheringLog failed: ' . $e->getMessage());
            } catch (Throwable) {
                // Swallow.
            }
        }
    }

    public static function debug(string $message, array $context = []): void
    {
        static::record($message, $context, 'debug');
    }

    public static function info(string $message, array $context = []): void
    {
        static::record($message, $context, 'info');
    }

    public static function notice(string $message, array $context = []): void
    {
        static::record($message, $context, 'notice');
    }

    public static function warning(string $message, array $context = []): void
    {
        static::record($message, $context, 'warning');
    }

    public static function error(string $message, array $context = []): void
    {
        static::record($message, $context, 'error');
    }

    public static function critical(string $message, array $context = []): void
    {
        static::record($message, $context, 'critical');
    }

    /**
     * Log a named activity performed by a user.
     */
    public static function activity(string $action, array $context = [], string $level = 'info'): void
    {
        static::record($action, $context, $level);
    }

    /**
     * Build the base context shared by all entries.
     */
    protected static function requestContext(): array
    {
        $context = [];

        if (!app()->runningInConsole() && app()->bound('request')) {
            try {
                $request = request();
                $context['ip'] = $request->ip();
                $context['method'] = $request->method();
                $context['url'] = $request->fullUrl();
                $context['user_agent'] = mb_substr((string) $request->userAgent(), 0, 255);
            } catch (Throwable) {
                // Ignore request access failures.
            }
        }

        $context['user'] = static::currentUsername();

        return array_filter($context, static fn ($v) => $v !== null && $v !== '');
    }

    protected static function currentUsername(): ?string
    {
        try {
            $user = session('user');

            if (is_object($user)) {
                return $user->username ?? null;
            }

            if (is_array($user)) {
                return $user['username'] ?? null;
            }
        } catch (Throwable) {
            // No session (e.g. console / API).
        }

        return null;
    }
}
