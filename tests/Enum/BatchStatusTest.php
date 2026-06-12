<?php

declare(strict_types=1);

namespace Tests\Enum;

use PHPUnit\Framework\TestCase;
use BatchStatus;

class BatchStatusTest extends TestCase
{
    public function testAllCasesExist(): void
    {
        $cases = BatchStatus::cases();
        $this->assertCount(4, $cases);
    }

    public function testOkCase(): void
    {
        $status = BatchStatus::OK;
        $this->assertSame('OK', $status->name);
    }

    public function testWarningCase(): void
    {
        $status = BatchStatus::WARNING;
        $this->assertSame('WARNING', $status->name);
    }

    public function testCriticalCase(): void
    {
        $status = BatchStatus::CRITICAL;
        $this->assertSame('CRITICAL', $status->name);
    }

    public function testExpiredCase(): void
    {
        $status = BatchStatus::EXPIRED;
        $this->assertSame('EXPIRED', $status->name);
    }

    public function testCaseNames(): void
    {
        $expectedNames = ['OK', 'WARNING', 'CRITICAL', 'EXPIRED'];
        $actualNames = array_map(fn(BatchStatus $s) => $s->name, BatchStatus::cases());
        $this->assertSame($expectedNames, $actualNames);
    }

    public function testEnumIsNotBacked(): void
    {
        $reflection = new \ReflectionEnum(BatchStatus::class);
        $this->assertFalse($reflection->isBacked());
    }
}
