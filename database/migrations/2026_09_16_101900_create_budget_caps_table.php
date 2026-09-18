<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_caps', function (Blueprint $table) {
            $table->id();
            $table->string('service')->unique();
            $table->decimal('monthly_limit', 10, 2)->nullable();
            $table->unsignedTinyInteger('notify_at_percent')->default(80);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_caps');
    }
};
