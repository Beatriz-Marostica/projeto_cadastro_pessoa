<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;

Route::apiResource('pessoas', PessoaController::class);