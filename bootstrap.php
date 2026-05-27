<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/*
APP CONFIG
*/

define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');

$isDevelopment = APP_ENV === 'development';

/*
ERROR DISPLAY
*/

ini_set('display_errors', $isDevelopment ? '1' : '0');
ini_set('display_startup_errors', $isDevelopment ? '1' : '0');

error_reporting(E_ALL);

/*
LOG DIRECTORY
*/

$logDir = __DIR__ . '/logs';

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

/*
ERROR HANDLER
*/

set_error_handler(function (
    $severity,
    $message,
    $file,
    $line
) use ($logDir, $isDevelopment) {

    $logMessage =
        '[' . date('Y-m-d H:i:s') . '] ERROR: '
        . $message
        . ' in '
        . $file
        . ' on line '
        . $line
        . PHP_EOL;

    error_log(
        $logMessage,
        3,
        $logDir . '/app.log'
    );

    if ($isDevelopment) {
        echo nl2br(htmlspecialchars($logMessage));
    }

    return true;
});

/*
EXCEPTION HANDLER
*/

set_exception_handler(function ($exception) use ($logDir, $isDevelopment) {

    $logMessage =
        '[' . date('Y-m-d H:i:s') . '] EXCEPTION: '
        . $exception->getMessage()
        . ' in '
        . $exception->getFile()
        . ' on line '
        . $exception->getLine()
        . PHP_EOL
        . $exception->getTraceAsString()
        . PHP_EOL . PHP_EOL;

    error_log(
        $logMessage,
        3,
        $logDir . '/app.log'
    );

    http_response_code(500);

    if ($isDevelopment) {
        echo nl2br(htmlspecialchars($logMessage));
    } else {
        echo "Dogodila se greška u aplikaciji.";
    }
});

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}