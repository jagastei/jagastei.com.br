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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->string('name');
            $table->bigInteger('limit')->default(0);

            $table->string('digits')->nullable();
            $table->foreignId('brand_id')->nullable();

            $table->boolean('digital')->default(false);
            $table->boolean('credit')->default(false);
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
