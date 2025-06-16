<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_verification_documents', function (Blueprint $table) {
            $table->id();

            // Link this document to a user. If the user is deleted, delete this document.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // The path where the image is stored, relative to the storage disk.
            // Example: 'user_documents/abc123_id_front.jpg'
            $table->string('document_path');
            
            // Type of document for easy identification.
            // E.g., 'id_front', 'id_back', 'proof_of_address'
            $table->string('document_type');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verification_documents');
    }
};