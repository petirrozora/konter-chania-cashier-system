@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">PREDIKSI</span><h1>Impor Data Historis</h1><p>Alur riset: unggah → pratinjau → validasi → impor.</p></div></div>
<ol class="stepper"><li class="active">1 · Unggah</li><li>2 · Pratinjau</li><li>3 · Validasi</li><li>4 · Impor</li></ol>
<section class="panel upload-box"><h2>Unggah CSV/XLSX</h2><p class="muted">Kolom minimal: tanggal, product ID/kode, quantity terjual. Data pelanggan pribadi tidak diperlukan.</p><form method="POST" action="{{route('admin.forecasts.import.store')}}" enctype="multipart/form-data">@csrf<label>File historis<input type="file" name="file" accept=".csv,.txt,.xlsx" required></label><div class="drawer-footer"><button class="btn btn-primary">Unggah untuk validasi</button></div></form></section>
@endsection
