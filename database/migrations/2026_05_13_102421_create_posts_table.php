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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('category_id')->nullable()
                ->constrained()
                ->nullOnDelete()
                ->nullOnUpdate();
            $table->string('title', 500);
            $table->longText('content');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
