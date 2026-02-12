<?php

require_once __DIR__ . '/logger.php';

/*
|--------------------------------------------------------------------------
| Production Safe
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 0);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| Convert PHP Errors to Exceptions
|--------------------------------------------------------------------------
*/
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

/*
|--------------------------------------------------------------------------
| Global Exception Handler
|--------------------------------------------------------------------------
*/
set_exception_handler(function ($exception) {

    $rawInput = file_get_contents('php://input');
    $decodedInput = json_decode($rawInput, true);

    // Hide passwords if exist
    if (isset($decodedInput['password'])) {
        $decodedInput['password'] = '***hidden***';
    }

    writeLog('critical', 'Unhandled Exception', [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'input' => $decodedInput
    ]);

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Internal server error'
    ]);

    exit;
});
