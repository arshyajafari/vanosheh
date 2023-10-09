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
                $table->string('title', 250);
                $table->string('picture', 500)->nullable();
                $table->integer('stock')->unsigned()->default(0);
                $table->string('expiration_date', 50)->nullable();
                $table->text('description')->nullable();
                $table->integer('price')->unsigned()->default(0);
                $table->string('category_id', 20);
                $table->integer('discount')->unsigned()->default(0);
                $table->integer('gift_product')->unsigned()->default(0);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('products');
        }
    };
