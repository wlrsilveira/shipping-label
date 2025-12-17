<?php

namespace App\Http\Requests;

use App\Domain\ShippingLabel\ValueObjects\Country;
use App\Domain\ShippingLabel\ValueObjects\DimensionUnit;
use App\Domain\ShippingLabel\ValueObjects\USState;
use App\Domain\ShippingLabel\ValueObjects\WeightUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateShippingLabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $usStates = array_map(fn($state) => $state->value, USState::cases());
        $countries = array_map(fn($country) => $country->value, Country::cases());
        $weightUnits = array_map(fn($unit) => $unit->value, WeightUnit::cases());
        $dimensionUnits = array_map(fn($unit) => $unit->value, DimensionUnit::cases());

        return [
            'from_address' => ['required', 'array'],
            'from_address.street1' => ['required', 'string', 'max:255'],
            'from_address.street2' => ['nullable', 'string', 'max:255'],
            'from_address.city' => ['required', 'string', 'max:100'],
            'from_address.state' => ['required', 'string', Rule::in($usStates)],
            'from_address.zip' => ['required', 'string', 'regex:/^\d{5}(-\d{4})?$/'],
            'from_address.country' => ['nullable', 'string', Rule::in($countries)],
            'from_address.name' => ['nullable', 'string', 'max:255'],
            'from_address.phone' => ['nullable', 'string', 'max:20'],
            'from_address.company' => ['nullable', 'string', 'max:255'],

            'to_address' => ['required', 'array'],
            'to_address.street1' => ['required', 'string', 'max:255'],
            'to_address.street2' => ['nullable', 'string', 'max:255'],
            'to_address.city' => ['required', 'string', 'max:100'],
            'to_address.state' => ['required', 'string', Rule::in($usStates)],
            'to_address.zip' => ['required', 'string', 'regex:/^\d{5}(-\d{4})?$/'],
            'to_address.country' => ['nullable', 'string', Rule::in($countries)],
            'to_address.name' => ['nullable', 'string', 'max:255'],
            'to_address.phone' => ['nullable', 'string', 'max:20'],
            'to_address.company' => ['nullable', 'string', 'max:255'],

            'package' => ['required', 'array'],
            'package.weight' => ['required', 'numeric', 'min:0.01', 'max:150.0'],
            'package.length' => ['required', 'numeric', 'min:0.01', 'max:108.0'],
            'package.width' => ['required', 'numeric', 'min:0.01', 'max:108.0'],
            'package.height' => ['required', 'numeric', 'min:0.01', 'max:108.0'],
            'package.weight_unit' => ['nullable', 'string', Rule::in($weightUnits)],
            'package.dimension_unit' => ['nullable', 'string', Rule::in($dimensionUnits)],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (isset($this->from_address) && !isset($this->from_address['country'])) {
            $this->merge([
                'from_address' => array_merge($this->from_address, ['country' => Country::UNITED_STATES->value]),
            ]);
        }

        if (isset($this->to_address) && !isset($this->to_address['country'])) {
            $this->merge([
                'to_address' => array_merge($this->to_address, ['country' => Country::UNITED_STATES->value]),
            ]);
        }

        if (isset($this->package) && !isset($this->package['weight_unit'])) {
            $this->merge([
                'package' => array_merge($this->package, ['weight_unit' => WeightUnit::POUND->value]),
            ]);
        }

        if (isset($this->package) && !isset($this->package['dimension_unit'])) {
            $this->merge([
                'package' => array_merge($this->package, ['dimension_unit' => DimensionUnit::INCH->value]),
            ]);
        }
    }
}

