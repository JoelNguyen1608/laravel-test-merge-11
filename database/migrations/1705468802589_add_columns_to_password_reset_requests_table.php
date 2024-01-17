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
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token', 64);
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 20);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['id', 'created_at', 'updated_at', 'token', 'expires_at', 'status', 'user_id']);
        });
    }
};
