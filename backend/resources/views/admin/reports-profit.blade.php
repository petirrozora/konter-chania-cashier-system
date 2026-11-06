@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">LAPORAN</span><h1>Laporan Keuntungan</h1><p>Periode {{$from->format('d M Y')}} – {{$to->format('d M Y')}}.</p></div></div>
<section class="panel profit-hero"><span>Total keuntungan</span><strong>Rp {{number_format($profit,0,',','.')}}</strong><p>Rumus: (harga jual − harga beli) × quantity.</p></section>
@endsection
