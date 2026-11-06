<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('cash_transactions', function (Blueprint $t) {
            $t->id();
            $t->enum('type', ['income','expense']);
            $t->string('category');
            $t->string('description');
            $t->unsignedBigInteger('amount');
            $t->date('transaction_date');
            $t->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $t->string('reference_type')->nullable();
            $t->unsignedBigInteger('reference_id')->nullable();
            $t->timestamps();
            $t->index(['type','transaction_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('cash_transactions'); }
};
