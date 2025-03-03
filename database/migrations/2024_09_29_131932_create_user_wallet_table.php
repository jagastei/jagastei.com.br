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
        Schema::create('user_wallet', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->snowflake('wallet_id');
            $table->boolean('can_edit')->default(false);
            $table->enum('status', ['PENDING', 'ACCEPTED', 'DECLINED', 'EXPIRED']);
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();

            $table->unique(['user_id', 'wallet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wallet');
    }
};
