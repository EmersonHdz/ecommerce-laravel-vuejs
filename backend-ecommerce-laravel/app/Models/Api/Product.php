<?php

namespace App\Models\Api;


/**
 * API-specific Product model.
 * Overrides the default route key to use `id` instead of `slug` for API endpoints.
 * This improves query efficiency in APIs where slugs are unnecessary.
 */
class Product extends \App\Models\Product
{
     /**
     * Defines `id` as the route key for API URLs.
     * Example: /api/products/1 (instead of /api/products/product-slug).
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id'; // Optimized for API performance
    }
}