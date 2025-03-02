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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['IN', 'OUT']);
            $table->bigInteger('value');
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('account_id');
            $table->enum('method', ['CASH', 'CARD', 'TED', 'PIX', 'OTHER', 'UNKNOWN'])->nullable();
            $table->foreignUuid('card_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
