<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = [ 'user_id','first_name', 'last_name', 'phone', 'status',];

    public function user()
    {
          return $this->belongsTo(User::class, 'user_id');
    }


    private function _getAddresses(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'user_id');
    }

    public function shippingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Shipping->value);
    }

    public function billingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Billing->value);
    }

}

