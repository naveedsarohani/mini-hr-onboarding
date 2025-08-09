<?php

use App\Controllers\Api\EmployeeController;
use App\Controllers\Api\LogController;
use Core\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('api.employees.index');

    Route::prefix('/logs')->controller(LogController::class)->group(function () {
        Route::get('/', 'index')->name('api.logs.index');
        Route::post('/delete/{id}', 'delete')->name('api.logs.delete');
    });
});
