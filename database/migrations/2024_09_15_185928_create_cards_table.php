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
        Schema::create('cards', function (Blueprint $table) {
            $table->snowflakeId();
            $table->snowflake('account_id')->index();
            $table->string('name');
            $table->bigInteger('limit')->default(0);

            $table->string('digits')->nullable();
            $table->foreignUuid('brand_id')->nullable();

            $table->date('expiration_date')->nullable();

            $table->boolean('credit')->default(false);
            $table->boolean('virtual')->default(false);
            $table->boolean('international')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
