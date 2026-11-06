<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class EmployeeController extends Controller {
 public function index(){return view('admin.employees',['employees'=>User::where('role','employee')->latest()->paginate(15)]);}
 public function store(Request $r){$d=$r->validate(['name'=>'required','username'=>'required|unique:users,username','password'=>'required|min:6']);$d['role']='employee';$d['is_active']=true;$d['password']=Hash::make($d['password']);$u=User::create($d);ActivityLogger::log('create_employee',"Menambah karyawan {$u->name}");return back()->with('success','Karyawan ditambahkan.');}
 public function update(Request $r,User $user){$d=$r->validate(['name'=>'required','username'=>'required|unique:users,username,'.$user->id]);$user->update($d);return back()->with('success','Karyawan diperbarui.');}
 public function toggle(User $user){if($user->id===auth()->id())return back()->withErrors(['employee'=>'Anda tidak dapat menonaktifkan akun sendiri.']);$user->update(['is_active'=>!$user->is_active]);return back()->with('success','Status akun diperbarui.');}
}
