<?php

use Illuminate\Support\Facades\Route;
use Gogain\LaravelPhoneVerification\Http\Controllers\SmsController;

Route::prefix('users')->group(function () {
    Route::post('sms-verification', [SmsController::class, 'send'])->name('sms-verification');
    Route::post('sms-verification/{code}/{number}', [SmsController::class, 'checkCode'])->name('sms-verification.check');
});
