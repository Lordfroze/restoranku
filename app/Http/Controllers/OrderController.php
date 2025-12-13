<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    //menampilkan order
    public function index()
    {
        //menampilkan order
        $orders = Order::all();
        return view('admin.order.index', compact('orders'));
    }
}
