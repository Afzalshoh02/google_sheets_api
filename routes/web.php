<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\GoogleSheetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
    return redirect()->route('products.index');
})->name("home");

Route::resource('products', ProductController::class);
Route::get('/generate-records', [ProductController::class, 'generateRecords'])->name('generate.records');
Route::get('/truncate-records', [ProductController::class, 'truncateRecords'])->name('truncate.records');


Route::get('/google-sheets', [GoogleSheetController::class, 'index'])->name('google_sheets.index');
Route::put('/google-sheets', [GoogleSheetController::class, 'update'])->name('google_sheets.update');



Route::get('/fetch/{count?}', function ($count = null) {
    Artisan::call('google:fetch', ['count' => $count]);
    return nl2br(Artisan::output());
})->name('products.fetch');
