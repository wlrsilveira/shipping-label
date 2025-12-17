<?php

namespace App\Domain\ShippingLabel\ValueObjects;

use InvalidArgumentException;

final readonly class Address
{
    public function __construct(
        private string $street1,
        private ?string $street2,
        private string $city,
        private USState $state,
        private string $zipCode,
        private Country $country,
        private ?string $name = null,
        private ?string $phone = null,
        private ?string $company = null,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty(trim($this->street1))) {
            throw new InvalidArgumentException('Street address cannot be empty');
        }

        if (strlen($this->street1) > 255) {
            throw new InvalidArgumentException('Street address cannot exceed 255 characters');
        }

        if ($this->street2 !== null && strlen($this->street2) > 255) {
            throw new InvalidArgumentException('Street address line 2 cannot exceed 255 characters');
        }

        if (empty(trim($this->city))) {
            throw new InvalidArgumentException('City cannot be empty');
        }

        if (strlen($this->city) > 100) {
            throw new InvalidArgumentException('City cannot exceed 100 characters');
        }

        if (! $this->isValidZipCode($this->zipCode)) {
            throw new InvalidArgumentException('Invalid US ZIP code format');
        }

        if ($this->phone !== null && ! empty(trim($this->phone))
            && ! $this->isValidPhone($this->phone)
        ) {
            throw new InvalidArgumentException('Invalid phone number format');
        }

        if ($this->name !== null && strlen($this->name) > 255) {
            throw new InvalidArgumentException('Name cannot exceed 255 characters');
        }

        if ($this->company !== null && strlen($this->company) > 255) {
            throw new InvalidArgumentException('Company name cannot exceed 255 characters');
        }
    }

    private function isValidZipCode(string $zipCode): bool
    {
        $pattern = '/^\d{5}(-\d{4})?$/';
        return preg_match($pattern, trim($zipCode)) === 1;
    }

    private function isValidPhone(string $phone): bool
    {
        $digitsOnly = preg_replace('/\D/', '', $phone);
        return strlen($digitsOnly) >= 10 && strlen($digitsOnly) <= 15;
    }

    public function getStreet1(): string
    {
        return $this->street1;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): USState
    {
        return $this->state;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function toArray(): array
    {
        return [
            'street1' => $this->street1,
            'street2' => $this->street2,
            'city' => $this->city,
            'state' => $this->state->value,
            'zip' => $this->zipCode,
            'country' => $this->country->value,
            'name' => $this->name,
            'phone' => $this->phone,
            'company' => $this->company,
        ];
    }

    public function equals(Address $other): bool
    {
        return $this->street1 === $other->street1
            && $this->street2 === $other->street2
            && $this->city === $other->city
            && $this->state === $other->state
            && $this->zipCode === $other->zipCode
            && $this->country === $other->country;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            street1: $data['street1'],
            street2: $data['street2'] ?? null,
            city: $data['city'],
            state: USState::fromString($data['state']),
            zipCode: $data['zip'],
            country: Country::fromString($data['country'] ?? Country::UNITED_STATES->value),
            name: $data['name'] ?? null,
            phone: $data['phone'] ?? null,
            company: $data['company'] ?? null,
        );
    }
}

