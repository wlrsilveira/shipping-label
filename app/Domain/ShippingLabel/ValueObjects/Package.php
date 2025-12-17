<?php

namespace App\Domain\ShippingLabel\ValueObjects;

use InvalidArgumentException;

final readonly class Package
{
    private const MIN_WEIGHT = 0.01;
    private const MAX_WEIGHT = 150.0;
    private const MIN_DIMENSION = 0.01;
    private const MAX_DIMENSION = 108.0;

    public function __construct(
        private float $weight,
        private float $length,
        private float $width,
        private float $height,
        private WeightUnit $weightUnit = WeightUnit::POUND,
        private DimensionUnit $dimensionUnit = DimensionUnit::INCH,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        $this->validateWeight();
        $this->validateDimensions();
    }

    private function validateWeight(): void
    {
        if ($this->weight < self::MIN_WEIGHT) {
            throw new InvalidArgumentException(
                sprintf('Weight must be at least %.2f %s', self::MIN_WEIGHT, $this->weightUnit->value)
            );
        }

        if ($this->weight > self::MAX_WEIGHT) {
            throw new InvalidArgumentException(
                sprintf('Weight cannot exceed %.2f %s', self::MAX_WEIGHT, $this->weightUnit->value)
            );
        }
    }

    private function validateDimensions(): void
    {
        $dimensions = [
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
        ];

        foreach ($dimensions as $name => $value) {
            if ($value < self::MIN_DIMENSION) {
                throw new InvalidArgumentException(
                    sprintf('%s must be at least %.2f %s', ucfirst($name), self::MIN_DIMENSION, $this->dimensionUnit->value)
                );
            }

            if ($value > self::MAX_DIMENSION) {
                throw new InvalidArgumentException(
                    sprintf('%s cannot exceed %.2f %s', ucfirst($name), self::MAX_DIMENSION, $this->dimensionUnit->value)
                );
            }
        }
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getWeightUnit(): WeightUnit
    {
        return $this->weightUnit;
    }

    public function getWeightUnitValue(): string
    {
        return $this->weightUnit->value;
    }

    public function getDimensionUnit(): DimensionUnit
    {
        return $this->dimensionUnit;
    }

    public function getDimensionUnitValue(): string
    {
        return $this->dimensionUnit->value;
    }

    public function getVolume(): float
    {
        return round($this->length * $this->width * $this->height, 2);
    }

    public function toArray(): array
    {
        return [
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight_unit' => $this->weightUnit->value,
            'dimension_unit' => $this->dimensionUnit->value,
        ];
    }

    public function equals(Package $other): bool
    {
        return abs($this->weight - $other->weight) < 0.001
            && abs($this->length - $other->length) < 0.001
            && abs($this->width - $other->width) < 0.001
            && abs($this->height - $other->height) < 0.001
            && $this->weightUnit === $other->weightUnit
            && $this->dimensionUnit === $other->dimensionUnit;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            weight: (float) $data['weight'],
            length: (float) $data['length'],
            width: (float) $data['width'],
            height: (float) $data['height'],
            weightUnit: WeightUnit::fromString($data['weight_unit']),
            dimensionUnit: DimensionUnit::fromString($data['dimension_unit']),
        );
    }
}

