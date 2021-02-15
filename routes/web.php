<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\csv\FolderManager;
use App\Http\Controllers\v1\csv\Parser;


Route::get('/init', [FolderManager::class, 'initialize']);
Route::get('/run', [Parser::class, 'run']);
