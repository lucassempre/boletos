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
        Schema::create('boletos', function (Blueprint $table) {
            $table->uuid('uuid')->index()->primary();
            $table->uuid('processamento_uuid')->nullable()->index();

            $table->string('name', 200)->nullable();
            $table->integer('governmentId')->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('debtAmount')->nullable();
            $table->date('debtDueDate')->nullable();

            $table->string('debtId', 36)->nullable();
            $table->string('status', 80)->default('pendente');
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
        Schema::dropIfExists('boletos');
    }
};
