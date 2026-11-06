<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('activity_logs',function(Blueprint $t){$t->id();$t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();$t->string('action');$t->text('description');$t->json('before_data')->nullable();$t->json('after_data')->nullable();$t->string('ip_address')->nullable();$t->timestamps();});}public function down(){Schema::dropIfExists('activity_logs');}};
