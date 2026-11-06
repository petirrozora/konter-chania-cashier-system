@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">OPERASIONAL</span><h1>Riwayat Transaksi</h1><p>Semua transaksi penjualan yang tersimpan.</p></div></div>
<section class="panel table-wrap"><table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Karyawan</th><th class="num">Total</th><th class="num">Diterima</th><th class="num">Kembalian</th><th></th></tr></thead><tbody>@foreach($sales as $s)<tr><td class="mono">{{$s->invoice_code}}</td><td>{{$s->created_at->format('d M Y H:i')}}</td><td>{{$s->employee->name}}</td><td class="num">Rp {{number_format($s->total_price,0,',','.')}}</td><td class="num">Rp {{number_format($s->paid_amount,0,',','.')}}</td><td class="num">Rp {{number_format($s->change_amount,0,',','.')}}</td><td><a class="btn btn-small btn-ghost" href="{{route('admin.transactions.show',$s)}}">Detail</a></td></tr>@endforeach</tbody></table></section><div class="pagination">{{$sales->links()}}</div>
@endsection
