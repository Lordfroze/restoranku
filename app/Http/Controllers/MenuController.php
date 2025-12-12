<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use PHPUnit\Logging\OpenTestReporting\Status;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

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
        if (isset($cart[$menuId])) { // cek apakah menu sudah ada di keranjang
            $cart[$menuId]['qty']++; // jika sudah ada, tambahkan quantity
        } else { // jika belum ada, tambahkan menu ke keranjang
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

    // fungsi update keranjang
    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQty = $request->input('qty');

        if ($newQty <= 0) {
            return response()->json(['success' => false]); // jika quantity kurang dari 0, tampilkan pesan error
        }

        $cart = Session::get('cart');
        // cek apakah item ada di keranjang
        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $newQty; // jika ada, update quantity
            Session::put('cart', $cart); // simpan keranjang ke session
            Session::flash('success', 'Jumlah item berhasil diperbarui'); // tampilkan pesan sukses

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]); // jika item tidak ada di keranjang, tampilkan pesan error
    }

    // fungsi menghapus item dari keranjang
    public function removeCart(Request $request)
    {
        $itemId = $request->input('id');

        $cart = Session::get('cart');

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);

            Session::flash('success', 'Item berhasil dihapus dari keranjang');

            return response()->json(['success' => true]);
        }
    }

    // fungsi mengosongkan keranjang
    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang berhasil diosongkan');
    }

    // fungsi checkout
    // Checkout
    public function checkout()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong');
        }

        $tableNumber = Session::get('tableNumber');

        return view('customer.checkout', compact('cart', 'tableNumber'));
    }

    // fungsi menyimpan pesanan
    public function storeOrder(Request $request)
    {
        $cart = Session::get('cart'); // mengecek apakah keranjang ada
        $tableNumber = Session::get('tableNumber');
        if (empty($cart)) { // jika keranjang kosong
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong'); //redirect ke keranjang
        }

        // validasi input
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) { // jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput(); // redirect ke checkout dengan pesan error
        }

        $total = 0;
        foreach ($cart as $item) { // perulangan dari setiap item di keranjang
            $total += $item['price'] * $item['qty']; // hitung total harga
        }

        $totalAmount = 0;
        foreach ($cart as $item) { // perulangan dari setiap item di keranjang
            $totalAmount += $item['qty'] * $item['price']; // hitung total harga

            // memasukkan detail item ke array
            $itemDetails[] = [
                // 'item_id' => $item['id'],
                'id' => $item['id'],
                'price' => (int) ($item['price'] + ($item['price'] * 0.1)), // hitung total harga + 10% pph
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50), // truncaate nama item ke 50 karakter
            ];
        }

        // menyimpan user ke database
        $user = User::firstOrCreate([
           'fullname' => $request->input('fullname'),
           'phone' => $request->input('phone'),
           'role_id' => 4, // role customer sesuai database
        ]);

        // menyimpan order ke database
        $order = Order::create([
            'order_code' => 'ORD-'. $tableNumber . '-' . time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => $totalAmount * 0.1, // 10% pph
            'grand_total' => $totalAmount + ($totalAmount * 0.1), // total harga + 10% pph
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        // menyimpan order item ke database
        foreach ($cart as $itemId => $item) { // perulangan dari setiap item di keranjang
            OrderItem::create([
                'order_id' => $order->id,
                // 'item_id' => $item['id'],
                'item_id' => $itemId,
                'quantity' => $item['qty'],
                'price' => $item['price'] * $item['qty'], // hitung total harga
                'tax' => $item['price'] * $item['qty'] * 0.1, // 10% pph
                'total_price' => ($item['price'] * $item['qty']) + (0.1 * $item['price'] * $item['qty']), // hitung total harga + 10% pph
            ]);
        }
        // menghapus keranjang setelah order berhasil disimpan
        Session::forget('cart');

        if($request->payment_method == 'tunai') {
            return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Pesanan berhasil dibuat');
        } else {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_code,
                        'gross_amount' =>  (int) $order->grand_total,
                ],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'first_name' => $user->fullname ?? 'Guest',
                        'phone' => $user->phone,
                ],
                    'payment_type' => 'qris',
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_code' => $order->order_code,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat pesanan. Silakan coba lagi.',
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    // fungsi menampilkan pesanan berhasil
    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method == 'qris'){
            $order->status == 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));
    }

}