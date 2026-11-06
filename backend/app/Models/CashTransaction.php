<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CashTransaction extends Model {
    protected $fillable=['type','category','description','amount','transaction_date','created_by','reference_type','reference_id'];
    protected $casts=['amount'=>'integer','transaction_date'=>'date'];
    public function creator(){return $this->belongsTo(User::class,'created_by');}
}
