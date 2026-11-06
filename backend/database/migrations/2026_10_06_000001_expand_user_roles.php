<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner','admin','employee') NOT NULL DEFAULT 'employee'");
    }
    public function down(): void {
        DB::statement("UPDATE users SET role='employee' WHERE role='admin'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner','employee') NOT NULL DEFAULT 'employee'");
    }
};
