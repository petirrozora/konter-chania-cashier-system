<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('sales',function(Blueprint $t){$t->id();$t->string('invoice_code')->unique();$t->foreignId('employee_id')->constrained('users')->restrictOnDelete();$t->unsignedBigInteger('total_price');$t->unsignedBigInteger('paid_amount');$t->unsignedBigInteger('change_amount');$t->timestamps();});}public function down(){Schema::dropIfExists('sales');}};
