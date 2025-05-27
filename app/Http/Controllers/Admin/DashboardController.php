<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Event;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(){

        // user analytics
        $TotalUsers = User::count();
        $users = User::where('role', 'user')->count();
        $superAdmins = User::where('role', 'super-admin')->count();
        $admins = User::where('role', 'admin')->count();
        $editors = User::where('role', 'editor')->count();
        // artists analytics
        $artists = Artist::count();
        // artworks analytics
        $artworks = Artwork::count();
        // galleries analytics
        $galleries = Gallery::count();
        $galleriesWithEvents = Gallery::has('events')->count();
        $galleriesWithoutEvents = Gallery::doesntHave('events')->count();
        // events analytics
        $events = Event::count();
        $approvedEvents = Event::where('is_approved', true)->count();
        $notApprovedEvents = Event::where('is_approved', false)->count();
        // orders analytics
        $orders = Order::count();
        $sales = Order::sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $canceledOrders = Order::where('status', 'canceled')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $totalPendingSales = Order::where('status', 'pending')->sum('total');
        $totalCompletedSales = Order::where('status', 'completed')->sum('total');
        $totalCanceledSales = Order::where('status', 'canceled')->sum('total');
        $totalShippedSales = Order::where('status', 'shipped')->sum('total');

        return response()->json([
            'TotalUsers' => $TotalUsers,
            'users' => $users,
            'superAdmins' => $superAdmins,
            'admins' => $admins,
            'editors' => $editors,
            'artist' => $artists,
            'artworks' => $artworks,
            'galleries' => $galleries,
            'galleriesWithEvents' => $galleriesWithEvents,
            'galleriesWithoutEvents' => $galleriesWithoutEvents,
            'events' => $events,
            'approvedEvents' => $approvedEvents,
            'eventsWithGalleries' => $notApprovedEvents,
            'orders' => $orders,
            'sales' => $sales,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'canceledOrders' => $canceledOrders,
            'shippedOrders' => $shippedOrders,
            'totalPendingSales' => $totalPendingSales,
            'totalCompletedSales' => $totalCompletedSales,
            'totalCanceledSales' => $totalCanceledSales,
            'totalShippedSales' => $totalShippedSales,
        ]);
    }
}
