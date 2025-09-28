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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('otp_hash')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('completed_registration')->default(false);
            $table->string('fcm_token')->nullable();
            $table->decimal('wallet', 12, 2)->default(0);
            $table->json('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('password')->nullable();
            $table->boolean('updated_profile')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
