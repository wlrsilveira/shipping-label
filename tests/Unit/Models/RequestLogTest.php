<?php

namespace Tests\Unit\Models;

use App\Models\RequestLog;
use PHPUnit\Framework\TestCase;

class RequestLogTest extends TestCase
{
    public function test_table_name_is_correct(): void
    {
        $log = new RequestLog();

        $this->assertEquals('request_logs', $log->getTable());
    }

    public function test_fillable_contains_correct_fields(): void
    {
        $log = new RequestLog();
        $fillable = $log->getFillable();

        $this->assertContains('domain', $fillable);
        $this->assertContains('endpoint', $fillable);
        $this->assertContains('payload', $fillable);
        $this->assertContains('response', $fillable);
        $this->assertContains('status_code', $fillable);
    }

    public function test_casts_contains_correct_configurations(): void
    {
        $log = new RequestLog();
        $casts = $log->getCasts();

        $this->assertArrayHasKey('payload', $casts);
        $this->assertArrayHasKey('response', $casts);
        $this->assertArrayHasKey('status_code', $casts);
        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);

        $this->assertEquals('array', $casts['payload']);
        $this->assertEquals('array', $casts['response']);
        $this->assertEquals('integer', $casts['status_code']);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
    }
}
