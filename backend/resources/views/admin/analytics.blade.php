@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">WAWASAN</span><h1>Analitik</h1><p>Ringkasan produk terlaris dan penjualan rendah 30 hari terakhir.</p></div></div>
<div class="two-col"><section class="panel"><div class="panel-head"><h2>Produk terlaris</h2></div>@foreach($top as $i=>$p)<div class="rank-row"><b>{{str_pad($i+1,2,'0',STR_PAD_LEFT)}}</b><span>{{$p->name}}</span><strong>{{$p->qty}} pcs</strong></div>@endforeach</section><section class="panel"><div class="panel-head"><h2>Penjualan rendah</h2></div>@foreach($low as $p)<div class="rank-row"><b>—</b><span>{{$p->name}}</span><strong>{{$p->qty}} pcs</strong></div>@endforeach</section></div>
@endsection
