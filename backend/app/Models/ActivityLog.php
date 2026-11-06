<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model {
 protected $fillable=['user_id','action','description','before_data','after_data','ip_address'];
 protected $casts=['before_data'=>'array','after_data'=>'array'];
 public function user(){return $this->belongsTo(User::class);}
}
