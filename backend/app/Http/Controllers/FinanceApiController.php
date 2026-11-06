<?php
namespace App\Http\Controllers;
use App\Models\{CashTransaction,Sale,Stock,RestockRecommendation};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User,MobileToken};
class FinanceApiController extends Controller {
    public function login(Request $r){
        $d=$r->validate(['username'=>'required','password'=>'required']);
        $u=User::where('username',$d['username'])->first();
        if(!$u || !$u->isOwner() || !$u->is_active || !Hash::check($d['password'],$u->password)) return response()->json(['message'=>'Kredensial owner tidak valid.'],422);
        $raw=Str::random(64);
        MobileToken::create(['user_id'=>$u->id,'token_hash'=>hash('sha256',$raw),'expires_at'=>now()->addDays(30)]);
        return response()->json(['token'=>$raw,'user'=>['name'=>$u->name,'role'=>$u->role],'expires_at'=>now()->addDays(30)->toISOString()]);
    }
    public function dashboard(Request $r){
        $from=now()->startOfMonth(); $to=now()->endOfMonth();
        $sales=Sale::whereBetween('created_at',[$from,$to])->get();
        $income=CashTransaction::where('type','income')->whereBetween('transaction_date',[$from->toDateString(),$to->toDateString()])->sum('amount');
        $expense=CashTransaction::where('type','expense')->whereBetween('transaction_date',[$from->toDateString(),$to->toDateString()])->sum('amount');
        $low=Stock::with('product')->whereColumn('quantity','<=','minimum_stock')->orderBy('quantity')->limit(8)->get()->map(fn($s)=>['name'=>$s->product->name,'stock'=>$s->quantity,'minimum'=>$s->minimum_stock]);
        $restock=RestockRecommendation::with('product')->where('status','Perlu Restock')->latest()->limit(8)->get()->map(fn($x)=>['product'=>$x->product->name,'current_stock'=>$x->current_stock,'recommended_quantity'=>$x->recommended_quantity]);
        $daily=Sale::selectRaw('DATE(created_at) day, SUM(total_price) total, COUNT(*) transactions')->where('created_at','>=',now()->subDays(30))->groupBy('day')->orderBy('day')->get();
        return response()->json(['period'=>['from'=>$from->toDateString(),'to'=>$to->toDateString()],'sales'=>['total'=>$sales->sum('total_price'),'transactions'=>$sales->count()],'finance'=>['income'=>$income,'expense'=>$expense,'net'=>$income-$expense],'low_stock'=>$low,'restock'=>$restock,'trend'=>$daily]);
    }
    public function logout(Request $r){MobileToken::where('token_hash',hash('sha256',$r->bearerToken()))->delete();return response()->json(['message'=>'Logout berhasil.']);}
}
