<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('flag');
            $table->integer('points');
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'points']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
