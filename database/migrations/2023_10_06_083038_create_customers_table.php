<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('full_name', 300);
                $table->string('national_code', 25);
                $table->string('economic_code', 25)->nullable();
                $table->string('phone_number', 50)->nullable();
                $table->string('telephone_number', 50)->nullable();
                $table->string('city', 100);
                $table->text('address')->nullable();
                $table->string('file', 500)->nullable();
                $table->integer('total_invoice')->default(0);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('customers');
        }
    };
