@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">DASHBOARD KARYAWAN</span><h1>Selamat datang, {{auth()->user()->name}}</h1><p>Mulai transaksi atau cek kondisi operasional hari ini.</p></div><a class="btn btn-primary btn-lg" href="{{route('employee.transactions')}}">Mulai Transaksi</a></div>
<div class="kpi-grid"><article class="kpi hero"><span>Penjualan hari ini</span><strong>Rp {{number_format($total,0,',','.')}}</strong><small>{{count($sales)}} transaksi</small></article><article class="kpi"><span>Transaksi</span><strong>{{count($sales)}}</strong><small>Hari ini</small></article></div>
<section class="panel"><div class="panel-head"><h2>Transaksi terakhir</h2><a href="{{route('employee.today-sales')}}">Lihat semua</a></div>@forelse($sales->take(5) as $s)<div class="list-row"><div><strong class="mono">{{$s->invoice_code}}</strong><small>{{$s->created_at->format('H:i')}}</small></div><strong>Rp {{number_format($s->total_price,0,',','.')}}</strong></div>@empty<p class="empty">Belum ada transaksi hari ini.</p>@endforelse</section>
@endsection
