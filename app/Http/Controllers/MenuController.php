<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use PHPUnit\Logging\OpenTestReporting\Status;

class MenuController extends Controller
{
    // fungsi menampilkan menu
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');
        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('customer.menu', compact('items', 'tableNumber'));
    }

    // fungsi menampilkan keranjang
    public function cart()
    {
        $cart = Session::get('cart');
        return view('customer.cart', compact('cart'));
    }

    // fungsi menambahkan keranjang
    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if (!$menu) { // cek apakah menu ada
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Menu tidak ditemukan'
                ],
                404
            );
        }

        // mengambil keranjang dari session
        $cart = Session::get('cart');

        // menambahkan menu ke keranjang
        if(isset($cart[$menuId])){ // cek apakah menu sudah ada di keranjang
            $cart[$menuId]['qty']++; // jika sudah ada, tambahkan quantity
        }else{ // jika belum ada, tambahkan menu ke keranjang
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => $menu->img,
                'qty' => 1,
            ];
        }
        // menyimpan keranjang ke session
        Session::put('cart', $cart);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Berhasil ditambahkan ke keranjang',
                'cart' => $cart
            ],
            200
        );
    }
}