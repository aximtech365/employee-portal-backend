<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('file_type', 50);
            $table->unsignedBigInteger('file_size');
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->enum('access_level', ['public', 'department', 'private'])->default('public');
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
