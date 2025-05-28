<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProfileBillingAddressRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Enums\AddressType;
use Illuminate\Http\Request;

class ProfileBillingandShippingAddressController extends Controller
{
    /**
     * Show the profile address form.
     */
    public function view(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;

        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);

        $countries = Country::query()->orderBy('name')->get();

        return view('profile.view', compact('customer', 'user', 'billingAddress', 'shippingAddress', 'countries'));
    }

    /**
     * Handle update of billing and shipping addresses.
     */
    public function update(ProfileBillingAddressRequest $request)
    {
        
        $validatedData = $request->validated();

        $billingData = $validatedData['billing'] ?? [];
        $shippingData = $validatedData['shipping'] ?? [];

        // If no shipping data was submitted, fallback to billing data
        if (empty($shippingData)) {
            $shippingData = $billingData;
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        DB::beginTransaction();

        try {
            // Update customer basic info if any (optional)
            $customer->update($validatedData);

            // Update or create shipping address
            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            // Update or create billing address
            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }

            DB::commit();

            $request->session()->flash('flash_message', 'Profile successfully updated.');

            return redirect()->route('profile.view');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' failed: ' . $e->getMessage());
            throw $e;
        }
    }
}