<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleItem extends Model {
 protected $fillable=['sale_id','product_id','quantity','price_each','subtotal'];
 protected $casts=['quantity'=>'integer','price_each'=>'integer','subtotal'=>'integer'];
 public function sale(){return $this->belongsTo(Sale::class);}
 public function product(){return $this->belongsTo(Product::class);}
}
