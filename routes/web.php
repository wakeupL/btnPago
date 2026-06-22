<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotonPagoController;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/register', function () {
//    return view('welcome');
//});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/btn-activos', function () {
    return view('btn-activos');
})->middleware(['auth', 'verified'])->name('btn-activos');
Route::get('/btn-pagados', function () {
    return view('listado-pagados');
})->middleware(['auth', 'verified'])->name('btn-pagados');
Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth', 'verified'])->name('settings');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('botonpago', BotonPagoController::class);
Route::get('/pagar', function () {
    return view('pagar');
})->name('pagar');
Route::get('/respuestaPago', [BotonPagoController::class,'respuestaPago'])->name('respuestaPago');
Route::get('/comprobante/{documento}', [BotonPagoController::class,'comprobante'])->name('comprobante');
Route::post('/actualizarToken', [BotonPagoController::class,'actualizarToken'])->name('actualizarToken');
Route::get('/error', [BotonPagoController::class, 'error'])->name('error');
Route::get('/url', [BotonPagoController::class,'urlCorta'])->name('urlCorta');
Route::get('/logo', function () {
    $path = storage_path('app/public/logo/logo.png');
    abort_unless(file_exists($path), 404);

    // Entrega el logo a través de PHP: evita depender del symlink public/storage.
    return response()->file($path, ['Cache-Control' => 'public, max-age=86400']);
})->name('logo');
Route::post('/descargarComprobante', [BotonPagoController::class, 'descargarComprobante'])->name('descargar');

require __DIR__.'/auth.php';
