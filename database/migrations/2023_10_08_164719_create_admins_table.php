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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 300);
            $table->string('national_code', 25);
            $table->string('type_activity', 100);
            $table->string('phone_number', 50)->nullable();
            $table->string('national_card_picture', 500)->nullable();
            $table->string('user_name', 50)->unique();
            $table->string('password', 150);
            $table->boolean('is_primary')->default(false)->nullable();
            $table->string('privileges', 1500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
