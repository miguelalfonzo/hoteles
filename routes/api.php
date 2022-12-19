<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\ProductsController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\BookingController;
use App\Http\Controllers\V1\MaintenanceController;





Route::prefix('v1')->group(function () {


	 Route::group(['prefix' => 'booking'], function () {

       
	 	Route::get('search', [BookingController::class, 'search']);

	 	Route::post('create', [BookingController::class, 'create']);
		
    });


	  Route::group(['prefix' => 'maintenance'], function () {

       
	 	Route::get('options', [MaintenanceController::class, 'options']);
		
    });



	 

	



    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('products', [ProductsController::class, 'index']);
    Route::get('products/{id}', [ProductsController::class, 'show']);

    Route::group(['middleware' => ['jwt.verify']], function() {
      
      //verificación 
        
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
        Route::post('products', [ProductsController::class, 'store']);
        Route::put('products/{id}', [ProductsController::class, 'update']);
        Route::delete('products/{id}', [ProductsController::class, 'destroy']);
    });
});