<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'Kasir Pintar' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<a class="skip-link" href="#main-content">Lewati ke konten</a>
@if(auth()->check() && auth()->user()->isAdmin())
<div class="owner-shell">
<aside class="sidebar" id="owner-sidebar">
  <div class="brand"><div class="brand-mark">KP</div><div><strong>Kasir Pintar</strong><small>Admin workspace</small></div></div>
  <nav aria-label="Utama">
   <div class="nav-group-label">Ringkasan</div>
   <a class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{route('admin.dashboard')}}">⌂ <span>Dashboard</span></a>
   <div class="nav-group-label">Operasional</div>
   @foreach([['admin.products','Produk','▣'],['admin.categories','Kategori','◫'],['admin.stocks','Stok','▤'],['admin.stock-movements','Riwayat Stok','↕'],['admin.transactions','Riwayat Transaksi','▤']] as $n)
    <a class="nav-link {{ request()->routeIs($n[0])?'active':'' }}" href="{{route($n[0])}}"><b>{{$n[2]}}</b><span>{{$n[1]}}</span></a>
   @endforeach
   <div class="nav-group-label">Laporan</div>
   <a class="nav-link" href="{{route('admin.reports.sales')}}">▥ <span>Penjualan</span></a>
   <a class="nav-link" href="{{route('admin.reports.profit')}}">◒ <span>Keuntungan</span></a>
   <a class="nav-link {{ request()->routeIs('admin.finance')?'active':'' }}" href="{{route('admin.finance')}}">Rp <span>Keuangan</span></a>
   <div class="nav-group-label">Wawasan</div>
   <a class="nav-link" href="{{route('admin.analytics')}}">⌁ <span>Analitik</span></a>
   <a class="nav-link" href="{{route('admin.forecasts')}}">⌁ <span>Prediksi</span></a>
   <a class="nav-link" href="{{route('admin.restock')}}">↗ <span>Restock</span>@if(($restockBadge ?? 0)>0)<em>{{$restockBadge}}</em>@endif</a>
   <div class="nav-group-label">Administrasi</div>
   <a class="nav-link" href="{{route('admin.employees')}}">♙ <span>Karyawan</span></a>
   <a class="nav-link" href="{{route('admin.activity-logs')}}">◷ <span>Activity Log</span></a>
  </nav>
  <div class="sidebar-user"><div class="avatar">{{strtoupper(substr(auth()->user()->name,0,1))}}</div><div><strong>{{auth()->user()->name}}</strong><small>Admin</small></div><form method="POST" action="{{route('logout')}}">@csrf<button title="Keluar">↪</button></form></div>
</aside>
<div class="main-shell">
<header class="topbar">
  <button class="mobile-menu-toggle" id="owner-menu-toggle" type="button" aria-controls="owner-sidebar" aria-expanded="false" aria-label="Buka menu">
    <span></span><span></span><span></span>
  </button>
  <div class="topbar-date"><span class="eyebrow">KASIR PINTAR</span><strong>{{ now()->format('d M Y') }}</strong></div>
  <div class="topbar-status"><span class="dot"></span> Sistem aktif</div>
</header>
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>
<main id="main-content" class="page">@yield('content')</main>
</div>
</div>
@else
<div class="employee-shell">
<header class="employee-topbar">
  <div class="brand-inline"><div class="brand-mark">KP</div><strong>Kasir Pintar</strong></div>
  <nav class="employee-nav" aria-label="Navigasi karyawan">
    <a class="{{request()->routeIs('employee.transactions')?'active':''}}" href="{{route('employee.transactions')}}"><span class="nav-icon">＋</span><span>Kasir</span></a>
    <a class="{{request()->routeIs('employee.dashboard')?'active':''}}" href="{{route('employee.dashboard')}}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
    <a class="{{request()->routeIs('employee.stocks')?'active':''}}" href="{{route('employee.stocks')}}"><span class="nav-icon">▤</span><span>Stok</span></a>
    <a class="{{request()->routeIs('employee.today-sales')?'active':''}}" href="{{route('employee.today-sales')}}"><span class="nav-icon">≡</span><span>Hari Ini</span></a>
  </nav>
  <form method="POST" action="{{route('logout')}}">@csrf<button class="btn btn-ghost logout-button" aria-label="Keluar">Keluar</button></form>
</header>
<main id="main-content" class="employee-main">@yield('content')</main>
</div>
@endif
@if(session('success'))<div class="toast" role="status">{{session('success')}}</div>@endif
@if($errors->any())<div class="toast toast-error" role="alert">{{$errors->first()}}</div>@endif
</body></html>
