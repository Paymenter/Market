<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::post('/webhook', [App\Http\Controllers\WebhookController::class, 'handleStripeWebhook']);
Route::post('/pwebhook', [App\Http\Controllers\WebhookController::class, 'handlePaypalWebhook'])->name('paypal.webhook');
Route::group(['prefix' => 'settings', 'middleware' => 'verified'], function () {
    Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/connect', [App\Http\Controllers\SettingsController::class, 'connect'])->name('settings.connect');
    Route::post('/paypal', [App\Http\Controllers\SettingsController::class, 'paypal'])->name('settings.paypal');
    Route::post('/', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/{username}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::get('/{username}/resources', [App\Http\Controllers\UserController::class, 'resources'])->name('user.resources');
});
Route::get('/resources', [App\Http\Controllers\ResourceController::class, 'index'])->name('resources.index');

Route::group(['prefix' => 'resource'], function () {
    Route::get('/create', [App\Http\Controllers\ResourceController::class, 'create'])->name('resource.create')->middleware('verified');
    Route::post('/create', [App\Http\Controllers\ResourceController::class, 'store'])->name('resource.store')->middleware('verified');
    Route::get('/{resource}', [App\Http\Controllers\ResourceController::class, 'show'])->name('resource.show');
    Route::get('/{resource}/edit', [App\Http\Controllers\ResourceController::class, 'edit'])->name('resource.edit')->middleware('verified');
    Route::get('/{resource}/stripe', [App\Http\Controllers\ResourceController::class, 'buyStripe'])->name('resource.buy.stripe')->middleware('verified');
    Route::get('/{resource}/paypal', [App\Http\Controllers\ResourceController::class, 'buyPaypal'])->name('resource.buy.paypal')->middleware('verified');
    Route::put('/{resource}/edit', [App\Http\Controllers\ResourceController::class, 'update'])->name('resource.update')->middleware('verified');
    Route::post('/{resource}/delete', [App\Http\Controllers\ResourceController::class, 'delete'])->name('resource.delete')->middleware('verified');
    Route::get('/{resource}/download', [App\Http\Controllers\ResourceController::class, 'download'])->name('resource.download')->middleware('verified');
    Route::post('/{resource}/review', [App\Http\Controllers\ResourceController::class, 'review'])->name('resource.review')->middleware('verified');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
    Route::get('/resources', [App\Http\Controllers\Admin\ResourceController::class, 'index'])->name('admin.resources.index');
    Route::get('/resources/{resource}', [App\Http\Controllers\Admin\ResourceController::class, 'show'])->name('admin.resources.show');
    Route::get('/resources/{resource}/approve', [App\Http\Controllers\Admin\ResourceController::class, 'approve'])->name('admin.resources.approve');
    Route::get('/resources/{resource}/reject', [App\Http\Controllers\Admin\ResourceController::class, 'reject'])->name('admin.resources.reject');
    Route::get('/resources/{resource}/delete', [App\Http\Controllers\Admin\ResourceController::class, 'delete'])->name('admin.resources.delete');
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
});

require __DIR__ . '/auth.php';
