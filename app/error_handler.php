<?php
namespace app;

use ErrorException;
use Exception;


/**
 * Error handler, passes flow over the exception logger with new ErrorException.
 */
function log_error($num, $str, $file, $line, $context = null) {
    log_exception(new ErrorException($str, 0, $num, $file, $line));
}

/**
 * Uncaught exception handler.
 */
function log_exception(Exception $e) {
    $handler = new kontrolery\Error();
    if (DEBUG) {
        $handler->debugError($e);
    } else {
        $handler->error500($e);
    }
}

/**
 * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
 */
function check_for_fatal() {
    $error = error_get_last();

    switch ($error['type']) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_PARSE:
            log_error($error["type"], $error["message"], $error["file"], $error["line"]);
    }
    die();
}

ini_set("display_errors", "on");
error_reporting(E_ALL);
set_error_handler('app\log_error');
set_exception_handler('app\log_exception');
register_shutdown_function('app\check_for_fatal');
