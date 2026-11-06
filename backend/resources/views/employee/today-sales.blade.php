@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">PENJUALAN HARI INI</span><h1>Penjualan Hari Ini</h1><p>Transaksi yang dilakukan oleh akun Anda.</p></div></div>
<section class="panel table-wrap"><table><thead><tr><th>Invoice</th><th>Waktu</th><th>Item</th><th class="num">Total</th></tr></thead><tbody>@foreach($sales as $s)<tr><td class="mono">{{$s->invoice_code}}</td><td>{{$s->created_at->format('H:i')}}</td><td>{{$s->items->sum('quantity')}} pcs</td><td class="num">Rp {{number_format($s->total_price,0,',','.')}}</td></tr>@endforeach</tbody></table></section>
@endsection
