<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->unsigned();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->bigInteger('member_id')->unsigned();
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->integer('total_invoice')->unsigned()->default(0);
                $table->string('invoice_type', 50)->nullable();
                $table->string('order_status', 50)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('orders');
        }
    };
