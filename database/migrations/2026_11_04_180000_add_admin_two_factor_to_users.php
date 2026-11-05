<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->text('admin_two_factor_secret')->nullable();
            $table->unsignedBigInteger('admin_two_factor_last_step')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['admin_two_factor_secret', 'admin_two_factor_last_step']);
        });
    }
};
