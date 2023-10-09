<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('return_product', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->unsigned();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->bigInteger('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->bigInteger('member_id')->unsigned();
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->integer('quantity')->unsigned()->default(0);
                $table->string('description', 2500)->nullable();
                $table->string('return_status', 50)->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('return_product');
        }
    };
