<?php
namespace App\Http\Controllers;
use App\Models\{SalesForecast,RestockRecommendation,Product};
use App\Services\{ForecastService,ActivityLogger};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ForecastController extends Controller {
 public function index(){ $forecasts=SalesForecast::with('product')->latest()->paginate(20);return view('admin.forecasts',compact('forecasts')); }
 public function run(Request $r,ForecastService $service){$days=$r->integer('horizon',7);$result=$service->run(max(1,min(90,$days)));ActivityLogger::log('run_forecast',"Menjalankan estimasi {$days} hari",[],['count'=>count($result)]);return back()->with('success','Estimasi berhasil dibuat dan rekomendasi restock diperbarui.');}
 public function restock(){ $recommendations=RestockRecommendation::with('product')->latest('created_at')->get()->unique('product_id')->sortByDesc(fn($x)=>match($x->status){'Perlu Restock'=>3,'Pantau'=>2,default=>1});return view('admin.restock',compact('recommendations')); }
 public function copyRestock(Request $r){$ids=$r->input('ids',[]);$items=RestockRecommendation::with('product')->whereIn('id',$ids)->get();$text=$items->map(fn($i)=>"{$i->product->name}: {$i->recommended_quantity} {$i->product->unit}")->join("\n");return response()->json(['text'=>$text]);}
 public function import(){return view('admin.forecast-import');}
 public function importStore(Request $r){$r->validate(['file'=>'required|file|mimes:csv,txt,xlsx|max:5120']);return back()->with('success','Berkas diterima untuk tahap validasi. Untuk integrasi penuh XLSX, hubungkan parser Excel sesuai kebutuhan penelitian.');}
}
