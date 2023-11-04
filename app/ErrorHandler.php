<?php

namespace App;

use Bead\ErrorHandler as BaseErrorHandler;
use Throwable;

/**
 * The app's error handler.
 *
 * This is an empty extension of the bead ErrorHandler. The base error handler is probably sufficient for the purposes
 * of the app, but you may want to pass the error/exception on to some bug tracking service such as BugSnag or Sentry.
 * If so, add your functionality in the `handleError()` and `handleException()` stubs before delegating to the base
 * class methods.
 */
class ErrorHandler extends BaseErrorHandler
{
    /**
     * Handle a PHP error.
     *
     * @param int $type The error type.
     * @param string $message The error message.
     * @param string $file The file where the error occurred.
     * @param int $line The location in the file where the error occurred.
     */
    public function handleError(int $type, string $message, string $file, int $line): void
    {
        // customise error handling here - e.g. do you use BugSnag?
        parent::handleError($type, $message, $file, $line);
    }

    /**
     * Handle an exception
     *
     * @param Throwable $err The exception that occurred.
     */
    public function handleException(Throwable $err): void
    {
        // customise error handling here - e.g. do you use BugSnag?
        parent::handleException($err);
    }
}