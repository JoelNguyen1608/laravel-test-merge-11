
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
            $table->dateTime('token_expiration')->nullable();
            $table->boolean('keep_session_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stylists');
    }
};
