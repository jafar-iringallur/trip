<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('dashboard.index');
    });

    Route::prefix('places')->group(function () {
        Route::get('/', [PlaceController::class, 'index'])->name('places.index');
        Route::get('/getData', [PlaceController::class, 'getData'])->name('places.getData');
        Route::post('/add', [PlaceController::class, 'add'])->name('places.add');
        Route::delete('/delete', [PlaceController::class, 'delete'])->name('places.delete');
    });
 
});

require __DIR__.'/auth.php';
