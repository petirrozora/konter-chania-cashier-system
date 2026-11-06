@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">STOK</span><h1>Info Stok</h1><p>Baca saja. Harga beli tidak ditampilkan.</p></div></div>
<section class="panel table-wrap"><table><thead><tr><th>Produk</th><th>Kategori</th><th class="num">Stok</th><th>Status</th></tr></thead><tbody>@foreach($products as $p)<tr><td><strong>{{$p->name}}</strong><small class="mono">{{$p->product_code}}</small></td><td>{{$p->category->name}}</td><td class="num">{{$p->stock->quantity}} {{$p->unit}}</td><td><span class="status {{$p->stock->quantity<=0?'danger':($p->stock->quantity<=$p->stock->minimum_stock?'warning':'success')}}">{{$p->stock->statusLabel()}}</span></td></tr>@endforeach</tbody></table></section><div class="pagination">{{$products->links()}}</div>
@endsection
