<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileBillingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Billing address rules (required)
            'billing.address1' => ['required', 'string'],
            'billing.address2' => ['nullable', 'string'],
            'billing.city' => ['required', 'string'],
            'billing.state' => ['required', 'string'],
            'billing.zipcode' => ['required', 'string'],
            'billing.country_code' => ['required', 'exists:countries,code'],

            // Shipping address rules (optional if not submitted)
            'shipping.address1' => ['sometimes', 'required', 'string'],
            'shipping.address2' => ['sometimes', 'nullable', 'string'],
            'shipping.city' => ['sometimes', 'required', 'string'],
            'shipping.state' => ['sometimes', 'required', 'string'],
            'shipping.zipcode' => ['sometimes', 'required', 'string'],
            'shipping.country_code' => ['sometimes', 'required', 'exists:countries,code'],
        ];
    }

    /**
     * Custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            // Billing labels
            'billing.address1' => 'billing address 1',
            'billing.address2' => 'billing address 2',
            'billing.city' => 'billing city',
            'billing.state' => 'billing state',
            'billing.zipcode' => 'billing zip code',
            'billing.country_code' => 'billing country',

            // Shipping labels
            'shipping.address1' => 'shipping address 1',
            'shipping.address2' => 'shipping address 2',
            'shipping.city' => 'shipping city',
            'shipping.state' => 'shipping state',
            'shipping.zipcode' => 'shipping zip code',
            'shipping.country_code' => 'shipping country',
        ];
    }
}
