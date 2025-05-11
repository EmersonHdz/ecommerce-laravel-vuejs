<?php
/**
 * User: Emerson
 * Date: 4/16/2025
 * Time: 5:26 AM
 */

namespace App\Helpers;


use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;



/**
 * Class Cart
 *
 * @author  emerson hernandez <
 * @package App\Helpers
 */

 class Cart {
  
    /**
     * Get cart items count from DB or cookies
     * and sum the quantity of each item
     * @return int
     */
         
    public static function getCartItemsCount(): int
    {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                0
            );
        }
    }  
   

    /**
     * Get cart items from DB or cookies
     * @return array
     */

    public static function getCartItems()
    {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }
    

    /**
     * Get cart items from cookies
     * @return array
     */

    public static function getCookieCartItems()
    {
        $request = \request();
        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    public static function getCountFromItems($cartItems)
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }
    

    /**
     * Move cart items from cookies to DB when user logs in or registers
     * @return void
     */

    public static function moveCartItemsIntoDb()
    {
        $request = \request();
            $request = \request();
            $user = $request->user(); // Get the authenticated user

    if (!$user) {
        return;
    }
        $cartItems = self::getCookieCartItems();
        CartItem::where('user_id', $user->id)->delete();

        $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy('product_id');
        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }
            $newCartItems[] = [
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
       
        cookie()->queue(cookie('cart_items', '', -1)); //delete cookie
        
    }



    /**
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @author emerson hernandez <emersonhdz94@gmail.com>
     */
    public static function getProductsAndCartItems(): array|\Illuminate\Database\Eloquent\Collection
    {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');

        return [$products, $cartItems];
    }

     /**
      * Move cart items from DB to cookies when user logs out
      * @return void
      */
    

    public static function moveDbCartItemsIntoCookies(): void
{
    $request = \request();
    $user = $request->user();

    if (!$user) {
        return;
    }

    $dbCartItems = CartItem::where('user_id', $user->id)->get();

    $cookieCartItems = [];

    foreach ($dbCartItems as $item) {
        $cookieCartItems[] = [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
        ];
    }

    // Save the cart items in cookies
    cookie()->queue(cookie(
        'cart_items',
        json_encode($cookieCartItems),
        60 * 24 * 7 // coookie (7 days)
    ));
}


 }