<?php
namespace App\Http\Controllers;
use App\Models\{CashTransaction,Sale,Stock,RestockRecommendation};
use Illuminate\Support\Facades\DB;
class OwnerDashboardController extends Controller {
 public function index(){
  $monthSales=Sale::whereBetween("created_at",[now()->startOfMonth(),now()->endOfMonth()]);
  $income=CashTransaction::where("type","income")->whereBetween("transaction_date",[now()->startOfMonth()->toDateString(),now()->endOfMonth()->toDateString()])->sum("amount");
  $expense=CashTransaction::where("type","expense")->whereBetween("transaction_date",[now()->startOfMonth()->toDateString(),now()->endOfMonth()->toDateString()])->sum("amount");
  $low=Stock::with("product")->whereColumn("quantity","<=","minimum_stock")->orderBy("quantity")->limit(6)->get();
  $restock=RestockRecommendation::with("product")->where("status","Perlu Restock")->latest()->limit(6)->get();
  $trend=Sale::selectRaw("DATE(created_at) day, SUM(total_price) total")->where("created_at",">=",now()->subDays(14))->groupBy("day")->orderBy("day")->get();
  return view("owner.dashboard",compact("monthSales","income","expense","low","restock","trend"));
 }
}
