<?php
namespace App\Http\Controllers;
use App\Models\{Product,Sale,SaleItem,CashTransaction};
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller {
 public function employeeDashboard(){
   $today=Sale::where('employee_id',auth()->id())->whereDate('created_at',today())->get();
   return view('employee.dashboard',['sales'=>$today,'total'=>$today->sum('total_price')]);
 }
 public function pos(){return view('employee.pos');}
 public function searchProducts(Request $r){
   $q=$r->string('q')->toString();
   $products=Product::with('stock')->where('status','active')->whereHas('stock',fn($s)=>$s->where('quantity','>',0))
     ->where(fn($w)=>$w->where('name','like',"%$q%")->orWhere('product_code','like',"%$q%")->orWhere('barcode',$q))->limit(10)->get();
   return response()->json($products->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'code'=>$p->product_code,'price'=>$p->selling_price,'stock'=>$p->stock->quantity,'unit'=>$p->unit]));
 }
 public function store(Request $r){
   $d=$r->validate(['items'=>'required|array|min:1','items.*.product_id'=>'required|exists:products,id','items.*.quantity'=>'required|integer|min:1','paid_amount'=>'required|integer|min:0']);
   return DB::transaction(function()use($d){
     $total=0;$validated=[];
     foreach($d['items'] as $i){$p=Product::with('stock')->lockForUpdate()->findOrFail($i['product_id']);if($p->stock->quantity<$i['quantity'])throw \Illuminate\Validation\ValidationException::withMessages(['items'=>"Stok {$p->name} tidak mencukupi."]);$sub=$p->selling_price*$i['quantity'];$total+=$sub;$validated[]=['product'=>$p,'quantity'=>$i['quantity'],'subtotal'=>$sub];}
     if($d['paid_amount']<$total)throw \Illuminate\Validation\ValidationException::withMessages(['paid_amount'=>'Pembayaran belum mencukupi.']);
     $sale=Sale::create(['invoice_code'=>'INV-'.now()->format('YmdHis').'-'.random_int(10,99),'employee_id'=>auth()->id(),'total_price'=>$total,'paid_amount'=>$d['paid_amount'],'change_amount'=>$d['paid_amount']-$total]);
     foreach($validated as $v){SaleItem::create(['sale_id'=>$sale->id,'product_id'=>$v['product']->id,'quantity'=>$v['quantity'],'price_each'=>$v['product']->selling_price,'subtotal'=>$v['subtotal']]);$v['product']->stock->decrement('quantity',$v['quantity']);}
     CashTransaction::create(['type'=>'income','category'=>'Penjualan','description'=>'Penerimaan transaksi '.$sale->invoice_code,'amount'=>$total,'transaction_date'=>today(),'created_by'=>auth()->id(),'reference_type'=>'sale','reference_id'=>$sale->id]);
     ActivityLogger::log('sale',"Transaksi {$sale->invoice_code} tersimpan",[],['total'=>$total]);
     return response()->json(['success'=>true,'sale'=>$sale->load('items.product')]);
   });
 }
 public function index(Request $r){$sales=Sale::with('employee')->latest()->paginate(20)->withQueryString();return view('admin.transactions',compact('sales'));}
 public function show(Sale $sale){return view('admin.transaction-detail',['sale'=>$sale->load(['employee','items.product'])]);}
 public function todaySales(){ $sales=Sale::with('items.product')->where('employee_id',auth()->id())->whereDate('created_at',today())->latest()->get();return view('employee.today-sales',compact('sales')); }
}
