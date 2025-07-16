<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $perPage = request('per_page', 10);
    $search = request('search', '');
    $sortField = request('sort_field', 'updated_at');
    $sortDirection = request('sort_direction', 'desc');

    $query = Customer::query()
        ->whereHas('user', function ($q) {
            $q->where('role', 'customer'); // role 'customer'
        })
        ->with('user')
        ->orderBy("customers.$sortField", $sortDirection);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('email', 'like', "%{$search}%");
              });
        });
    }

    $paginator = $query->paginate($perPage);

    return CustomerListResource::collection($paginator);
}


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
     
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer     $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customerData = $request->validated();
        $customerData['updated_by'] = $request->user()->id;
        $customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
        $shippingData = $customerData['shippingAddress'];
        $billingData = $customerData['billingAddress'];

        DB::beginTransaction();
        try {
            $customer->update($customerData);

            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        DB::commit();

        return new CustomerResource($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CustomerRequest $request
     * @return \Illuminate\Http\Response
     */

    public function store(CustomerRequest $request)
  {
    $data = $request->validated();

    DB::beginTransaction();

    try {
        // create user first
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'] ?? null, 
        ]);

        // 2. create customer
        $data['user_id'] = $user->id;
        $data['status'] = $data['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
        $data['created_by'] = auth()->id();

        $customer = Customer::create($data);



        // 3. create addresses
        $data['billingAddress']['customer_id'] = $customer->user_id;
        $data['billingAddress']['type'] = AddressType::Billing->value;

        $data['shippingAddress']['customer_id'] = $customer->user_id;
        $data['shippingAddress']['type'] = AddressType::Shipping->value;

        CustomerAddress::create($data['billingAddress']);
        CustomerAddress::create($data['shippingAddress']);

        DB::commit();
        $customer = Customer::with('user')->find($customer->user_id);

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::critical('Error creating customer: ' . $e->getMessage());
        return response()->json(['message' => 'Could not create customer'], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
   
    public function destroy(Customer $customer)
{
    DB::beginTransaction();
    try {
        // Eliminar primero las direcciones asociadas
        $customer->billingAddress()?->delete();
        $customer->shippingAddress()?->delete();

        // Luego el customer
        $customer->delete();

        DB::commit();
        return response()->noContent();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error deleting customer: " . $e->getMessage());
        return response()->json(['message' => 'Could not delete customer'], 500);
    }
}


    public function countries()
    {
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
    }




}