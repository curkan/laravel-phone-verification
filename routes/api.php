<?php

use Illuminate\Support\Facades\Route;
use Curkan\LaravelPhoneVerification\Http\Controllers\ItemsController;

Route::get('items', [ItemsController::class, 'index']);
