@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">AUDIT STOK</span><h1>Riwayat Stok</h1><p>Buku besar perubahan persediaan.</p></div></div>
<section class="panel table-wrap"><table><thead><tr><th>Waktu</th><th>Produk</th><th>Tipe</th><th class="num">Qty</th><th>Stok</th><th>Alasan</th><th>Pengguna</th></tr></thead><tbody>@foreach($movements as $m)<tr><td>{{$m->created_at->format('d M Y H:i')}}</td><td>{{$m->product->name}}</td><td><span class="status {{$m->type==='in'?'success':'warning'}}">{{$m->type==='in'?'Barang masuk':'Barang keluar'}}</span></td><td class="num">{{$m->quantity}} pcs</td><td class="mono">{{$m->before_quantity}} → {{$m->after_quantity}}</td><td>{{$m->reason}}</td><td>{{$m->user?->name ?? 'Sistem'}}</td></tr>@endforeach</tbody></table></section><div class="pagination">{{$movements->links()}}</div>
@endsection
