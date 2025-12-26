<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

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

    public function show($id)
    {
        // Fetch the order by ID
        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id', $order->id)->get(); // menampilkan order item yang berkaitan dengan order tersebut
        // Return the view with the order details
        return view('admin.order.show', compact('order', 'orderItems'));
    }
};
