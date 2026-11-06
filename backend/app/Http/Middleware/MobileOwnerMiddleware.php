<?php
namespace App\Http\Middleware;
use App\Models\MobileToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class MobileOwnerMiddleware {
    public function handle(Request $request, Closure $next) {
        $raw = $request->bearerToken();
        if (!$raw) return response()->json(['message'=>'Token diperlukan.'],401);
        $token = MobileToken::with('user')->where('token_hash',hash('sha256',$raw))->first();
        if (!$token || !$token->user || !$token->user->is_active || !$token->user->isOwner() || ($token->expires_at && $token->expires_at->isPast())) {
            return response()->json(['message'=>'Token tidak valid atau kedaluwarsa.'],401);
        }
        $request->setUserResolver(fn()=> $token->user);
        return $next($request);
    }
}
