<?php
namespace App\Http\Controllers;
use App\Models\{Product,Category,Stock};
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller {
 public function index(Request $r){
   $q=$r->string('q')->toString(); $category=$r->integer('category');
   $products=Product::with(['category','stock'])->when($q,fn($x)=>$x->where(fn($w)=>$w->where('name','like',"%$q%")->orWhere('product_code','like',"%$q%")->orWhere('barcode','like',"%$q%")))
    ->when($category,fn($x)=>$x->where('category_id',$category))->orderBy('name')->paginate(12)->withQueryString();
   $categories=Category::where('is_active',true)->orderBy('name')->get(); return view('admin.products',compact('products','q','category','categories'));
 }
 public function store(Request $r){
   $d=$r->validate(['category_id'=>'required|exists:categories,id','product_code'=>'required|unique:products,product_code','barcode'=>'nullable','name'=>'required','unit'=>'required','purchase_price'=>'required|integer|min:0','selling_price'=>'required|integer|min:0','minimum_stock'=>'required|integer|min:0']);
   $d['status']='active'; $d['created_by']=auth()->id(); $minimum=$d['minimum_stock']; unset($d['minimum_stock']);
   $p=Product::create($d); Stock::create(['product_id'=>$p->id,'quantity'=>0,'minimum_stock'=>$minimum]);
   ActivityLogger::log('create_product',"Menambah produk {$p->name}",[], $p->toArray());
   return back()->with('success','Produk berhasil ditambahkan.');
 }
 public function update(Request $r,Product $product){
   $d=$r->validate(['category_id'=>'required|exists:categories,id','product_code'=>['required',Rule::unique('products')->ignore($product->id)],'barcode'=>'nullable','name'=>'required','unit'=>'required','purchase_price'=>'required|integer|min:0','selling_price'=>'required|integer|min:0','minimum_stock'=>'required|integer|min:0']);
   $before=$product->toArray(); $minimum=$d['minimum_stock']; unset($d['minimum_stock']);
   $product->update($d); $product->stock()->update(['minimum_stock'=>$minimum]);
   ActivityLogger::log('update_product',"Mengubah produk {$product->name}",$before,$product->fresh()->toArray());
   return back()->with('success','Produk berhasil diperbarui.');
 }
 public function toggle(Product $product){
   $before=$product->status; $product->update(['status'=>$product->status==='active'?'inactive':'active']);
   ActivityLogger::log('toggle_product',"Mengubah status produk {$product->name}",['status'=>$before],['status'=>$product->status]);
   return back()->with('success','Status produk diperbarui.');
 }
}
