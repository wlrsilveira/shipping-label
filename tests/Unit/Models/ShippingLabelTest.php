<?php

namespace Tests\Unit\Models;

use App\Models\ShippingLabel;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ShippingLabelTest extends TestCase
{
    public function test_table_name_esta_correto(): void
    {
        $label = new ShippingLabel();

        $this->assertEquals('shipping_labels', $label->getTable());
    }

    public function test_fillable_contem_campos_corretos(): void
    {
        $label = new ShippingLabel();
        $fillable = $label->getFillable();

        $expectedFields = [
            'user_id',
            'from_address',
            'to_address',
            'package_data',
            'external_shipment_id',
            'provider',
            'label_url',
            'tracking_code',
            'status',
            'carrier',
            'service',
            'rate',
        ];

        foreach ($expectedFields as $field) {
            $this->assertContains($field, $fillable);
        }
    }

    public function test_casts_contem_configuracoes_corretas(): void
    {
        $label = new ShippingLabel();
        $casts = $label->getCasts();

        $this->assertArrayHasKey('from_address', $casts);
        $this->assertArrayHasKey('to_address', $casts);
        $this->assertArrayHasKey('package_data', $casts);
        $this->assertArrayHasKey('rate', $casts);
        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);

        $this->assertEquals('array', $casts['from_address']);
        $this->assertEquals('array', $casts['to_address']);
        $this->assertEquals('array', $casts['package_data']);
        $this->assertEquals('decimal:2', $casts['rate']);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
    }

    public function test_metodo_user_existe(): void
    {
        $label = new ShippingLabel();

        $this->assertTrue(method_exists($label, 'user'));
    }
}

