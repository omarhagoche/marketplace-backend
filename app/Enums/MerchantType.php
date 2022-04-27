<?php

namespace App\Enums;

/**
 * Enum MerchantType define all posible merchant types 
 *
 * @package App\Enums
 */
class MerchantType {
    const DEFAULT = self::RESTAURANT;
    
    const RESTAURANT = 'RESTAURANT';
    const SUPERMARKET = 'SUPERMARKET';
    const SHOP = 'SHOP';
}