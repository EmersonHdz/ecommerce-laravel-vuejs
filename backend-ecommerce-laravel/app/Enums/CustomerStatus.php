<?php


namespace App\Enums;


/**
 * Class CustomerStatus
 *
 */
enum CustomerStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';
}