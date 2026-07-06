<?php

use App\Http\Controllers\MedicalCaseController;
use App\Http\Controllers\MedicalNoteController;
use Illuminate\Support\Facades\Route;
use RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController;
use RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController;
use RobbinThijssen\IdentitySsoKit\Http\Controllers\SsoCallbackController;

Route::get('login', RedirectToIdentityController::class)->name('login');
Route::get('sso/callback', SsoCallbackController::class)->name('sso.callback');
Route::post('logout', LogoutController::class)->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/', '/medical-cases')->name('home');
    Route::redirect('dashboard', '/medical-cases')->name('dashboard');

    Route::get('medical-cases', [MedicalCaseController::class, 'index'])->name('medical-cases.index');
    Route::post('medical-cases', [MedicalCaseController::class, 'store'])->name('medical-cases.store');
    Route::get('medical-cases/{medicalCase}', [MedicalCaseController::class, 'show'])->name('medical-cases.show');
    Route::put('medical-cases/{medicalCase}', [MedicalCaseController::class, 'update'])->name('medical-cases.update');

    Route::post('medical-cases/{medicalCase}/notes', [MedicalNoteController::class, 'store'])->name('medical-notes.store');
    Route::put('medical-cases/{medicalCase}/notes/{medicalNote}', [MedicalNoteController::class, 'update'])->name('medical-notes.update');
    Route::delete('medical-cases/{medicalCase}/notes/{medicalNote}', [MedicalNoteController::class, 'destroy'])->name('medical-notes.destroy');
});

require __DIR__.'/settings.php';
