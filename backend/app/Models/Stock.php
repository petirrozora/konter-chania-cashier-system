<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Stock extends Model {
 protected $fillable=['product_id','quantity','minimum_stock'];
 public function product(){return $this->belongsTo(Product::class);}
 public function statusLabel(): string {
   if($this->quantity<=0) return 'Habis';
   if($this->quantity<=$this->minimum_stock) return 'Menipis';
   return 'Aman';
 }
}
