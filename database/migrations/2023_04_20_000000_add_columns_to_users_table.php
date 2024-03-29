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
            // Add new columns
            $table->string('password_hash')->after('password');
            $table->string('session_token')->nullable()->after('password_hash');
            $table->timestamp('session_expiration')->nullable()->after('session_token');
            $table->boolean('keep_session')->default(false)->after('session_expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the columns in the reverse order that they were added
            $table->dropColumn(['keep_session', 'session_expiration', 'session_token', 'password_hash']);
        });
    }
};
