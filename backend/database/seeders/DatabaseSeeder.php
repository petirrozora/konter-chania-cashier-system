<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Category,Product,Stock};
class DatabaseSeeder extends Seeder {
 public function run(){
  $owner=User::create(['name'=>'Owner Kasir Pintar','username'=>'owner','password'=>Hash::make('password'),'role'=>'owner','is_active'=>true]);
  $admin=User::create(['name'=>'Admin Demo','username'=>'admin','password'=>Hash::make('password'),'role'=>'admin','is_active'=>true]);
  $employee=User::create(['name'=>'Karyawan Demo','username'=>'kasir','password'=>Hash::make('password'),'role'=>'employee','is_active'=>true]);
  $cats=collect(['Aksesoris','Charger','Kabel','Audio'])->mapWithKeys(fn($n)=>[$n=>Category::create(['name'=>$n,'is_active'=>true])]);
  $items=[
   ['Charger 20W','CHG-001','Charger',85000,120000,8,25],
   ['Kabel USB-C 1m','KBL-001','Kabel',30000,50000,10,40],
   ['Headset Wired','AUD-001','Audio',55000,85000,5,15],
   ['Powerbank 10000mAh','PWB-001','Aksesoris',110000,150000,5,20],
   ['Adaptor Lightning','CHG-002','Charger',60000,90000,5,12],
   ['Kabel Lightning 1m','KBL-002','Kabel',35000,60000,8,30],
  ];
  foreach($items as [$name,$code,$cat,$buy,$sell,$min,$qty]){
    $p=Product::create(['category_id'=>$cats[$cat]->id,'product_code'=>$code,'name'=>$name,'unit'=>'pcs','purchase_price'=>$buy,'selling_price'=>$sell,'status'=>'active','created_by'=>$owner->id]);
    Stock::create(['product_id'=>$p->id,'quantity'=>$qty,'minimum_stock'=>$min]);
  }
  \App\Models\CashTransaction::create(['type'=>'expense','category'=>'Operasional','description'=>'Pembelian perlengkapan konter','amount'=>250000,'transaction_date'=>today(),'created_by'=>$admin->id]);
  \App\Models\CashTransaction::create(['type'=>'income','category'=>'Modal','description'=>'Modal awal','amount'=>1000000,'transaction_date'=>today()->subDays(3),'created_by'=>$admin->id]);
 }
}
