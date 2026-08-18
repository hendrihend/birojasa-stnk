<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\STNKRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->middleware('can:super_admin');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('transactions', TransactionController::class);

    Route::get('/vehicles/{vehicle_id}/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/vehicles/{vehicle_id}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::resource('stnk_records', STNKRecordController::class);
    Route::get('/scan-qr', function() {
        return view('scan');
    })->name('scan.qr');


});

require __DIR__.'/auth.php';
