<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    //menampilkan order
    public function index()
    {
        // Fetch all orders from the database
        $orders = Order::all()->sortByDesc('created_at');

        // Return the view with the orders
        return view('admin.order.index', compact('orders'));
    }
}
