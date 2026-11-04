<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Standard Laravel user tables already include this column.
        // Older/custom schemas may not, preventing persistent login.
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->rememberToken();
            });
        }
    }

    public function down(): void
    {
        // Intentionally do not remove remember_token: it may have existed
        // before this migration, and it is used by Laravel authentication.
    }
};
