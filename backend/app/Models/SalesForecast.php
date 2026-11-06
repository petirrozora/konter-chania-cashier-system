<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SalesForecast extends Model {
 protected $fillable=['product_id','forecast_period','predicted_quantity','actual_quantity','error_value','method'];
 protected $casts=['forecast_period'=>'date','predicted_quantity'=>'float','actual_quantity'=>'float','error_value'=>'float'];
 public function product(){return $this->belongsTo(Product::class);}
}
