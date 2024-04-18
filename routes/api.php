<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\UserController;


Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'create']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);
    
    // Brands API
    Route::get('/brands', [BrandController::class, 'index']);
    Route::post('/brands', [BrandController::class, 'store']);
    Route::get('/brands/{id}', [BrandController::class, 'show']);
    Route::put('/brands/{id}', [BrandController::class, 'update']);
    Route::delete('/brands/{id}', [BrandController::class, 'destroy']);
    
    // Branches API
    Route::get('/branches', [BranchController::class, 'index']);
    Route::post('/branches', [BranchController::class, 'store']);
    Route::get('/branches/{id}', [BranchController::class, 'show']);
    Route::put('/branches/{id}', [BranchController::class, 'update']);
    Route::delete('/branches/{id}', [BranchController::class, 'destroy']);
    
    // Regions API
    Route::get('/regions', [RegionController::class, 'index']);
    Route::post('/regions', [RegionController::class, 'store']);
    Route::get('/regions/{id}', [RegionController::class, 'show']);
    Route::put('/regions/{id}', [RegionController::class, 'update']);
    Route::delete('/regions/{id}', [RegionController::class, 'destroy']);
    
    // Districts API
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::post('/districts', [DistrictController::class, 'store']);
    Route::get('/districts/{id}', [DistrictController::class, 'show']);
    Route::put('/districts/{id}', [DistrictController::class, 'update']);
    Route::delete('/districts/{id}', [DistrictController::class, 'destroy']);

    Route::get('/count/{id}',   [RegionController::class, 'countBranches']);
    Route::get('/currency',     [CurrencyController::class, 'getCurrencies']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});
