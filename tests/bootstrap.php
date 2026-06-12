<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Start a session for controller tests to prevent notices
// from session_start() calls in controller constructors
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
