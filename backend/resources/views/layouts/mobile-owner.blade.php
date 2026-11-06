<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Owner — Kasir Pintar' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="owner-mobile-body">
<header class="owner-mobile-topbar">
  <div><span class="eyebrow">OWNER</span><strong>Kasir Pintar</strong></div>
  <form method="POST" action="{{ route('logout') }}">@csrf<button class="owner-icon-btn" aria-label="Keluar">↪</button></form>
</header>
<main class="owner-mobile-page">@yield('content')</main>
<nav class="owner-mobile-nav" aria-label="Navigasi owner">
 <a class="active" href="{{ route('owner.dashboard') }}"><span>⌂</span><small>Ringkasan</small></a>
 <a href="#finance"><span>Rp</span><small>Keuangan</small></a>
 <a href="#stock"><span>▤</span><small>Stok</small></a>
 <a href="#forecast"><span>⌁</span><small>Prediksi</small></a>
</nav>
</body></html>
