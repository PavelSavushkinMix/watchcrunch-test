<?php

use App\Http\Controllers\VacationController;
use Illuminate\Support\Facades\Route;

Route::apiResource('vacations', VacationController::class);
