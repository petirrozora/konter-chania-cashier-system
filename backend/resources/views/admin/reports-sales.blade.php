@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">LAPORAN</span><h1>Laporan Penjualan</h1><p>Periode {{$from->format('d M Y')}} – {{$to->format('d M Y')}}.</p></div></div>
<div class="kpi-grid"><article class="kpi hero"><span>Total penjualan</span><strong>Rp {{number_format($summary,0,',','.')}}</strong><small>Periode aktif</small></article><article class="kpi"><span>Transaksi</span><strong>{{$sales->total()}}</strong><small>Data tersimpan</small></article></div>
<section class="panel table-wrap"><table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Karyawan</th><th class="num">Total</th></tr></thead><tbody>@foreach($sales as $s)<tr><td class="mono">{{$s->invoice_code}}</td><td>{{$s->created_at->format('d M Y H:i')}}</td><td>{{$s->employee->name}}</td><td class="num">Rp {{number_format($s->total_price,0,',','.')}}</td></tr>@endforeach</tbody></table></section>
@endsection
