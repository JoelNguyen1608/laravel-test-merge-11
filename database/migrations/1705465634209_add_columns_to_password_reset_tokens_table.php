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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->timestamp('expiration')->nullable();
            $table->boolean('used')->default(false);
            $table->unsignedBigInteger('stylist_id')->nullable();
            $table->foreign('stylist_id')->references('id')->on('stylists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropForeign(['stylist_id']);
            $table->dropColumn(['expiration', 'used', 'stylist_id']);
        });
    }
};
