<?php

use Illuminate\Support\Facades\Route;
use Gogain\LaravelPhoneVerification\Http\Controllers\ItemsController;

Route::get('items', [ItemsController::class, 'index']);
