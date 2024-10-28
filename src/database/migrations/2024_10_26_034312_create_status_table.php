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
        Schema::create('status', function (Blueprint $table) {
            $table->uuid('uuid')->index()->primary();
            $table->uuid('processamento_uuid')->nullable()->index();
            $table->string('status', 80)->default('pendente')->index();
            $table->text('status_descricao')->nullable();
            $table->foreign('processamento_uuid')->references('uuid')->on('processamentos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
