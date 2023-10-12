<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('members', function (Blueprint $table) {
                $table->id();
                $table->string('full_name', 300);
                $table->string('national_code', 25);
                $table->string('type_activity', 100);
                $table->string('city', 100)->nullable();
                $table->string('phone_number', 50)->nullable();
                $table->string('social_number', 50)->nullable();
                $table->string('profile_picture', 500)->nullable();
                $table->string('national_card_picture', 500);
                $table->string('password', 500);
                $table->boolean('is_block')->default(false);
                $table->boolean('is_primary')->default(false)->nullable();
                $table->string('privileges', 1500)->nullable();
                $table->integer('most_order_count')->default(0);
                $table->integer('last_order_count')->default(0);
                $table->integer('most_sold')->default(0);
                $table->integer('last_sold')->default(0);
                $table->integer('most_expensive')->default(0);
                $table->integer('last_expensive')->default(0);
                $table->integer('most_sold_goods')->default(0);
                $table->integer('last_sold_goods')->default(0);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('members');
        }
    };
