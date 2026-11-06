<?php
namespace App\Services;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
class ActivityLogger {
 public static function log(string $action,string $description,array $before=[],array $after=[]): void {
   ActivityLog::create([
     'user_id'=>Auth::id(),'action'=>$action,'description'=>$description,
     'before_data'=>$before ?: null,'after_data'=>$after ?: null,
     'ip_address'=>request()->ip()
   ]);
 }
}
