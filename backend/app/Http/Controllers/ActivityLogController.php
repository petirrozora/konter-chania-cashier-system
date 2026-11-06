<?php
namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
class ActivityLogController extends Controller {
 public function index(){return view('admin.activity-logs',['logs'=>ActivityLog::with('user')->latest()->paginate(30)]);}
}
