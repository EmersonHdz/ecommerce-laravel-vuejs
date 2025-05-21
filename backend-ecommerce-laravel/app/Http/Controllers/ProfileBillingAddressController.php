<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileBillingAddressRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Enums\AddressType;
use Illuminate\Http\Request;

class ProfileBillingAddressController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $countries = Country::query()->orderBy('name')->get();

        return view('profile.view', compact('customer', 'user', 'billingAddress', 'countries'));
    }

    public function update(ProfileBillingAddressRequest $request)
    {
        $customerData = $request->validated();
        $billingData = $customerData['billing'];

        $user = $request->user();
        $customer = $user->customer;

        $customer->update($customerData);

        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Billing Address was successfully updated.');

        return redirect()->route('profile.view');
    }
}
