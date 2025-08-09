<?php

use App\Controllers\AuthController;
use App\Controllers\EmployeeController;
use App\Controllers\FrontendController;
use Core\Route;

Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'index')->name('home');

    Route::middleware('auth')->group(function () {
        Route::get('/dowload-resume/{id}', 'downloadResume')->name('resume.download');
    });
});

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('auth.login');
        Route::post('/validate-login', 'validateLogin')->name('auth.validate-login');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::prefix('/employees')->controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index')->name('employees.index');
        Route::get('/{id}/view', 'show')->name('employees.show');

        Route::get('/create', 'create')->name('employees.create');
        Route::post('/store', 'store')->name('employees.store');

        Route::get('/edit/{id}', 'edit')->name('employees.edit');
        Route::post('/update/{id}', 'update')->name('employees.update');

        Route::post('/delete/{id}', 'destroy')->name('employees.delete');
    });
});
