
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
        Schema::create('stylists', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('session_token')->nullable();
            $table->timestamp('token_expiration')->nullable();
            $table->boolean('keep_session_active')->default(false);
            $table->timestamps();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->foreignId('stylist_id')->constrained('stylists')->onDelete('cascade');
        });

        Schema::create('stylist_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stylist_requests');

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropForeign(['stylist_id']);
            $table->dropColumn('stylist_id');
        });

        Schema::dropIfExists('stylists');
    }
};
