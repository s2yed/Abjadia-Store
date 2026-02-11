<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', [LocaleController::class, 'setLocale'])->name('lang.set');

Route::middleware(['locale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/payment/webhook', [\App\Http\Controllers\MoyasarController::class, 'webhook'])->name('moyasar.webhook');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

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
        Route::apiResource('banners', \App\Http\Controllers\Api\BannerController::class);
        Route::get('brands', [\App\Http\Controllers\Api\BrandController::class, 'index'])->name('brands.index');
        Route::get('dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats'])->name('dashboard.stats');
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Api\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Api\SettingController::class, 'update'])->name('settings.update');

        // Pages
        Route::apiResource('pages', \App\Http\Controllers\Api\PageController::class);
    });

    Route::get('/p/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

    require __DIR__ . '/auth.php';
});
