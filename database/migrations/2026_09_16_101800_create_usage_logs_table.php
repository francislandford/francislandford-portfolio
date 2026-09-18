<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('action')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->decimal('cost', 10, 4)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->nullableMorphs('subject');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['service', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
