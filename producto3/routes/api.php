<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/servicios/zonas', [ApiController::class, 'serviciosPorZona']);