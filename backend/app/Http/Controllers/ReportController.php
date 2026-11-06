<?php
namespace App\Http\Controllers;
use App\Models\{Sale,SaleItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller {
 private function range(Request $r){$preset=$r->get('period','month');return match($preset){'today'=>[now()->startOfDay(),now()->endOfDay()],'yesterday'=>[now()->subDay()->startOfDay(),now()->subDay()->endOfDay()],'week'=>[now()->startOfWeek(),now()->endOfWeek()],default=>[now()->startOfMonth(),now()->endOfMonth()]};}
 public function sales(Request $r){[$from,$to]=$this->range($r);$sales=Sale::with('employee')->whereBetween('created_at',[$from,$to])->latest()->paginate(20)->withQueryString();$summary=$sales->sum('total_price');return view('admin.reports-sales',compact('sales','summary','from','to'));}
 public function profit(Request $r){[$from,$to]=$this->range($r);$profit=DB::table('sale_items')->join('sales','sales.id','=','sale_items.sale_id')->join('products','products.id','=','sale_items.product_id')->whereBetween('sales.created_at',[$from,$to])->selectRaw('COALESCE(SUM((sale_items.price_each-products.purchase_price)*sale_items.quantity),0) profit')->value('profit');return view('admin.reports-profit',compact('profit','from','to'));}
 public function analytics(){ $top=DB::table('sale_items')->join('products','products.id','=','sale_items.product_id')->join('sales','sales.id','=','sale_items.sale_id')->where('sales.created_at','>=',now()->subDays(30))->groupBy('products.id','products.name')->select('products.name',DB::raw('SUM(quantity) qty'))->orderByDesc('qty')->limit(10)->get();$low=DB::table('sale_items')->join('products','products.id','=','sale_items.product_id')->join('sales','sales.id','=','sale_items.sale_id')->where('sales.created_at','>=',now()->subDays(30))->groupBy('products.id','products.name')->select('products.name',DB::raw('SUM(quantity) qty'))->orderBy('qty')->limit(10)->get();return view('admin.analytics',compact('top','low'));}
}
