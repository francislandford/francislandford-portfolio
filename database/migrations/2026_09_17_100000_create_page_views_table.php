<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('referrer')->nullable();
            $table->string('visitor_hash', 64);
            $table->string('user_agent')->nullable();
            $table->timestamp('viewed_at');

            $table->index('path');
            $table->index('viewed_at');
            $table->index('visitor_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
