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
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('status');
            $table->json('meta')->nullable()->after('published_at');
            $table->softDeletes()->after('updated_at'); // deleted_at: nullable timestamp
            // $table->enum('status', ['published', 'draft', 'archived', 'trashed'])
            //     ->default('draft')->change();
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['published_at', 'meta']);
            $table->dropSoftDeletes();
            // $table->enum('status', ['published', 'draft', 'archived'])
            //     ->default('draft')->change();
        });
    }
};
