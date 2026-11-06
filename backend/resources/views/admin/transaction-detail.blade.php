@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">TRANSAKSI</span><h1>{{$sale->invoice_code}}</h1><p>{{$sale->created_at->format('d M Y H:i')}} · {{$sale->employee->name}}</p></div><a class="btn btn-secondary" href="{{route('admin.transactions')}}">Kembali</a></div>
<section class="panel receipt"><h2>Detail penjualan</h2>@foreach($sale->items as $i)<div class="receipt-row"><span>{{$i->product->name}} × {{$i->quantity}}</span><strong>Rp {{number_format($i->subtotal,0,',','.')}}</strong></div>@endforeach<div class="receipt-total"><span>Total</span><strong>Rp {{number_format($sale->total_price,0,',','.')}}</strong></div><div class="receipt-row"><span>Diterima</span><strong>Rp {{number_format($sale->paid_amount,0,',','.')}}</strong></div><div class="receipt-row"><span>Kembalian</span><strong>Rp {{number_format($sale->change_amount,0,',','.')}}</strong></div></section>
@endsection
