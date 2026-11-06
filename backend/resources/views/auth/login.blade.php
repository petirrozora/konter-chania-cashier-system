<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Masuk — Kasir Pintar</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="login-page"><a class="skip-link" href="#login-content">Lewati ke konten</a><section class="login-brand"><div><div class="brand-big"><div class="brand-mark">KP</div>Kasir Pintar</div><p>Catat penjualan. Pantau stok.</p><small>Sistem informasi kasir berbasis web</small></div></section>
<section class="login-form-wrap" id="login-content"><div class="login-form"><span class="eyebrow">MASUK</span><h1>Masuk</h1><p class="muted">Gunakan akun yang diberikan owner.</p>
<form method="POST" action="{{route('login.store')}}">@csrf
<label for="username">Username</label><input id="username" name="username" value="{{old('username')}}" autocomplete="username" autofocus required>
<label for="password">Password</label><div class="password-wrap"><input id="password" type="password" name="password" autocomplete="current-password" required><button type="button" class="icon-btn" data-password-toggle>◉</button></div>
@if($errors->any())<div class="alert alert-danger" role="alert">{{$errors->first()}}</div>@endif
<button class="btn btn-primary btn-xl" type="submit">Masuk</button></form><p class="login-note">Lupa password? Hubungi owner.</p>
<div class="demo-box"><strong>Akun demo</strong><span>owner / password</span><span>kasir / password</span></div></div></section></body></html>
