<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model {
 use HasFactory;
 protected $fillable=['category_id','product_code','barcode','name','description','unit','purchase_price','selling_price','status','created_by'];
 protected $casts=['purchase_price'=>'integer','selling_price'=>'integer'];
 public function category(){return $this->belongsTo(Category::class);}
 public function stock(){return $this->hasOne(Stock::class);}
 public function saleItems(){return $this->hasMany(SaleItem::class);}
 public function getMarginAttribute(){return $this->purchase_price ? (($this->selling_price-$this->purchase_price)/$this->purchase_price)*100 : 0;}
}
