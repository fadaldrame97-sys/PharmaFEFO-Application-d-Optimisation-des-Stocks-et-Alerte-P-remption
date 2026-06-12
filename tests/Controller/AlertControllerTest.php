<?php

declare(strict_types=1);

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use AlertController;

class AlertControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testConstructorCreatesInstance(): void
    {
        $controller = new AlertController();
        $this->assertInstanceOf(AlertController::class, $controller);
    }
}
