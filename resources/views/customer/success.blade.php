@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container-fluid py-5 d-flex justify-content-center">
    <div class="receipt border p-4 bg-white shadow" style="width: 450px; margin-top: 5rem">
        <h5 class="text-center mb-2"> Pesanan berhasil dibuat!</h5>
        @if ($order->payment_method == 'tunai' && $order->status == 'pending')
            <p class="text-center"><span class="badge bg-danger">Menunggu Pembayaran</span></p>
        @elseif ($order->payment_method == 'qris' && $order->status == 'pending')
            <p class="text-center"><span class="badge bg-success">Menunggu konfirmasi pembayaran</span></p>
        @else
            <p class="text-center"><span class="badge bg-success">Pembayaran berhasil, pesanan segera diproses</span></p>
        @endif
</div>     

@endsection