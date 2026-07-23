<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController, ProductController, OrderController, PaymentController
};

Route::get('/', function () {
    $products = Product::latest()->cursorPaginate(10);
    return view('welcome',['products'=>$products]);
})->name('welcome');

Route::get('/login', [AuthController::class, 'loginPage'])->name('login-page');
Route::post('/loggedIn', [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'registrationPage'])->name('register-page');
Route::post('/registration', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('products/show/{product}', [ProductController::class, 'userShow'])->name('user.products.show');
Route::post('add-to-cart',[OrderController::class,'store'])->name('add.to.cart');
Route::get('cart',[OrderController::class,'index'])->name('order.index');

Route::post('checkout',[PaymentController::class,'checkout'])->name('order.checkout');
Route::get('payment/success/{order}',[PaymentController::class,'success'])->name('order.success');
Route::get('payment/cancel/{order}',[PaymentController::class,'cancel'])->name('order.cancel');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.product.index');
        Route::get('/create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/show/{product}', [ProductController::class, 'show'])->name('admin.product.show');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.delete');
    });
});
