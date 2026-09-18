<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function ($table) {
            $table->fullText(['title', 'summary', 'body']);
        });

        Schema::table('projects', function ($table) {
            $table->fullText(['title', 'description', 'body']);
        });

        Schema::table('posts', function ($table) {
            $table->fullText(['title', 'excerpt', 'body', 'ai_summary']);
        });
    }

    public function down(): void
    {
        Schema::table('services', function ($table) {
            $table->dropFullText(['title', 'summary', 'body']);
        });

        Schema::table('projects', function ($table) {
            $table->dropFullText(['title', 'description', 'body']);
        });

        Schema::table('posts', function ($table) {
            $table->dropFullText(['title', 'excerpt', 'body', 'ai_summary']);
        });
    }
};
