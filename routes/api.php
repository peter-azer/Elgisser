<?php

use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\BannersController as AdminBannersController;
use App\Http\Controllers\Admin\ArtWorkController as AdminArtWorkController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RentedArtWorkController as AdminRentedArtWorkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:super-admin'])->prefix('dashboard')->group(function(){
    //user routes
    Route::get('/users', [AdminUsersController::class, 'index']);
    Route::get('/users/{user}', [AdminUsersController::class, 'show']);
    Route::post('/user/create', [AdminUsersController::class, 'store']);
    Route::put('/users/edit/{user}', [AdminUsersController::class, 'update']);
    Route::delete('/users/{user}', [AdminUsersController::class, 'destroy']);

    //artists routes
    Route::get('/artists', [AdminArtistController::class, 'index']);
    Route::get('/artists/{artist}', [AdminArtistController::class, 'show']);
    Route::post('/artists', [AdminArtistController::class, 'store']);
    Route::put('/artists/edit/{artist}', [AdminArtistController::class, 'update']);
    Route::delete('/artists/{artist}', [AdminArtistController::class, 'destroy']);

    //gallery routes
    Route::get('/galleries', [AdminGalleryController::class, 'index']);
    Route::get('/galleries/{gallery}', [AdminGalleryController::class, 'show']);
    Route::post('/gallery/create', [AdminGalleryController::class, 'store']);
    Route::put('/gallery/edit/{gallery}', [AdminGalleryController::class, 'update']);
    Route::delete('/gallery/{gallery}', [AdminGalleryController::class, 'destroy']);

    //events routes
    Route::get('/events', [AdminEventController::class, 'index']);
    Route::get('/events/{event}', [AdminEventController::class, 'show']);
    Route::post('/event/create', [AdminEventController::class, 'store']);
    Route::put('/event/edit/{event}', [AdminEventController::class, 'update']);
    Route::put('/event/approve/{event}', [AdminEventController::class, 'approve']);
    Route::delete('/event/{event}', [AdminEventController::class, 'destroy']);

    //banners routes
    Route::get('/banners', [AdminBannersController::class, 'index']);
    Route::get('/banners/{banner}', [AdminBannersController::class, 'show']);
    Route::post('/banner/create', [AdminBannersController::class, 'store']);
    Route::put('/banner/edit/{banner}', [AdminBannersController::class, 'update']);
    Route::put('/banner/order/{banner}', [AdminBannersController::class, 'order']); #order banners based on certain order not asc nor desc
    Route::delete('/banner/{banner}', [AdminBannersController::class, 'destroy']);

    //Artwork(products) routes
    Route::get('/artworks', [AdminArtWorkController::class, 'index']);
    Route::get('/artwork/{artwork}', [AdminArtWorkController::class, 'show']);
    Route::post('/artwork/create', [AdminArtWorkController::class, 'store']);
    Route::put('/artwork/edit/{artwork}', [AdminArtWorkController::class, 'update']);
    Route::delete('/artwork/{artwork}', [AdminArtWorkController::class, 'destroy']);

    //orders routes
    Route::get('/orders', [AdminOrderController::class, 'index']);  
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
    Route::put('/orders/status/{order}', [AdminOrderController::class, 'setStatus']); #set order status and notify user
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy']);

    //rented artwork routes
    Route::get('/rented-artworks', [AdminRentedArtworkController::class, 'index']);
    Route::get('/rended-artwork', [AdminRentedArtWorkController::class, 'show']);
    Route::post('/rended-artwork/create', [AdminRentedArtWorkController::class, 'store']);
    Route::put('/rended-artwork/edit/{rent}', [AdminRentedArtWorkController::class, 'update']);
    Route::put('/rended-artwork/status/{rent}', [AdminRentedArtWorkController::class, 'status']); #set status for the rented item
    Route::delete('/rended-artwork/{rent}', [AdminRentedArtWorkController::class, 'destroy']);
});

// handel artist-role routes
// handle gallery-role routes
// handle buyers-role routes
