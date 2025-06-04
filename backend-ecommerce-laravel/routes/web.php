<?php
require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileBillingandShippingAddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{product:slug}', [ProductController::class, 'view'])->name('product.view');

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    // profile  routes
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile.view');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/password-update', [ProfileController::class, 'update'])->name('profile.password.update');

    //sending the user to the shipping address
    Route::get('/profile/billing-address', [ProfileBillingandShippingAddressController::class, 'view'])->name('profile.billing');
    Route::patch('/profile/billing-address', [ProfileBillingandShippingAddressController::class, 'update'])->name('profile.billing.update');

    // route for checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');

    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');

});
Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

require __DIR__.'/auth.php';
