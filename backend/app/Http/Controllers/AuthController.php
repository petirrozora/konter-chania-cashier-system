<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\ActivityLogger;

class AuthController extends Controller {
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){
   $data=$request->validate(['username'=>'required','password'=>'required']);
   $user=User::where('username',$data['username'])->first();
   if(!$user || !Hash::check($data['password'],$user->password)){
      return back()->withInput($request->only('username'))->withErrors(['username'=>'Username atau password salah.']);
   }
   if(!$user->is_active) return back()->withInput($request->only('username'))->withErrors(['username'=>'Akun dinonaktifkan. Hubungi owner.']);
   Auth::login($user); $request->session()->regenerate();
   ActivityLogger::log('login','Pengguna masuk ke sistem');
   return redirect($user->isOwner()?route('owner.dashboard'):($user->isAdmin()?route('admin.dashboard'):route('employee.dashboard')));
 }
 public function logout(Request $request){
   ActivityLogger::log('logout','Pengguna keluar dari sistem');
   Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
   return redirect()->route('login');
 }
}
