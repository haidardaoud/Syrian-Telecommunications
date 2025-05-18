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
            $table->foreignId('job_id')->constrained('job_offers')->onDelete('cascade');
            $table->string('user_name')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_temporary')->default(false);
            $table->boolean('is_suspended')->default(false);;
            //$table->string('password');
            $table->rememberToken();
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
