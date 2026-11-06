<?php
namespace App\Http\Controllers;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FinanceController extends Controller {
    private function range(Request $r): array {
        $preset=$r->get('period','month');
        return match($preset){
            'today'=>[now()->startOfDay(),now()->endOfDay()],
            'week'=>[now()->startOfWeek(),now()->endOfWeek()],
            'year'=>[now()->startOfYear(),now()->endOfYear()],
            default=>[now()->startOfMonth(),now()->endOfMonth()],
        };
    }
    public function index(Request $r){
        [$from,$to]=$this->range($r);
        $rows=CashTransaction::with('creator')->whereBetween('transaction_date',[$from->toDateString(),$to->toDateString()])->latest('transaction_date')->latest()->paginate(20)->withQueryString();
        $income=CashTransaction::where('type','income')->whereBetween('transaction_date',[$from->toDateString(),$to->toDateString()])->sum('amount');
        $expense=CashTransaction::where('type','expense')->whereBetween('transaction_date',[$from->toDateString(),$to->toDateString()])->sum('amount');
        return view('admin.finance',compact('rows','income','expense','from','to'));
    }
    public function store(Request $r){
        $d=$r->validate(['type'=>'required|in:income,expense','category'=>'required|string|max:80','description'=>'required|string|max:255','amount'=>'required|integer|min:1','transaction_date'=>'required|date']);
        $d['created_by']=auth()->id();
        CashTransaction::create($d);
        return back()->with('success',($d['type']==='income'?'Pemasukan':'Pengeluaran').' berhasil dicatat.');
    }
    public function destroy(CashTransaction $cashTransaction){$cashTransaction->delete();return back()->with('success','Catatan keuangan dihapus.');}
}
