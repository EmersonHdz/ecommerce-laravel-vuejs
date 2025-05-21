<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileShippingAddressRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class ProfileShippingAddressController extends Controller
{

    public function create(request $request){

          view('');
    }
    public function view(Request $request)
    {
        // Obtener el usuario actual
        $user = $request->user();

        // Obtener el cliente asociado al usuario actual
        $customer = $user->customer;

        // Obtener la dirección de envío del cliente si existe, si no, crear una nueva dirección de envío
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);

        // Obtener la lista de países ordenada alfabéticamente
        $countries = Country::query()->orderBy('name')->get();

        // Retornar la vista 'profile.view' pasando los datos del cliente, usuario, dirección de facturación y países
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'countries'));
    }

    public function update(ProfileShippingAddressRequest $request)
    {

       
        // Validar datos
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        // Obtener el usuario actual
        $user = $request->user();
        // Obtener el cliente asociado al usuario
        $customer = $user->customer;

        // Actualizar la información del cliente con los nuevos datos enviados en la solicitud
        $customer->update($customerData);

        // Verificar si el usuario actual ya tiene una dirección de envío.
        // Si la tiene, actualizamos la dirección existente con los nuevos datos.
        // Si no tiene una dirección de envío, creamos una nueva dirección.
        if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }

        // Mostrar un mensaje de éxito en la sesión del usuario
        $request->session()->flash('flash_message', 'Shipping Address was successfully updated.');

        // Redireccionar al perfil del usuario
        return redirect()->route('profile.view');
    }
}
