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
        Schema::create('processamentos', function (Blueprint $table) {
            $table->uuid('uuid')->index()->primary();
            $table->uuid('operacao_uuid')->nullable()->index();
            $table->string('hash_file')->nullable();
            $table->foreign('operacao_uuid')->references('uuid')->on('operacoes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processamentos');
    }
};
