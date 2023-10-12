<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('order_product', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('order_id')->unsigned();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->bigInteger('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->integer('quantity')->unsigned()->default(1);
                $table->integer('gift_quantity')->unsigned()->default(0);
                $table->string('invoice_type', 50);
                $table->integer('price')->unsigned()->default(0);
                $table->bigInteger('total_invoice')->default(0);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('order_product');
        }
    };
