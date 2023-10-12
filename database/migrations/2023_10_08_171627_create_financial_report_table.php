<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('financial_report', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->unsigned();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->bigInteger('order_id')->unsigned();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->bigInteger('checkout_id')->unsigned();
                $table->foreign('checkout_id')->references('id')->on('checkouts')->onDelete('cascade');
                $table->bigInteger('amount')->default(0);
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('financial_report');
        }
    };
