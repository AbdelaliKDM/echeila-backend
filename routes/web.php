<?php

use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\Auth\ForgotPassword;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\front_pages\HelpCenterArticle;
use App\Http\Controllers\Dashboard\Roles\RolesController;
use App\Http\Controllers\Dashboard\Users\UsersController;
use App\Http\Controllers\Dashboard\Permissions\PermissionsController;


// locale
Route::get('/lang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
Route::get('/pages/misc-comingsoon', [MiscComingSoon::class, 'index'])->name('pages-misc-comingsoon');
Route::get('/pages/misc-not-authorized', [MiscNotAuthorized::class, 'index'])->name('pages-misc-not-authorized');

// Front Pages
Route::get('/', [Landing::class, 'index'])->name('front-pages-landing');
Route::get('/pricing', [Pricing::class, 'index'])->name('front-pages-pricing');
Route::get('/payment', [Payment::class, 'index'])->name('front-pages-payment');
Route::get('/checkout', [Checkout::class, 'index'])->name('front-pages-checkout');
Route::get('/help-center', [HelpCenter::class, 'index'])->name('front-pages-help-center');
Route::get('/help-center-article', [HelpCenterArticle::class, 'index'])->name('front-pages-help-center-article');


// Auth Routes

Route::get('login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'action'])->middleware('guest')->name('login.action');
Route::get('forgot-password', [ForgotPassword::class, 'index'])->middleware('guest')->name('reset-password');
Route::get('logout', [LogoutController::class, 'action'])->middleware('auth')->name('logout');
Route::get('register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
Route::post('register', [RegisterController::class, 'action'])->middleware('guest')->name('register.action');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    Route::resource('roles', RolesController::class);

    Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [PermissionsController::class, 'update'])->name('permissions.update');

    Route::resource('users', UsersController::class)->except(['show']);

});
