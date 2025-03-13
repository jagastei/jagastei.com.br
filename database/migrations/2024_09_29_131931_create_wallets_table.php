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
        Schema::create('wallets', function (Blueprint $table) {
            $table->snowflakeId();
            $table->foreignUuid('user_id');
            $table->string('name');
            $table->boolean('personal')->default(true);
            // $table->enum('type', ['PERSONAL', 'BUSINESS'])->default('PERSONAL');
            $table->string('currency')->default('BRL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
