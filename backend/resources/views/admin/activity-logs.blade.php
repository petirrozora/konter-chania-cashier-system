@extends('layouts.app')
@section('content')
<div class="page-head"><div><span class="eyebrow">ADMINISTRASI</span><h1>Activity Log</h1><p>Audit aktivitas pengguna. Data bersifat baca-saja.</p></div></div>
<section class="panel table-wrap"><table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr></thead><tbody>@foreach($logs as $l)<tr><td>{{$l->created_at->format('d M Y H:i')}}</td><td>{{$l->user?->name ?? 'Sistem'}}</td><td class="mono">{{$l->action}}</td><td>{{$l->description}}</td><td class="mono">{{$l->ip_address}}</td></tr>@endforeach</tbody></table></section><div class="pagination">{{$logs->links()}}</div>
@endsection
