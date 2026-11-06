<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RestockRecommendation extends Model {
 protected $fillable=['product_id','current_stock','predicted_demand','safety_stock','recommended_quantity','status'];
 protected $casts=['current_stock'=>'integer','predicted_demand'=>'float','safety_stock'=>'float','recommended_quantity'=>'float'];
 public function product(){return $this->belongsTo(Product::class);}
}
