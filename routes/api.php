<?php

use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\BannersController as AdminBannersController;
use App\Http\Controllers\Admin\ArtWorkController as AdminArtWorkController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RentedArtWorkController as AdminRentedArtWorkController;
use App\Http\Controllers\Admin\RentRequestController as AdminRentRequestController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\RentedArtWorkController;
use App\Http\Controllers\ArtWorkController;
use App\Http\Controllers\RentRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'user' =>  $user,
        'role' =>  $user->role
    ]);
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

    //Rent Request routes
    Route::get('/requests', [RentRequestController::class, 'index']);
    Route::get('/request/{request}', [RentRequestController::class, 'show']);
    Route::post('/request/create', [RentRequestController::class, 'store']);
    Route::put('/request/edit/{request}', [RentRequestController::class, 'store']);

});

// handle general routes for all the website
Route::prefix('guest')->group(function (){
    // artwork routes
    Route::get('/artworks', [ArtWorkController::class, 'index']);
    Route::get('/artwork/{artwork}', [ArtWorkController::class, 'show']);
        // most viewed
        Route::get('/artwork/most', [ArtWorkController::class, 'mostViewed']);
        // recently viewed
        Route::get('/artwork/recent', [ArtWorkController::class, 'recentViewed']);
    //banners routes
    Route::get('/banners', [BannersController::class, 'index']);
    //categories routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/category/{category}', [CategoryController::class, 'show']);
    //events routes
    Route::get('/events', [EventController::class, 'index']);
    // galleries
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::get('/gallery/{gallery}', [GalleryController::class, 'show']);
    // portfolio
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/portfolio/{artist}', [ArtistController::class, 'show']);
});


//users routes
Route::middleware(['auth:sanctum'])->group(function(){
    // favorite routes
    Route::get('/favorites/{id}', [FavoriteController::class, 'show']);
    Route::post('/favorite/{artwork}', [FavoriteController::class, 'store']);
    Route::delete('/favorite/{artwork}', [FavoriteController::class, 'destroy']);

    // handel order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/order/{order}', [OrderController::class, 'show']);
    Route::post('/place-order', [OrderController::class, 'checkout']);
});

// handel artist-role routes
Route::middleware(['auth:sanctum', 'role:artist'])->prefix('artist')->group(function(){
    // portfolio routes
    Route::post('/portfolio/upload', [ArtistController::class, 'upload']); 
    // Artwork routes
    Route::get('/artworks', [ArtWorkController::class, 'showArtistArtwork']);
    Route::post('/artwork/create', [ArtWorkController::class, 'store']);
    Route::put('/artwork/edit/{artwork}', [ArtWorkController::class, 'update']);
    // orders routes
    Route::get('/orders', [OrderController::class, 'showArtistOrders']);
    Route::get('/order/{order}', [OrderController::class, 'showArtistOrder']);
    Route::put('/order/status/{order}', [OrderController::class, 'artistSetStatus']);

    // Rent Requests
    Route::get('/rent/requests', [RentRequestController::class, 'index']);
    Route::get('/rent/request/{request}', [RentRequestController::class, 'show']);
    Route::put('/rent/request/{artwork}', [RentRequestController::class, 'approve']);
    Route::put('/rent/request/{artwork}', [RentRequestController::class, 'disapprove']);
});

// handle gallery-role routes
Route::middleware(['auth:sanctum', 'role:gallery'])->prefix('gallery')->group(function(){
    // request to create event
    Route::post('/request/event', [EventController::class, 'store']);
    // can see there event requests
    Route::get('/requests/view', [EventController::class, 'galleryEvents']);
    // request to rent artwork
    Route::post('/request/artwork', [RentRequestController::class, 'store']);
    // can see there artwork rent request
    Route::post('/request/artwork/view', [RentRequestController::class, 'galleryRentRequests']);

});
