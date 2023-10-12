<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('title', 300);
                $table->string('picture', 500)->nullable();
                $table->integer('stock')->unsigned()->default(0);
                $table->string('expiration_date', 50)->nullable();
                $table->text('description')->nullable();
                $table->integer('price')->unsigned()->default(0);
                $table->integer('discount')->unsigned()->default(0);
                $table->string('gift_product', 10)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('products');
        }
    };
