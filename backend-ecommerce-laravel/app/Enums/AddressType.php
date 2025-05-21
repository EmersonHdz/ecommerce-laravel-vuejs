<?php
/**
 * User: Emerson
 * Date: 15/05/2025
 * Time: 00:00 MDT
 */

namespace App\Enums;


/**
 * Class AddressType
 *
 * @author  Emerson Hernandez <emersonhdz94@gmail.com>
 * @package App\Enums
 */
enum AddressType: string
{
    case Shipping = 'shipping';
    case Billing = 'billing';
}