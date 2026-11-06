<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('sales_forecasts',function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->date('forecast_period');$t->decimal('predicted_quantity',12,2);$t->decimal('actual_quantity',12,2)->nullable();$t->decimal('error_value',12,2)->nullable();$t->string('method');$t->timestamps();});}public function down(){Schema::dropIfExists('sales_forecasts');}};
