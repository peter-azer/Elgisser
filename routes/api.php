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
use App\Http\Controllers\Admin\RolesPermissionController as AdminRolesPermissionController;
use App\Http\Controllers\Admin\DashboardController;
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
use App\Http\Controllers\FilterController;
use App\Http\Controllers\RentRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:super-admin|admin|editor'])->prefix('dashboard')->group(function () {
    // dashboard route #Done to test
    Route::get('/', [DashboardController::class, 'index']);
    //user routes #Done to test
    Route::get('/users', [AdminUsersController::class, 'index']);
    Route::get('/admins', [AdminUsersController::class, 'getAdmins']);
    Route::get('/users/{user}', [AdminUsersController::class, 'show']);
    Route::post('/user/create', [AdminUsersController::class, 'store']);
    Route::put('/users/edit/{user}', [AdminUsersController::class, 'update']);
    Route::delete('/users/{user}', [AdminUsersController::class, 'destroy']);
    // unauthrized artists and galleries
    Route::get('/users/not-artist', [AdminUsersController::class, 'getUnauthorizedArtist']);
    Route::get('/users/not-gallery', [AdminUsersController::class, 'getUnauthorizedGalleries']);

    //artists routes #Done to test
    Route::get('/artists', [AdminArtistController::class, 'index']);
    Route::get('/artists/{artist}', [AdminArtistController::class, 'show']);
    Route::post('/artists', [AdminArtistController::class, 'store']);
    Route::put('/artists/edit/{artist}', [AdminArtistController::class, 'update']);
    Route::delete('/artists/{artist}', [AdminArtistController::class, 'destroy']);

    //gallery routes #Done to test
    Route::get('/galleries', [AdminGalleryController::class, 'index']);
    Route::get('/galleries/{gallery}', [AdminGalleryController::class, 'show']);
    Route::post('/gallery/create', [AdminGalleryController::class, 'store']);
    Route::put('/gallery/edit/{gallery}', [AdminGalleryController::class, 'update']);
    Route::delete('/gallery/{gallery}', [AdminGalleryController::class, 'destroy']);

    //events routes #Done to test
    Route::get('/events', [AdminEventController::class, 'index']);
    Route::get('/events/{event}', [AdminEventController::class, 'show']);
    Route::post('/event/create', [AdminEventController::class, 'store']);
    Route::put('/event/edit/{event}', [AdminEventController::class, 'update']);
    Route::put('/event/approve/{event}', [AdminEventController::class, 'approve']);
    Route::delete('/event/{event}', [AdminEventController::class, 'destroy']);

    //banners routes #Done to test
    Route::get('/banners', [AdminBannersController::class, 'index']);
    Route::get('/banners/{banner}', [AdminBannersController::class, 'show']);
    Route::post('/banner/create', [AdminBannersController::class, 'store']);
    Route::put('/banner/edit/{banner}', [AdminBannersController::class, 'update']);
    Route::put('/banner/order/{banner}', [AdminBannersController::class, 'order']); #order banners based on certain order not asc nor desc
    Route::delete('/banner/{banner}', [AdminBannersController::class, 'destroy']);

    //Artwork(products) routes #Done to test
    Route::get('/artworks', [AdminArtWorkController::class, 'index']);
    Route::get('/artwork/{artwork}', [AdminArtWorkController::class, 'show']);
    Route::post('/artwork/create', [AdminArtWorkController::class, 'store']);
    Route::put('/artwork/edit/{artwork}', [AdminArtWorkController::class, 'update']);
    Route::delete('/artwork/{artwork}', [AdminArtWorkController::class, 'destroy']);

    //orders routes #Done to test
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
    Route::put('/orders/status/{order}', [AdminOrderController::class, 'setStatus']); #set order status and notify user
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy']);

    //rented artwork routes #Done to test
    Route::get('/rented-artworks', [AdminRentedArtworkController::class, 'index']);
    Route::get('/rended-artwork/{rentedArtWork}', [AdminRentedArtWorkController::class, 'show']);
    Route::post('/rended-artwork/create', [AdminRentedArtWorkController::class, 'store']);
    Route::put('/rended-artwork/edit/{rentedArtWork}', [AdminRentedArtWorkController::class, 'update']);
    Route::put('/rended-artwork/status/{rentedArtWork}', [AdminRentedArtWorkController::class, 'status']); #set status for the rented item
    Route::delete('/rended-artwork/{rentedArtWork}', [AdminRentedArtWorkController::class, 'destroy']);

    //Rent Request routes #Done to test
    Route::get('/requests', [AdminRentRequestController::class, 'index']);
    Route::get('/request/{rentRequest}', [AdminRentRequestController::class, 'show']);
    Route::post('/request/create', [AdminRentRequestController::class, 'store']);
    Route::put('/request/edit/{rentRequest}', [AdminRentRequestController::class, 'store']);

    // assign permission to users #Done to test
    Route::get('/permissions', [AdminRolesPermissionController::class, 'index']);
    Route::post('/permission/{id}', [AdminRolesPermissionController::class, 'assignPermission']);

    // activity log routes #Done to test
    Route::get('/activity-log', [AdminUsersController::class, 'getLogs']);
});

// handle general routes for all the website
Route::prefix('guest')->group(function () {
    // artwork routes #Done to test
    Route::get('/artworks', [ArtWorkController::class, 'index']);
    Route::get('/artwork/{artwork}', [ArtWorkController::class, 'show']);
    // most viewed #Done to test
    Route::get('/artwork/most', [ArtWorkController::class, 'mostViewed']);
    // recently viewed #Done to test
    Route::get('/artwork/recent', [ArtWorkController::class, 'recentViewed']);
    //banners routes #Done to test
    Route::get('/banners', [BannersController::class, 'index']);
    //categories routes #Done to test
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/category/{category}', [CategoryController::class, 'show']);
    //events routes #Done to test
    Route::get('/events', [EventController::class, 'index']);
    // galleries #Done to test
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::get('/gallery/{id}', [GalleryController::class, 'show']);
    // portfolio #Done to test
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/portfolio/{id}', [ArtistController::class, 'show']);
    // filter options #Done to test
    Route::get('/filters', [FilterController::class, 'index']);
});


//users routes
Route::middleware(['auth:sanctum'])->group(function () {
    // favorite routes #Done to test
    Route::get('/favorites/{id}', [FavoriteController::class, 'show']);
    Route::post('/favorite/{artwork}', [FavoriteController::class, 'store']);
    Route::delete('/favorite/{artwork}', [FavoriteController::class, 'destroy']);

    // handel order routes #Done to test
    Route::get('/orders/{id}', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::post('/place-order', [OrderController::class, 'checkout']);
});

// handel artist-role routes
Route::middleware(['auth:sanctum', 'role:artist'])->prefix('artist')->group(function () {
    // sign artist data #Done to test
    Route::post('/sign', [ArtistController::class, 'store']);
    Route::put('/sign/{artist}', [ArtistController::class, 'update']);
    // portfolio routes #Done to test
    Route::post('/portfolio/upload', [ArtistController::class, 'upload']);
    // Artwork routes #Done to test
    Route::get('/artworks', [ArtWorkController::class, 'showArtistArtwork']);
    Route::post('/artwork/create', [ArtWorkController::class, 'store']);
    Route::put('/artwork/edit/{artwork}', [ArtWorkController::class, 'update']);
    // orders routes #Done to test
    Route::get('/orders', [OrderController::class, 'showArtistOrders']);
    Route::get('/order/{id}', [OrderController::class, 'showArtistOrder']);
    Route::put('/order/status/{id}', [OrderController::class, 'artistSetStatus']);

    // Rent Requests #Done to test
    Route::get('/rent/requests', [RentRequestController::class, 'index']);
    Route::get('/rent/request/{id}', [RentRequestController::class, 'show']);
    Route::put('/rent/request/approve/{id}', [RentRequestController::class, 'approve']);
    Route::put('/rent/request/disapprove/{id}', [RentRequestController::class, 'disapprove']);
});

// handle gallery-role routes
Route::middleware(['auth:sanctum', 'role:gallery'])->prefix('gallery')->group(function () {
    // sign artist data #Done to test
    Route::post('/sign', [GalleryController::class, 'store']);
    Route::put('/sign/{artist}', [GalleryController::class, 'update']);
    // request to create event #Done to test
    Route::post('/request/event', [EventController::class, 'store']);
    // can see there event requests #Done to test
    Route::get('/requests/view', [EventController::class, 'galleryEvents']);
    // request to rent artwork #Done to test
    Route::post('/request/artwork', [RentRequestController::class, 'store']);
    // can see there artwork rent request #Done to test
    Route::get('/request/artwork/view', [RentRequestController::class, 'galleryRentRequests']);
});
