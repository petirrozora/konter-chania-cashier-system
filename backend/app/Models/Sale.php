<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model {
 protected $fillable=['invoice_code','employee_id','total_price','paid_amount','change_amount'];
 protected $casts=['total_price'=>'integer','paid_amount'=>'integer','change_amount'=>'integer'];
 public function employee(){return $this->belongsTo(User::class,'employee_id');}
 public function items(){return $this->hasMany(SaleItem::class);}
}
