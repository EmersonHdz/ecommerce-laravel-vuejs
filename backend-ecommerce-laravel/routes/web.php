<?php
require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileShippingAddressController;
use App\Http\Controllers\ProfileBillingAddressController;

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
    // Rutas relacionadas con el perfil del usuario
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile.view');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/password-update', [ProfileController::class, 'update'])->name('profile.password.update');

    //Rutas relacionadas con la dirección de envio del usuario
    Route::post('profile/shippin-addess-create', [ProfileShippingAddress::class, 'create'])->name(('shippingAddress.create'));
    Route::post('profile/shipping-address', [ProfileShippingAddressController::class, 'store'])->name('shippingAddress.store');
    Route::patch('profile/shipping-address-update', [ProfileShippingAddressController::class, 'update'])->name('shippingAddress.update');

        //Rutas relacionadas con la dirección de envio del usuario
    Route::get('/profile/billing-address', [ProfileBillingAddressController::class, 'view'])->name('profile.billing');
    Route::patch('/profile/billing-address', [ProfileBillingAddressController::class, 'update'])->name('profile.billing.update');

});

require __DIR__.'/auth.php';
