<?php


namespace App\Enums;


/**
 * Class OrderStatus
 *
 * @package App\Enums
 */
enum OrderStatus: string
{   
    case Pending = 'pending';
    case Cancelled = 'cancelled';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Paid = 'paid';
    case Unpaid = 'unpaid';

    public static function getStatuses()
    {
        return [
             self::Pending, self::Cancelled, self::Shipped, self::Completed
        ];
    }
}
