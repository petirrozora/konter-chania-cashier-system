<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('sale_items',function(Blueprint $t){$t->id();$t->foreignId('sale_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->restrictOnDelete();$t->integer('quantity');$t->unsignedBigInteger('price_each');$t->unsignedBigInteger('subtotal');$t->timestamps();});}public function down(){Schema::dropIfExists('sale_items');}};
