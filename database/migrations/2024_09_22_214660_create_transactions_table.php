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
            $table->snowflakeId();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['IN', 'OUT']);
            $table->bigInteger('value');
            $table->snowflake('category_id')->index()->nullable();
            $table->snowflake('account_id')->index();
            $table->enum('method', ['CASH', 'CARD', 'TED', 'PIX', 'OTHER', 'UNKNOWN'])->nullable();
            $table->snowflake('card_id')->index()->nullable();
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
