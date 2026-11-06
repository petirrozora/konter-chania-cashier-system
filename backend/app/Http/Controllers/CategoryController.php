<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
class CategoryController extends Controller {
 public function index(){return view('admin.categories',['categories'=>Category::withCount('products')->orderBy('name')->get()]);}
 public function store(Request $r){$d=$r->validate(['name'=>'required|max:100|unique:categories,name']);$c=Category::create($d);ActivityLogger::log('create_category',"Menambah kategori {$c->name}");return back()->with('success','Kategori ditambahkan.');}
 public function update(Request $r,Category $category){$d=$r->validate(['name'=>'required|max:100|unique:categories,name,'.$category->id]);$category->update($d);ActivityLogger::log('update_category',"Mengubah kategori {$category->name}");return back()->with('success','Kategori diperbarui.');}
 public function toggle(Category $category){if($category->products()->where('status','active')->exists()) return back()->withErrors(['category'=>'Kategori masih memiliki produk aktif.']);$category->update(['is_active'=>!$category->is_active]);ActivityLogger::log('toggle_category',"Mengubah status kategori {$category->name}");return back()->with('success','Status kategori diperbarui.');}
}
