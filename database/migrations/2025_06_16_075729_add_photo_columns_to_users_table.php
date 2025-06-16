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
        Schema::table('users', function (Blueprint $table) {
            // Path to the user's profile photo (e.g., an avatar).
            // Placed after the 'password' column.
            $table->string('profile_photo_path')->nullable()->after('password');

            // Path to a photo of the user's face, for ID verification.
            $table->string('face_photo_path')->nullable()->after('profile_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // The 'down' method should be the reverse of 'up' to allow rollbacks.
            $table->dropColumn(['profile_photo_path', 'face_photo_path']);
        });
    }
};