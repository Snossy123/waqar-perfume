<?php

use App\Http\Controllers\Admin\FinancingRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Financing Requests
    Route::resource('requests', FinancingRequestController::class)->except(['create', 'edit', 'store']);
    Route::patch('requests/{financingRequest}/status', [FinancingRequestController::class, 'updateStatus'])
        ->name('requests.update-status');
});
