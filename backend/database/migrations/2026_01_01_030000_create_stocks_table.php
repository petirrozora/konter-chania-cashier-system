<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('stocks',function(Blueprint $t){$t->id();$t->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();$t->integer('quantity')->default(0);$t->integer('minimum_stock')->default(0);$t->timestamps();});}public function down(){Schema::dropIfExists('stocks');}};
