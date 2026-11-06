<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanceApiController;
Route::prefix('mobile')->group(function(){
    Route::post('/login',[FinanceApiController::class,'login']);
    Route::middleware('mobile.owner')->group(function(){
        Route::get('/dashboard',[FinanceApiController::class,'dashboard']);
        Route::post('/logout',[FinanceApiController::class,'logout']);
    });
});
