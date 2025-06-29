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
        Schema::create('categories', function (Blueprint $table) {
            $table->snowflakeId();
            $table->snowflake('wallet_id')->index();
            $table->string('name');
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('emoji')->nullable();
            $table->enum('type', ['IN', 'OUT']);
            $table->bigInteger('budget')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
