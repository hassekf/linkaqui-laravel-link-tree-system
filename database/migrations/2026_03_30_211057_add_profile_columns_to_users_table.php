<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->unique()->after('name');
            $table->string('bio', 255)->nullable()->after('username');
            $table->string('avatar_path')->nullable()->after('bio');
            $table->boolean('is_admin')->default(false)->after('avatar_path');
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->json('settings')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'bio', 'avatar_path', 'is_admin', 'is_active', 'settings']);
        });
    }
};
