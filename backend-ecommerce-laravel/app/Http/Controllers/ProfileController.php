<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    
    public function view(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
//        dd($customer, $shippingAddress->attributesToArray(), $billingAddress, $billingAddress->customer);
        $countries = Country::query()->orderBy('name')->get();
   
        return view('profile.view', compact('customer', 'user', 'countries' , 'billingAddress', 'shippingAddress'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $user = $request->user();
        $customer = $user->customer;

        $customer->update($customerData);


        $request->session()->flash('flash_message', 'Profile was successfully updated.');

        return redirect()->route('profile.view');

    }

    public function update(PasswordUpdateRequest $request)
    {
       
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', 'Your password was successfully updated.');

        return redirect()->route('profile.view');
    }
}
