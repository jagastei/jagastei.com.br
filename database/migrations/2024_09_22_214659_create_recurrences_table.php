<?php

declare(strict_types=1);

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
        Schema::create('recurrences', function (Blueprint $table) {
            $table->snowflakeId();
            $table->enum('repeat', ['DAILY', 'WEEKLY', 'MONTHLY', 'YEARLY']);
            $table->jsonb('repeat_options')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['IN', 'OUT']);
            $table->bigInteger('value');
            $table->snowflake('category_id')->index()->nullable();
            $table->snowflake('account_id')->index();
            $table->enum('method', ['CASH', 'CARD', 'TED', 'PIX', 'OTHER', 'UNKNOWN'])->nullable();
            $table->snowflake('card_id')->index()->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurrences');
    }
};
