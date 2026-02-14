<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\LocaleController;


Route::get('lang/{locale}', [LocaleController::class, 'setLocale'])->name('lang.set');

Route::middleware(['locale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/payment/webhook', [\App\Http\Controllers\MoyasarController::class, 'webhook'])->name('moyasar.webhook');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart-data', [CartController::class, 'getCartDataResponse'])->name('cart.data');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->where('id', '[0-9]+')->name('cart.destroy');
    Route::post('/cart/update-zone', [CartController::class, 'updateZone'])->name('cart.zone.update');

    Route::middleware(['auth', 'status'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Customer Profile Routes
        Route::get('/my-account', [CustomerController::class, 'index'])->name('customer.profile');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
        Route::get('/orders/{id}', [CustomerController::class, 'orderDetails'])->name('customer.orders.show');
        Route::get('/favorites', [CustomerController::class, 'favorites'])->name('customer.favorites');
        Route::post('/favorites/{product}', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');

        // Payment Routes
        Route::get('/payment/callback', [\App\Http\Controllers\MoyasarController::class, 'callback'])->name('moyasar.callback');
        Route::get('/payment/{order}', [\App\Http\Controllers\MoyasarController::class, 'pay'])->name('moyasar.pay');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware('guest:admin')->group(function () {
            Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');
        });

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth:admin')
            ->name('logout');
    });

    Route::get('/dashboard/{any?}', function () {
        $user = \Illuminate\Support\Facades\Auth::user() ?: \Illuminate\Support\Facades\Auth::guard('admin')->user();
        
        if ($user && strtolower($user->role) === 'customer') {
            return redirect('/my-account')->with('error', 'Unauthorized access.');
        }

        $authUser = $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ] : null;
        return view('dashboard', ['authUser' => $authUser]);
    })->where('any', '.*')->middleware(['dashboard.access', 'auth:admin,web', 'role:admin,editor'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Dashboard Internal API Routes (Session Auth)
    Route::middleware(['dashboard.access', 'auth:admin,web', 'role:admin,editor'])->prefix('api')->name('api.')->group(function () {
        Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
        Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
        Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);
        Route::apiResource('orders', \App\Http\Controllers\Api\OrderController::class)->only(['index', 'show', 'update']);
        Route::apiResource('banners', \App\Http\Controllers\Api\BannerController::class)->except(['index']);
        Route::get('brands', [\App\Http\Controllers\Api\BrandController::class, 'index'])->name('brands.index');
        Route::get('dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats'])->name('dashboard.stats');
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Api\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Api\SettingController::class, 'update'])->name('settings.update');

        // Pages
        Route::apiResource('pages', \App\Http\Controllers\Api\PageController::class);
        Route::apiResource('authors', \App\Http\Controllers\Api\AuthorController::class);
        Route::apiResource('publishers', \App\Http\Controllers\Api\PublisherController::class);
        Route::apiResource('translators', \App\Http\Controllers\Api\TranslatorController::class);

        // Shipping
        Route::apiResource('shipping-companies', \App\Http\Controllers\Api\ShippingCompanyController::class);
        Route::apiResource('shipping-zones', \App\Http\Controllers\Api\ShippingZoneController::class);
        Route::apiResource('shipping-rates', \App\Http\Controllers\Api\ShippingRateController::class);
        Route::apiResource('coupons', \App\Http\Controllers\Api\CouponController::class);
        Route::apiResource('offers', \App\Http\Controllers\Api\OfferController::class);
        Route::apiResource('bank-accounts', \App\Http\Controllers\Api\BankAccountController::class);
        
        // Payments
        Route::apiResource('order-payments', \App\Http\Controllers\Api\OrderPaymentController::class)->only(['store', 'destroy']);
    });

    // Public API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('banners', [\App\Http\Controllers\Api\BannerController::class, 'index'])->name('banners.index');
    });

    Route::get('/p/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');


    require __DIR__ . '/auth.php';
});
