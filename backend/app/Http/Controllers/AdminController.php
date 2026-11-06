<?php
namespace App\Http\Controllers;
use App\Models\{Product,Sale,Stock,RestockRecommendation,CashTransaction};
use Illuminate\Support\Facades\DB;
class AdminController extends Controller {
 public function dashboard(){
   $sales=Sale::whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->get();
   $totalSales=$sales->sum('total_price'); $transactions=$sales->count();
   $profit=DB::table('sale_items')->join('sales','sales.id','=','sale_items.sale_id')->join('products','products.id','=','sale_items.product_id')
      ->whereBetween('sales.created_at',[now()->startOfMonth(),now()->endOfMonth()])
      ->selectRaw('COALESCE(SUM((sale_items.price_each-products.purchase_price)*sale_items.quantity),0) v')->value('v');
   $lowStocks=Stock::with('product')->whereColumn('quantity','<=','minimum_stock')->orderBy('quantity')->limit(5)->get();
   $income=CashTransaction::where('type','income')->whereBetween('transaction_date',[now()->startOfMonth()->toDateString(),now()->endOfMonth()->toDateString()])->sum('amount');
   $expense=CashTransaction::where('type','expense')->whereBetween('transaction_date',[now()->startOfMonth()->toDateString(),now()->endOfMonth()->toDateString()])->sum('amount');
   $restockCount=RestockRecommendation::where('status','Perlu Restock')->where('created_at','>=',now()->subDays(7))->count();
   $top=DB::table('sale_items')->join('products','products.id','=','sale_items.product_id')->join('sales','sales.id','=','sale_items.sale_id')
      ->where('sales.created_at','>=',now()->subDays(30))->groupBy('products.id','products.name')->orderByDesc(DB::raw('SUM(quantity)'))->limit(5)
      ->select('products.name',DB::raw('SUM(quantity) qty'))->get();
   return view('admin.dashboard',compact('totalSales','transactions','profit','lowStocks','restockCount','top','income','expense'));
 }
}
