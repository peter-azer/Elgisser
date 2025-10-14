<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/policy', [UserController::class, 'policy']);
require __DIR__.'/auth.php';
